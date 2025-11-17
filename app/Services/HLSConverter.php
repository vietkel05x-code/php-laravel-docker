<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Exception;

class HLSConverter
{
    /**
     * Convert video to HLS format using ffmpeg
     * 
     * @param string $videoPath Path to video file (relative to storage disk)
     * @param string $outputPath Output directory for HLS files (relative to storage disk)
     * @param array $options Conversion options
     * @return array ['success' => bool, 'playlist_path' => string, 'message' => string]
     */
    public function convertToHLS($videoPath, $outputPath = null, $options = [])
    {
        try {
            $disk = Storage::disk('public');
            $fullVideoPath = $disk->path($videoPath);
            
            if (!file_exists($fullVideoPath)) {
                return [
                    'success' => false,
                    'playlist_path' => null,
                    'message' => 'Video file not found: ' . $videoPath
                ];
            }

            // Find ffmpeg executable
            $ffmpegPath = $this->findFFmpeg();
            if (!$ffmpegPath) {
                return [
                    'success' => false,
                    'playlist_path' => null,
                    'message' => 'FFmpeg not found. Please install FFmpeg to use HLS conversion.'
                ];
            }

            // Generate output path if not provided
            if (!$outputPath) {
                $pathInfo = pathinfo($videoPath);
                $outputPath = 'hls/' . $pathInfo['filename'];
            }

            // Ensure output directory exists
            $outputFullPath = $disk->path($outputPath);
            if (!file_exists($outputFullPath)) {
                mkdir($outputFullPath, 0755, true);
            }

            // Default HLS options
            $segmentTime = $options['segment_time'] ?? 10; // 10 seconds per segment
            $playlistName = $options['playlist_name'] ?? 'playlist.m3u8';
            $quality = $options['quality'] ?? 'low'; // Default to low for speed: low, medium, high
            
            // Video quality settings
            $qualitySettings = $this->getQualitySettings($quality);
            
            // Build ffmpeg command
            $playlistPath = $outputPath . '/' . $playlistName;
            $playlistFullPath = $outputFullPath . DIRECTORY_SEPARATOR . $playlistName;
            
            // For Windows, use forward slashes in segment pattern (FFmpeg requirement)
            // IMPORTANT: Don't use escapeshellarg() for segment pattern because it breaks %03d on Windows
            // FFmpeg needs the %03d pattern intact. We'll pass it directly without shell escaping.
            $outputFullPathNormalized = str_replace('\\', '/', $outputFullPath);
            $segmentPattern = $outputFullPathNormalized . '/segment_%03d.ts';
            
            // Get encoding preset for speed optimization
            $preset = $options['preset'] ?? 'ultrafast'; // Default to ultrafast for maximum speed
            
            // Build command parts
            $commandParts = [
                escapeshellarg($ffmpegPath),
                '-i', escapeshellarg($fullVideoPath),
                '-c:v', 'libx264',
                '-preset', $preset, // Encoding speed: ultrafast, superfast, veryfast, faster, fast, medium, slow, slower, veryslow
                '-c:a', 'aac',
                '-hls_time', $segmentTime,
                '-hls_list_size', '0',
                '-hls_segment_filename', $segmentPattern,
                '-hls_flags', 'delete_segments'
            ];
            
            // Add quality settings if provided
            if (!empty($qualitySettings)) {
                $qualityParts = explode(' ', $qualitySettings);
                $commandParts = array_merge($commandParts, $qualityParts);
            }
            
            // Add output file (use forward slashes for Windows compatibility)
            $playlistFullPathForCommand = str_replace('\\', '/', $playlistFullPath);
            $commandParts[] = escapeshellarg($playlistFullPathForCommand);
            
            // Add error redirection
            $command = implode(' ', $commandParts) . ' 2>&1';

            Log::info('Starting HLS conversion', [
                'video_path' => $videoPath,
                'output_path' => $outputPath,
                'command' => $command
            ]);

            // Execute conversion
            // Ensure output directory exists
            $outputDir = dirname($playlistFullPath);
            if (!file_exists($outputDir)) {
                mkdir($outputDir, 0755, true);
            }
            
            // Use proc_open on Windows to properly handle % in segment pattern
            if (PHP_OS_FAMILY === 'Windows') {
                // Ensure output file path is ready (use forward slashes for Windows compatibility)
                $playlistFullPathForCommand = str_replace('\\', '/', $playlistFullPath);
                
                // Build command as string for Windows
                // Use escapeshellarg for paths, but keep %03d pattern intact for segment filename
                $winCommand = escapeshellarg($ffmpegPath);
                $winCommand .= ' -i ' . escapeshellarg($fullVideoPath);
                $winCommand .= ' -c:v libx264';
                $winCommand .= ' -preset ' . escapeshellarg($preset);
                $winCommand .= ' -c:a aac';
                $winCommand .= ' -hls_time ' . (string)$segmentTime;
                $winCommand .= ' -hls_list_size 0';
                
                // For segment filename: escape directory but keep %03d pattern
                // Don't use escapeshellarg for the whole pattern as it breaks %03d
                $segmentDirEscaped = escapeshellarg(dirname($segmentPattern));
                $segmentFile = basename($segmentPattern); // "segment_%03d.ts"
                // Remove quotes from directory and combine with filename
                $segmentDirEscaped = trim($segmentDirEscaped, "'\"");
                $winCommand .= ' -hls_segment_filename "' . str_replace('\\', '/', $segmentDirEscaped) . '/' . $segmentFile . '"';
                
                $winCommand .= ' -hls_flags delete_segments';
                
                // Add quality settings if provided
                // Parse quality settings properly, handling flags and values
                if (!empty($qualitySettings)) {
                    // Split by spaces but keep flags with their values together
                    $parts = preg_split('/\s+/', trim($qualitySettings));
                    $i = 0;
                    while ($i < count($parts)) {
                        $part = $parts[$i];
                        if (empty($part) || $part === '-') {
                            $i++;
                            continue;
                        }
                        
                        // If it's a flag (starts with -), add it and its value
                        if (strpos($part, '-') === 0) {
                            $winCommand .= ' ' . $part;
                            // Check if next part is a value (not a flag)
                            if ($i + 1 < count($parts) && strpos($parts[$i + 1], '-') !== 0) {
                                $winCommand .= ' ' . $parts[$i + 1];
                                $i += 2;
                            } else {
                                $i++;
                            }
                        } else {
                            // Standalone value
                            $winCommand .= ' ' . escapeshellarg($part);
                            $i++;
                        }
                    }
                }
                
                // Add output file (must be last, before 2>&1)
                $winCommand .= ' ' . escapeshellarg($playlistFullPathForCommand);
                $winCommand .= ' 2>&1';
                
                Log::info('FFmpeg command for Windows', [
                    'command' => $winCommand,
                    'output_file' => $playlistFullPathForCommand,
                    'output_dir' => $outputDir,
                ]);
                
                // Execute using proc_open with command string
                $descriptorspec = [
                    0 => ['pipe', 'r'],  // stdin
                    1 => ['pipe', 'w'],  // stdout
                    2 => ['pipe', 'w']   // stderr
                ];
                
                $process = @proc_open($winCommand, $descriptorspec, $pipes, null, null, ['bypass_shell' => false]);
                
                if (is_resource($process)) {
                    // Close stdin
                    fclose($pipes[0]);
                    
                    // Set streams to non-blocking mode
                    stream_set_blocking($pipes[1], false);
                    stream_set_blocking($pipes[2], false);
                    
                    // Read stdout and stderr with timeout
                    $output = [];
                    $startTime = time();
                    $timeout = 1800; // 30 minutes max
                    $stdout = '';
                    $stderr = '';
                    
                    // Read output in chunks to avoid blocking
                    while (true) {
                        $read = [$pipes[1], $pipes[2]];
                        $write = null;
                        $except = null;
                        
                        // Check if process is still running
                        $status = proc_get_status($process);
                        if (!$status['running']) {
                            // Process finished, read remaining output
                            $stdout .= stream_get_contents($pipes[1]);
                            $stderr .= stream_get_contents($pipes[2]);
                            break;
                        }
                        
                        // Check timeout
                        if (time() - $startTime > $timeout) {
                            Log::warning('FFmpeg conversion timeout, terminating process', [
                                'timeout' => $timeout,
                                'video_path' => $videoPath,
                            ]);
                            proc_terminate($process, 9); // SIGKILL
                            proc_close($process);
                            return [
                                'success' => false,
                                'playlist_path' => null,
                                'message' => 'HLS conversion timeout after ' . ($timeout / 60) . ' minutes. Video may be too large or complex.'
                            ];
                        }
                        
                        // Read available data
                        if (stream_select($read, $write, $except, 1) > 0) {
                            foreach ($read as $stream) {
                                $data = stream_get_contents($stream);
                                if ($stream === $pipes[1]) {
                                    $stdout .= $data;
                                } else {
                                    $stderr .= $data;
                                }
                            }
                        }
                        
                        // Small delay to prevent CPU spinning
                        usleep(100000); // 0.1 second
                    }
                    
                    fclose($pipes[1]);
                    fclose($pipes[2]);
                    
                    // Get return code
                    $returnCode = proc_close($process);
                    
                    // Combine output
                    if (!empty($stdout)) {
                        $output = array_merge($output, explode("\n", trim($stdout)));
                    }
                    if (!empty($stderr)) {
                        $output = array_merge($output, explode("\n", trim($stderr)));
                    }
                    
                    Log::info('FFmpeg execution completed', [
                        'return_code' => $returnCode,
                        'output_lines' => count($output),
                        'playlist_exists' => file_exists($playlistFullPath),
                    ]);
                } else {
                    $returnCode = 1;
                    $output = ['Failed to start FFmpeg process'];
                    Log::error('Failed to start FFmpeg process', [
                        'ffmpeg_path' => $ffmpegPath,
                        'command' => $winCommand,
                    ]);
                }
            } else {
                // On Linux/Mac, use exec as before
                $output = [];
                $returnCode = 0;
                exec($command, $output, $returnCode);
            }

            if ($returnCode !== 0 || !file_exists($playlistFullPath)) {
                $errorMessage = implode("\n", $output);
                Log::error('HLS conversion failed', [
                    'video_path' => $videoPath,
                    'return_code' => $returnCode,
                    'error' => $errorMessage
                ]);
                
                return [
                    'success' => false,
                    'playlist_path' => null,
                    'message' => 'HLS conversion failed: ' . $errorMessage
                ];
            }

            Log::info('HLS conversion completed', [
                'video_path' => $videoPath,
                'playlist_path' => $playlistPath,
                'output_path' => $outputPath
            ]);

            return [
                'success' => true,
                'playlist_path' => $playlistPath,
                'output_path' => $outputPath,
                'message' => 'Video converted to HLS successfully'
            ];

        } catch (Exception $e) {
            Log::error('HLS conversion exception', [
                'video_path' => $videoPath,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'playlist_path' => null,
                'message' => 'HLS conversion error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get quality settings for ffmpeg
     * Optimized for maximum speed while maintaining acceptable quality
     */
    private function getQualitySettings($quality)
    {
        $settings = [
            // Low: Fastest, smaller file size, acceptable quality for web
            'low' => '-b:v 500k -maxrate 500k -bufsize 1000k -s 640x360 -crf 28 -threads 0',
            // Medium: Balanced speed and quality
            'medium' => '-b:v 1500k -maxrate 1500k -bufsize 3000k -s 1280x720 -crf 23 -threads 0',
            // High: Better quality, slower
            'high' => '-b:v 3000k -maxrate 3000k -bufsize 6000k -s 1920x1080 -crf 20 -threads 0',
        ];

        return $settings[$quality] ?? $settings['low'];
    }

    /**
     * Find ffmpeg executable path
     */
    private function findFFmpeg()
    {
        $possiblePaths = [
            'ffmpeg',
            '/usr/bin/ffmpeg',
            '/usr/local/bin/ffmpeg',
            'C:\\ffmpeg\\bin\\ffmpeg.exe',
            'C:\\Program Files\\ffmpeg\\bin\\ffmpeg.exe',
        ];

        foreach ($possiblePaths as $path) {
            if ($this->isExecutable($path)) {
                return $path;
            }
        }

        // Try to find in PATH
        $whichCommand = PHP_OS_FAMILY === 'Windows' ? 'where' : 'which';
        $output = [];
        exec("$whichCommand ffmpeg 2>&1", $output, $returnCode);
        
        if ($returnCode === 0 && !empty($output[0]) && file_exists($output[0])) {
            return $output[0];
        }

        return null;
    }

    /**
     * Check if executable exists and is executable
     */
    private function isExecutable($path)
    {
        if (PHP_OS_FAMILY === 'Windows') {
            return file_exists($path) && is_file($path);
        }
        
        return file_exists($path) && is_executable($path);
    }

    /**
     * Delete original video file after successful HLS conversion
     */
    public function deleteOriginalVideo($videoPath)
    {
        try {
            $disk = Storage::disk('public');
            if ($disk->exists($videoPath)) {
                $disk->delete($videoPath);
                Log::info('Original video deleted after HLS conversion', [
                    'video_path' => $videoPath
                ]);
                return true;
            }
            return false;
        } catch (Exception $e) {
            Log::warning('Failed to delete original video', [
                'video_path' => $videoPath,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Delete HLS files (playlist and segments)
     */
    public function deleteHLSFiles($hlsPath)
    {
        try {
            $disk = Storage::disk('public');
            if ($disk->exists($hlsPath)) {
                // Delete directory and all contents
                $disk->deleteDirectory($hlsPath);
                Log::info('HLS files deleted', [
                    'hls_path' => $hlsPath
                ]);
                return true;
            }
            return false;
        } catch (Exception $e) {
            Log::warning('Failed to delete HLS files', [
                'hls_path' => $hlsPath,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}

