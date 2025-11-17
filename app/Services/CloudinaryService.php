<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Exception\ConfigurationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CloudinaryService
{
    private $cloudinary;

    public function __construct()
    {
        $cloudName = config('services.cloudinary.cloud_name');
        $apiKey = config('services.cloudinary.api_key');
        $apiSecret = config('services.cloudinary.api_secret');

        // Trim whitespace from credentials
        $cloudName = trim($cloudName ?? '');
        $apiKey = trim($apiKey ?? '');
        $apiSecret = trim($apiSecret ?? '');

        if (empty($cloudName) || empty($apiKey) || empty($apiSecret)) {
            Log::info('Cloudinary credentials not configured, service will not be available');
            $this->cloudinary = null;
            return;
        }

        try {
            // Set configuration using environment variables first
            // Cloudinary SDK will automatically read from environment if set
            putenv("CLOUDINARY_URL=cloudinary://{$apiKey}:{$apiSecret}@{$cloudName}");
            
            // Also set configuration directly as fallback
            Configuration::instance([
                'cloud' => [
                    'cloud_name' => $cloudName,
                    'api_key' => $apiKey,
                    'api_secret' => $apiSecret,
                ],
            ]);

            // Test configuration by creating Cloudinary instance
            $this->cloudinary = new Cloudinary();
            
            // Verify configuration by trying to access cloud name
            $config = Configuration::instance();
            $verifiedCloudName = $config->cloud->cloudName ?? null;
            
            if ($verifiedCloudName !== $cloudName) {
                throw new \Exception("Cloudinary configuration verification failed. Expected: {$cloudName}, Got: {$verifiedCloudName}");
            }
            
            Log::info('Cloudinary initialized successfully', [
                'cloud_name' => $cloudName,
                'verified' => true,
            ]);
        } catch (ConfigurationException $e) {
            Log::warning('Cloudinary configuration error', [
                'error' => $e->getMessage(),
                'cloud_name' => $cloudName,
                'has_api_key' => !empty($apiKey),
                'has_api_secret' => !empty($apiSecret),
                'message' => 'Please check your Cloudinary credentials in .env file',
            ]);
            $this->cloudinary = null;
        } catch (\Exception $e) {
            Log::error('Failed to initialize Cloudinary', [
                'error' => $e->getMessage(),
                'class' => get_class($e),
                'cloud_name' => $cloudName,
            ]);
            $this->cloudinary = null;
        }
    }

    /**
     * Sanitize course name for Cloudinary folder
     * Cloudinary folders can only contain: letters, numbers, underscore, hyphen, dot
     * 
     * @param string $courseName Original course name
     * @return string Sanitized folder name
     */
    public function sanitizeFolderName($courseName)
    {
        // Convert Vietnamese characters to ASCII
        $folder = $this->removeVietnameseAccents($courseName);
        
        // Replace spaces and special characters with underscores
        $folder = preg_replace('/[^a-zA-Z0-9._-]/', '_', $folder);
        
        // Remove multiple consecutive underscores
        $folder = preg_replace('/_+/', '_', $folder);
        
        // Remove leading/trailing underscores and dots
        $folder = trim($folder, '_.-');
        
        // If empty after sanitization, use default
        if (empty($folder)) {
            $folder = 'lessons';
        }
        
        // Limit length (Cloudinary max is 255, but we'll be safe with 100)
        if (strlen($folder) > 100) {
            $folder = substr($folder, 0, 100);
        }
        
        // Ensure it doesn't start with a number (Cloudinary restriction)
        if (preg_match('/^\d/', $folder)) {
            $folder = 'course_' . $folder;
        }
        
        return $folder;
    }

    /**
     * Remove Vietnamese accents from string
     * 
     * @param string $str String with Vietnamese characters
     * @return string String without accents
     */
    private function removeVietnameseAccents($str)
    {
        $accents = [
            'À' => 'A', 'Á' => 'A', 'Ạ' => 'A', 'Ả' => 'A', 'Ã' => 'A', 'Â' => 'A', 'Ầ' => 'A', 'Ấ' => 'A', 'Ậ' => 'A', 'Ẩ' => 'A', 'Ẫ' => 'A',
            'Ă' => 'A', 'Ằ' => 'A', 'Ắ' => 'A', 'Ặ' => 'A', 'Ẳ' => 'A', 'Ẵ' => 'A',
            'È' => 'E', 'É' => 'E', 'Ẹ' => 'E', 'Ẻ' => 'E', 'Ẽ' => 'E', 'Ê' => 'E', 'Ề' => 'E', 'Ế' => 'E', 'Ệ' => 'E', 'Ể' => 'E', 'Ễ' => 'E',
            'Ì' => 'I', 'Í' => 'I', 'Ị' => 'I', 'Ỉ' => 'I', 'Ĩ' => 'I',
            'Ò' => 'O', 'Ó' => 'O', 'Ọ' => 'O', 'Ỏ' => 'O', 'Õ' => 'O', 'Ô' => 'O', 'Ồ' => 'O', 'Ố' => 'O', 'Ộ' => 'O', 'Ổ' => 'O', 'Ỗ' => 'O',
            'Ơ' => 'O', 'Ờ' => 'O', 'Ớ' => 'O', 'Ợ' => 'O', 'Ở' => 'O', 'Ỡ' => 'O',
            'Ù' => 'U', 'Ú' => 'U', 'Ụ' => 'U', 'Ủ' => 'U', 'Ũ' => 'U', 'Ư' => 'U', 'Ừ' => 'U', 'Ứ' => 'U', 'Ự' => 'U', 'Ử' => 'U', 'Ữ' => 'U',
            'Ỳ' => 'Y', 'Ý' => 'Y', 'Ỵ' => 'Y', 'Ỷ' => 'Y', 'Ỹ' => 'Y',
            'Đ' => 'D',
            'à' => 'a', 'á' => 'a', 'ạ' => 'a', 'ả' => 'a', 'ã' => 'a', 'â' => 'a', 'ầ' => 'a', 'ấ' => 'a', 'ậ' => 'a', 'ẩ' => 'a', 'ẫ' => 'a',
            'ă' => 'a', 'ằ' => 'a', 'ắ' => 'a', 'ặ' => 'a', 'ẳ' => 'a', 'ẵ' => 'a',
            'è' => 'e', 'é' => 'e', 'ẹ' => 'e', 'ẻ' => 'e', 'ẽ' => 'e', 'ê' => 'e', 'ề' => 'e', 'ế' => 'e', 'ệ' => 'e', 'ể' => 'e', 'ễ' => 'e',
            'ì' => 'i', 'í' => 'i', 'ị' => 'i', 'ỉ' => 'i', 'ĩ' => 'i',
            'ò' => 'o', 'ó' => 'o', 'ọ' => 'o', 'ỏ' => 'o', 'õ' => 'o', 'ô' => 'o', 'ồ' => 'o', 'ố' => 'o', 'ộ' => 'o', 'ổ' => 'o', 'ỗ' => 'o',
            'ơ' => 'o', 'ờ' => 'o', 'ớ' => 'o', 'ợ' => 'o', 'ở' => 'o', 'ỡ' => 'o',
            'ù' => 'u', 'ú' => 'u', 'ụ' => 'u', 'ủ' => 'u', 'ũ' => 'u', 'ư' => 'u', 'ừ' => 'u', 'ứ' => 'u', 'ự' => 'u', 'ử' => 'u', 'ữ' => 'u',
            'ỳ' => 'y', 'ý' => 'y', 'ỵ' => 'y', 'ỷ' => 'y', 'ỹ' => 'y',
            'đ' => 'd',
        ];
        
        return strtr($str, $accents);
    }

    /**
     * Upload video to Cloudinary
     * Returns HLS URL and other formats
     * 
     * @param string $filePath Local file path or URL
     * @param array $options Additional upload options (can include 'folder' for custom folder path)
     * @return array
     */
    public function uploadVideo($filePath, $options = [])
    {
        if (!$this->cloudinary) {
            return [
                'success' => false,
                'error' => 'Cloudinary not configured',
            ];
        }

        try {
            // Check file size to determine if we need async processing
            $fileSize = file_exists($filePath) ? filesize($filePath) : 0;
            $fileSizeMB = $fileSize / (1024 * 1024); // Convert to MB
            
            // Cloudinary has issues with eager transformation on videos > 30MB
            // Always use on-the-fly transformation for better reliability
            // Upload video first, then Cloudinary generates HLS on-demand when URL is accessed
            $skipEager = $fileSizeMB > 30 || $fileSize == 0;
            
            // Default options for video upload
            // Use custom folder from options if provided, otherwise use default
            $defaultFolder = 'videos/lessons';
            if (isset($options['folder']) && !empty($options['folder'])) {
                $defaultFolder = $options['folder'];
                // Remove folder from options to avoid duplication when merging
                unset($options['folder']);
            }
            
            $uploadOptions = [
                'resource_type' => 'video',
                'folder' => $defaultFolder,
            ];
            
            // Only add transformation and eager for very small videos (< 30MB)
            // For larger videos, upload raw video without any transformation
            if (!$skipEager) {
                $uploadOptions['transformation'] = [
                    ['quality' => 'auto', 'fetch_format' => 'auto'],
                ];
                $uploadOptions['eager'] = [
                    [
                        'format' => 'm3u8',
                        'streaming_profile' => 'hd', // Options: sd, hd, full_hd, 4k
                    ],
                ];
                // Don't use async for small videos, process synchronously
                $uploadOptions['eager_async'] = false;
            } else {
                // For large videos: NO transformation, NO eager
                // Upload raw video, transform on-the-fly when accessed
                Log::info('Uploading raw video without transformation (on-the-fly transformation will be used)', [
                    'file_size_mb' => round($fileSizeMB, 2),
                ]);
            }
            
            // Merge custom options (but be careful with eager)
            if (!empty($options)) {
                foreach ($options as $key => $value) {
                    // Skip eager-related for large videos
                    if ($skipEager && in_array($key, ['eager', 'eager_async', 'transformation'])) {
                        continue;
                    }
                    // Allow other options
                    $uploadOptions[$key] = $value;
                }
            }
            
            // Final cleanup: ensure eager and transformation are removed for large videos
            if ($skipEager) {
                unset($uploadOptions['eager']);
                unset($uploadOptions['eager_async']);
                unset($uploadOptions['transformation']); // Also remove transformation to avoid processing
            }

            Log::info('Uploading video to Cloudinary', [
                'file' => $filePath,
                'options' => $uploadOptions,
            ]);

            $result = $this->cloudinary->uploadApi()->upload($filePath, $uploadOptions);

            // Cloudinary returns duration in SECONDS (float)
            // Log raw duration value for debugging
            $rawDuration = $result['duration'] ?? null;
            Log::info('Video uploaded to Cloudinary successfully', [
                'public_id' => $result['public_id'],
                'secure_url' => $result['secure_url'],
                'eager' => $result['eager'] ?? null,
                'eager_async' => $uploadOptions['eager_async'] ?? false,
                'file_size_mb' => round($fileSizeMB, 2),
                'raw_duration' => $rawDuration,
                'duration_type' => 'seconds (from Cloudinary API)',
            ]);

            // Get HLS URL from eager transformation or construct it
            $hlsUrl = null;
            if (isset($result['eager']) && !empty($result['eager'])) {
                // HLS URL from eager transformation (sync processing)
                $hlsUrl = $result['eager'][0]['secure_url'] ?? null;
            }
            
            // If HLS not ready yet (async processing or not in eager), construct the URL manually
            if (!$hlsUrl) {
                // Construct HLS URL using Cloudinary on-the-fly transformation
                // Format: https://res.cloudinary.com/{cloud_name}/video/upload/sp_{profile}/{public_id}.m3u8
                // Cloudinary will generate HLS automatically when this URL is first accessed
                $publicId = $result['public_id'];
                $cloudName = config('services.cloudinary.cloud_name');
                
                // Ensure public_id doesn't have .m3u8 extension already
                $publicId = rtrim($publicId, '.m3u8');
                
                // Construct HLS URL with streaming profile
                // Use secure URL (https) for better compatibility
                $hlsUrl = "https://res.cloudinary.com/{$cloudName}/video/upload/sp_hd/{$publicId}.m3u8";
                
                Log::info('Constructed HLS URL using on-the-fly transformation', [
                    'hls_url' => $hlsUrl,
                    'public_id' => $publicId,
                    'secure_url' => $result['secure_url'] ?? null,
                    'note' => 'HLS will be generated automatically when first accessed (may take a few minutes)',
                ]);
            }

            // Cloudinary returns duration in SECONDS (float)
            // Convert to integer seconds for storage
            $durationSeconds = isset($result['duration']) ? (int) round($result['duration']) : 0;
            
            return [
                'success' => true,
                'public_id' => $result['public_id'],
                'secure_url' => $result['secure_url'],
                'hls_url' => $hlsUrl,
                'duration' => $durationSeconds, // Duration in SECONDS (not minutes)
                'width' => $result['width'] ?? 0,
                'height' => $result['height'] ?? 0,
                'bytes' => $result['bytes'] ?? 0,
                'format' => $result['format'] ?? null,
                'on_the_fly' => !isset($result['eager']) || empty($result['eager']), // Flag if using on-the-fly
            ];
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            
            // If error is about video being too large, retry WITHOUT eager transformation
            // Upload video first, then use on-the-fly transformation
            if (strpos($errorMessage, 'too large to process synchronously') !== false || 
                strpos($errorMessage, 'eager_async=true') !== false ||
                strpos($errorMessage, 'eager transformation') !== false) {
                
                Log::warning('Video too large for eager transformation, retrying without eager (on-the-fly transformation)', [
                    'file' => $filePath,
                    'file_size_mb' => round($fileSizeMB, 2),
                ]);
                
                // Retry WITHOUT eager transformation - use on-the-fly transformation instead
                try {
                    // Upload video WITHOUT any eager transformation
                    // Cloudinary will generate HLS on-demand when URL is accessed
                    // Build clean options array without any eager-related parameters
                    // Also remove transformation to avoid any processing during upload
                    // Use the same folder as the original upload attempt
                    $asyncUploadOptions = [
                        'resource_type' => 'video',
                        'folder' => $uploadOptions['folder'], // Keep the same folder (could be custom course folder)
                        // NO transformation - upload raw video, transform on-the-fly later
                    ];
                    
                    // Only merge safe options (exclude eager, eager_async, and any transformation-related)
                    if (!empty($options)) {
                        foreach ($options as $key => $value) {
                            // Skip eager-related options completely
                            if (in_array($key, ['eager', 'eager_async'])) {
                                continue;
                            }
                            // Only merge safe options
                            if (in_array($key, ['resource_type', 'folder', 'public_id', 'overwrite', 'invalidate', 'tags', 'context'])) {
                                $asyncUploadOptions[$key] = $value;
                            }
                        }
                    }
                    
                    // CRITICAL: Ensure eager and eager_async are completely removed
                    unset($asyncUploadOptions['eager']);
                    unset($asyncUploadOptions['eager_async']);
                    
                    Log::info('Retrying Cloudinary upload WITHOUT eager transformation (on-the-fly will be used)', [
                        'file' => $filePath,
                        'file_size_mb' => round($fileSizeMB, 2),
                        'options' => $asyncUploadOptions,
                    ]);
                    
                    $result = $this->cloudinary->uploadApi()->upload($filePath, $asyncUploadOptions);
                    
                    // Get public_id and construct HLS URL using on-the-fly transformation
                    // Cloudinary will generate HLS automatically when this URL is first accessed
                    $publicId = $result['public_id'];
                    $cloudName = config('services.cloudinary.cloud_name');
                    
                    // Ensure public_id doesn't have .m3u8 extension already
                    $publicId = rtrim($publicId, '.m3u8');
                    
                    // Use on-the-fly transformation URL (sp_hd = streaming profile HD)
                    // Cloudinary generates HLS on-demand when this URL is accessed
                    // Format: https://res.cloudinary.com/{cloud_name}/video/upload/sp_{profile}/{public_id}.m3u8
                    $hlsUrl = "https://res.cloudinary.com/{$cloudName}/video/upload/sp_hd/{$publicId}.m3u8";
                    
                    Log::info('Video uploaded successfully without eager transformation', [
                        'public_id' => $publicId,
                        'hls_url' => $hlsUrl,
                        'note' => 'HLS will be generated automatically on first access (on-the-fly transformation)',
                    ]);
                    
                    // Cloudinary returns duration in SECONDS (float)
                    // Convert to integer seconds for storage
                    $durationSeconds = isset($result['duration']) ? (int) round($result['duration']) : 0;
                    
                    return [
                        'success' => true,
                        'public_id' => $publicId,
                        'secure_url' => $result['secure_url'],
                        'hls_url' => $hlsUrl,
                        'duration' => $durationSeconds, // Duration in SECONDS (not minutes)
                        'width' => $result['width'] ?? 0,
                        'height' => $result['height'] ?? 0,
                        'bytes' => $result['bytes'] ?? 0,
                        'format' => $result['format'] ?? null,
                        'on_the_fly' => true, // Flag to indicate on-the-fly transformation
                    ];
                } catch (\Exception $retryException) {
                    Log::error('Cloudinary upload retry failed', [
                        'file' => $filePath,
                        'file_size_mb' => round($fileSizeMB, 2),
                        'error' => $retryException->getMessage(),
                        'trace' => $retryException->getTraceAsString(),
                    ]);
                    
                    return [
                        'success' => false,
                        'error' => $retryException->getMessage(),
                    ];
                }
            }
            
            Log::error('Cloudinary upload error', [
                'file' => $filePath,
                'error' => $errorMessage,
                'file_size_mb' => round($fileSizeMB, 2),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'error' => $errorMessage,
            ];
        }
    }

    /**
     * Delete video from Cloudinary
     * 
     * @param string $publicId Cloudinary public ID
     * @return bool
     */
    public function deleteVideo($publicId)
    {
        if (!$this->cloudinary) {
            return false;
        }

        try {
            $result = $this->cloudinary->uploadApi()->destroy($publicId, [
                'resource_type' => 'video',
            ]);

            $success = $result['result'] === 'ok';
            
            if ($success) {
                Log::info('Video deleted from Cloudinary', [
                    'public_id' => $publicId,
                ]);
            } else {
                Log::warning('Failed to delete video from Cloudinary', [
                    'public_id' => $publicId,
                    'result' => $result,
                ]);
            }

            return $success;
        } catch (\Exception $e) {
            Log::error('Cloudinary delete error', [
                'public_id' => $publicId,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Get video info from Cloudinary
     * 
     * @param string $publicId Cloudinary public ID
     * @return array
     */
    public function getVideoInfo($publicId)
    {
        if (!$this->cloudinary) {
            return [
                'success' => false,
                'error' => 'Cloudinary not configured',
            ];
        }

        try {
            $result = $this->cloudinary->adminApi()->asset($publicId, [
                'resource_type' => 'video',
            ]);

            // Cloudinary returns duration in SECONDS (float)
            // Convert to integer seconds for storage
            $durationSeconds = isset($result['duration']) ? (int) round($result['duration']) : 0;
            
            return [
                'success' => true,
                'duration' => $durationSeconds, // Duration in SECONDS (not minutes)
                'width' => $result['width'] ?? 0,
                'height' => $result['height'] ?? 0,
                'bytes' => $result['bytes'] ?? 0,
                'format' => $result['format'] ?? null,
                'secure_url' => $result['secure_url'] ?? null,
            ];
        } catch (\Exception $e) {
            Log::error('Cloudinary get info error', [
                'public_id' => $publicId,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Check if Cloudinary is configured
     * 
     * @return bool
     */
    public function isConfigured()
    {
        return $this->cloudinary !== null;
    }
}

