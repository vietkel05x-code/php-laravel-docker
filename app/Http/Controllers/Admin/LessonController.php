<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Lesson;
use App\Services\YouTubeService;
use App\Services\HLSConverter;
use App\Services\CloudinaryService;
use App\Jobs\ConvertVideoToHLS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class LessonController extends Controller
{

    public function store(Request $request, Section $section)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'video_file' => 'nullable|file|mimes:mp4,avi,mov,wmv,flv,webm|max:1331200', // 1.3GB max (1331200 KB)
            'video_path' => 'nullable|string|max:500',
            'hls_path' => 'nullable|string|max:500',
            'video_url' => 'nullable|url|max:500',
            'attachment_file' => 'nullable|file|mimes:pdf,doc,docx,zip,rar|max:1331200', // 1.3GB max
            'attachment_path' => 'nullable|string|max:500',
            'duration' => 'nullable|integer|min:0',
            'position' => 'nullable|integer',
        ]);

        // Handle video file upload - Only Cloudinary or HLS (no direct video_path)
        if ($request->hasFile('video_file')) {
            $videoFile = $request->file('video_file');
            
            // ALWAYS try Cloudinary first (credentials are configured)
            $cloudinaryService = new CloudinaryService();
            $isCloudinaryConfigured = $cloudinaryService->isConfigured();
            
            Log::info('Video upload - Checking Cloudinary', [
                'is_configured' => $isCloudinaryConfigured,
                'cloud_name' => config('services.cloudinary.cloud_name'),
                'has_api_key' => !empty(config('services.cloudinary.api_key')),
                'file_name' => $videoFile->getClientOriginalName(),
                'file_size' => $videoFile->getSize(),
            ]);
            
            if ($isCloudinaryConfigured) {
                try {
                    // Get course name for folder organization
                    $course = $section->course;
                    $courseName = $course->title ?? 'lessons';
                    
                    // Sanitize course name for Cloudinary folder
                    // Format: videos/{sanitized_course_name}
                    $folderName = $cloudinaryService->sanitizeFolderName($courseName);
                    $cloudinaryFolder = "videos/{$folderName}";
                    
                    Log::info('Attempting Cloudinary upload', [
                        'file_path' => $videoFile->getRealPath(),
                        'file_exists' => file_exists($videoFile->getRealPath()),
                        'course_name' => $courseName,
                        'cloudinary_folder' => $cloudinaryFolder,
                    ]);
                    
                    // Upload to Cloudinary with course-specific folder
                    $uploadResult = $cloudinaryService->uploadVideo($videoFile->getRealPath(), [
                        'folder' => $cloudinaryFolder,
                    ]);
                    
                    Log::info('Cloudinary upload result', [
                        'success' => $uploadResult['success'] ?? false,
                        'error' => $uploadResult['error'] ?? null,
                        'public_id' => $uploadResult['public_id'] ?? null,
                    ]);
                    
                    if ($uploadResult['success']) {
                        // Store Cloudinary direct video URL (not HLS) for immediate playback
                        // Use secure_url instead of hls_url to avoid waiting for HLS generation
                        $validated['video_url'] = $uploadResult['secure_url'];
                        $validated['cloudinary_id'] = $uploadResult['public_id'];
                        // Cloudinary returns duration in SECONDS, store as seconds
                        $validated['duration'] = (int) ($uploadResult['duration'] ?? 0);
                        $validated['video_path'] = null; // No local storage
                        $validated['hls_path'] = null; // No local HLS
                        
                        Log::info('Video uploaded to Cloudinary successfully (using direct URL)', [
                            'public_id' => $uploadResult['public_id'],
                            'secure_url' => $uploadResult['secure_url'],
                            'hls_url' => $uploadResult['hls_url'] ?? null,
                            'duration' => $uploadResult['duration'],
                            'duration_in_seconds' => $validated['duration'],
                            'file' => $videoFile->getClientOriginalName(),
                        ]);
                    } else {
                        // Fallback to local HLS conversion if Cloudinary fails
                        Log::warning('Cloudinary upload failed, falling back to local HLS conversion', [
                            'error' => $uploadResult['error'] ?? 'Unknown error',
                        ]);
                        $this->handleLocalHLSConversion($request, $validated);
                    }
                } catch (\Exception $e) {
                    Log::error('Cloudinary upload exception, falling back to local HLS conversion', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                    $this->handleLocalHLSConversion($request, $validated);
                }
            } else {
                // Cloudinary not configured, use local HLS conversion
                Log::warning('Cloudinary not configured, using local HLS conversion', [
                    'cloud_name' => config('services.cloudinary.cloud_name'),
                ]);
                $this->handleLocalHLSConversion($request, $validated);
            }
        } else {
            // No video file uploaded, preserve video_url from request (for YouTube/other URLs)
            if ($request->filled('video_url')) {
                $validated['video_url'] = $request->input('video_url');
                Log::info('Preserving video_url from request (no file upload)', [
                    'video_url' => $validated['video_url'],
                ]);
            }
        }

        // Handle attachment file upload
        if ($request->hasFile('attachment_file')) {
            $attachmentPath = $request->file('attachment_file')->store('attachments/lessons', 'public');
            $validated['attachment_path'] = $attachmentPath;
        }

        // Remove file inputs from validated array
        unset($validated['video_file'], $validated['attachment_file']);

        // Auto-calculate duration if not already calculated from uploaded file
        // ALWAYS prefer YouTube API if YouTube URL is provided (even if user entered duration manually)
        // This ensures we store SECONDS, not minutes
        if (!empty($validated['video_url'])) {
            $youtubeService = new YouTubeService();
            if ($youtubeService->isYouTubeUrl($validated['video_url'])) {
                $youtubeDuration = $youtubeService->getVideoDuration($validated['video_url']);
                if ($youtubeDuration['seconds'] > 0) {
                    // YouTube API returns duration in ISO 8601 format, parsed to SECONDS
                    // Store duration in SECONDS (not minutes) for precision
                    // Override any manually entered duration to ensure consistency
                    $validated['duration'] = (int) $youtubeDuration['seconds'];
                    Log::info('Duration calculated from YouTube API (overriding form input if any)', [
                        'url' => $validated['video_url'],
                        'seconds' => $youtubeDuration['seconds'],  // This is what we store
                        'minutes' => $youtubeDuration['duration'],  // For display only
                        'formatted' => $youtubeDuration['formatted'],
                        'stored_as' => 'seconds',
                        'note' => 'Duration stored in database as SECONDS, not minutes. Form input ignored for YouTube URLs.'
                    ]);
                }
            }
        }
        
        // If no YouTube URL or YouTube API failed, check if duration was manually entered
        // Note: Manual input should be in SECONDS (form label says "giây")
        if ((!isset($validated['duration']) || $validated['duration'] == null || $validated['duration'] == 0) && empty($validated['video_url'])) {
            // Note: No longer calculating duration from video_path as we don't store direct video files
        }
        
        // Ensure duration is always set (not null) - default to 0
        if (!isset($validated['duration']) || $validated['duration'] === null) {
            $validated['duration'] = 0;
            Log::info('Duration set to default 0', [
                'has_video_file' => $request->hasFile('video_file'),
                'has_video_url' => !empty($validated['video_url'] ?? ''),
                'has_hls_path' => !empty($validated['hls_path'] ?? ''),
                'has_cloudinary_id' => !empty($validated['cloudinary_id'] ?? '')
            ]);
        }

        // Get max position if not provided (only if truly empty/null, not if user entered 0)
        if (!isset($validated['position']) || $validated['position'] === null || $validated['position'] === '') {
            $maxPosition = $section->lessons()->max('position') ?? 0;
            $validated['position'] = $maxPosition + 1;
        } else {
            // Ensure position is an integer
            $validated['position'] = (int) $validated['position'];
        }

        $validated['is_preview'] = $request->has('is_preview') ? 1 : 0;

        $lesson = $section->lessons()->create($validated);

        // Convert HLS only if using local storage (not Cloudinary)
        // Use background job to avoid blocking the request
        if ($request->hasFile('video_file') && !empty($validated['_temp_video_path']) && empty($validated['cloudinary_id'])) {
            // Dispatch to background job instead of running synchronously
            ConvertVideoToHLS::dispatch($lesson, $validated['_temp_video_path'])
                ->onQueue('default');
            
            Log::info('HLS conversion dispatched to background job', [
                'lesson_id' => $lesson->id,
                'temp_path' => $validated['_temp_video_path'],
            ]);
        }

        // Update course video count
        $course = $section->course;
        $course->increment('video_count');
        
        // Update course total duration
        $course->updateTotalDuration();

        // Redirect to admin course edit page instead of back() to avoid showing success on public course page
        return redirect()->route('admin.courses.edit', $course)->with('success', 'Đã thêm bài học mới!');
    }

    public function update(Request $request, Lesson $lesson)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'video_file' => 'nullable|file|mimes:mp4,avi,mov,wmv,flv,webm|max:1331200', // 1.3GB max (1331200 KB)
            'video_path' => 'nullable|string|max:500',
            'hls_path' => 'nullable|string|max:500',
            'video_url' => 'nullable|url|max:500',
            'attachment_file' => 'nullable|file|mimes:pdf,doc,docx,zip,rar|max:1331200', // 1.3GB max
            'attachment_path' => 'nullable|string|max:500',
            'duration' => 'nullable|integer|min:0',
            'position' => 'nullable|integer',
        ]);

        // Handle video file upload - Only Cloudinary or HLS (no direct video_path)
        if ($request->hasFile('video_file')) {
            // Delete old HLS files if exists
            if ($lesson->hls_path) {
                $hlsConverter = new HLSConverter();
                $hlsConverter->deleteHLSFiles(dirname($lesson->hls_path));
            }
            // Delete old Cloudinary video if exists
            if ($lesson->cloudinary_id) {
                $cloudinaryService = new CloudinaryService();
                if ($cloudinaryService->isConfigured()) {
                    $cloudinaryService->deleteVideo($lesson->cloudinary_id);
                }
            }
            
            $videoFile = $request->file('video_file');
            
            // ALWAYS try Cloudinary first (credentials are configured)
            $cloudinaryService = new CloudinaryService();
            $isCloudinaryConfigured = $cloudinaryService->isConfigured();
            
            Log::info('Video upload (update) - Checking Cloudinary', [
                'is_configured' => $isCloudinaryConfigured,
                'cloud_name' => config('services.cloudinary.cloud_name'),
                'file_name' => $videoFile->getClientOriginalName(),
            ]);
            
            if ($isCloudinaryConfigured) {
                try {
                    // Get course name for folder organization
                    $course = $lesson->section->course;
                    $courseName = $course->title ?? 'lessons';
                    
                    // Sanitize course name for Cloudinary folder
                    // Format: videos/{sanitized_course_name}
                    $folderName = $cloudinaryService->sanitizeFolderName($courseName);
                    $cloudinaryFolder = "videos/{$folderName}";
                    
                    Log::info('Attempting Cloudinary upload (update)', [
                        'file_path' => $videoFile->getRealPath(),
                        'course_name' => $courseName,
                        'cloudinary_folder' => $cloudinaryFolder,
                    ]);
                    
                    // Upload to Cloudinary with course-specific folder
                    $uploadResult = $cloudinaryService->uploadVideo($videoFile->getRealPath(), [
                        'folder' => $cloudinaryFolder,
                    ]);
                    
                    Log::info('Cloudinary upload result (update)', [
                        'success' => $uploadResult['success'] ?? false,
                        'error' => $uploadResult['error'] ?? null,
                    ]);
                    
                    if ($uploadResult['success']) {
                        // Store Cloudinary direct video URL (not HLS) for immediate playback
                        // Use secure_url instead of hls_url to avoid waiting for HLS generation
                        $validated['video_url'] = $uploadResult['secure_url'];
                        $validated['cloudinary_id'] = $uploadResult['public_id'];
                        // Cloudinary returns duration in SECONDS, store as seconds
                        $validated['duration'] = (int) ($uploadResult['duration'] ?? 0);
                        $validated['video_path'] = null; // No local storage
                        $validated['hls_path'] = null; // No local HLS
                        
                        Log::info('Video uploaded to Cloudinary successfully (update, using direct URL)', [
                            'public_id' => $uploadResult['public_id'],
                            'secure_url' => $uploadResult['secure_url'],
                            'hls_url' => $uploadResult['hls_url'] ?? null,
                            'duration' => $uploadResult['duration'],
                            'duration_in_seconds' => $validated['duration'],
                            'file' => $videoFile->getClientOriginalName(),
                        ]);
                    } else {
                        // Fallback to local HLS conversion if Cloudinary fails
                        Log::warning('Cloudinary upload failed, falling back to local HLS conversion (update)', [
                            'error' => $uploadResult['error'] ?? 'Unknown error',
                        ]);
                        $this->handleLocalHLSConversion($request, $validated);
                    }
                } catch (\Exception $e) {
                    Log::error('Cloudinary upload exception, falling back to local HLS conversion (update)', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                    $this->handleLocalHLSConversion($request, $validated);
                }
            } else {
                // Cloudinary not configured, use local HLS conversion
                Log::warning('Cloudinary not configured, using local HLS conversion (update)');
                $this->handleLocalHLSConversion($request, $validated);
            }
        }

        // Handle attachment file upload
        if ($request->hasFile('attachment_file')) {
            // Delete old attachment if exists
            if ($lesson->attachment_path) {
                Storage::disk('public')->delete($lesson->attachment_path);
            }
            $attachmentPath = $request->file('attachment_file')->store('attachments/lessons', 'public');
            $validated['attachment_path'] = $attachmentPath;
        }

        // Remove file inputs from validated array
        unset($validated['video_file'], $validated['attachment_file']);

        // Auto-calculate duration from video file if available and not manually set
        // ALWAYS prefer YouTube API if YouTube URL is provided (even if user entered duration manually)
        // This ensures we store SECONDS, not minutes
        if (!empty($validated['video_url'])) {
            $youtubeService = new YouTubeService();
            if ($youtubeService->isYouTubeUrl($validated['video_url'])) {
                $youtubeDuration = $youtubeService->getVideoDuration($validated['video_url']);
                if ($youtubeDuration['seconds'] > 0) {
                    // YouTube API returns duration in ISO 8601 format, parsed to SECONDS
                    // Store duration in SECONDS (not minutes) for precision
                    // Override any manually entered duration to ensure consistency
                    $validated['duration'] = (int) $youtubeDuration['seconds'];
                    Log::info('Duration calculated from YouTube API (update, overriding form input if any)', [
                        'url' => $validated['video_url'],
                        'seconds' => $youtubeDuration['seconds'],  // This is what we store
                        'minutes' => $youtubeDuration['duration'],  // For display only
                        'formatted' => $youtubeDuration['formatted'],
                        'stored_as' => 'seconds',
                        'note' => 'Duration stored in database as SECONDS, not minutes. Form input ignored for YouTube URLs.'
                    ]);
                }
            }
        }
        
        // If no YouTube URL or YouTube API failed, keep existing duration or use form input
        // Note: Manual input should be in SECONDS (form label says "giây")
        // Note: No longer calculating duration from video_path as we don't store direct video files

        // Set default duration to 0 if still not set
        if (!isset($validated['duration']) || $validated['duration'] == null) {
            $validated['duration'] = $lesson->duration ?? 0; // Keep existing if updating
        }

        // Ensure position is set (only if truly empty/null, preserve user input)
        if (!isset($validated['position']) || $validated['position'] === null || $validated['position'] === '') {
            $validated['position'] = $lesson->position ?? 0; // Keep existing if updating
        } else {
            // Ensure position is an integer (preserve user input)
            $validated['position'] = (int) $validated['position'];
        }

        $validated['is_preview'] = $request->has('is_preview') ? 1 : 0;

        $lesson->update($validated);

        // Convert HLS only if using local storage (not Cloudinary)
        // Use background job to avoid blocking the request
        if ($request->hasFile('video_file') && !empty($validated['_temp_video_path']) && empty($validated['cloudinary_id'])) {
            // Dispatch to background job instead of running synchronously
            ConvertVideoToHLS::dispatch($lesson, $validated['_temp_video_path'])
                ->onQueue('default');
            
            Log::info('HLS conversion dispatched to background job (update)', [
                'lesson_id' => $lesson->id,
                'temp_path' => $validated['_temp_video_path'],
            ]);
        }

        // Redirect to admin course edit page instead of back() to avoid showing success on public course page
        $course = $lesson->section->course;
        
        // Update course total duration
        $course->updateTotalDuration();
        
        return redirect()->route('admin.courses.edit', $course)->with('success', 'Đã cập nhật bài học!');
    }

    public function destroy(Lesson $lesson)
    {
        // Delete files if exist
        // Delete HLS files
        if ($lesson->hls_path) {
            $hlsConverter = new HLSConverter();
            $hlsConverter->deleteHLSFiles(dirname($lesson->hls_path));
        }
        // Delete Cloudinary video if exists
        if ($lesson->cloudinary_id) {
            $cloudinaryService = new CloudinaryService();
            if ($cloudinaryService->isConfigured()) {
                $cloudinaryService->deleteVideo($lesson->cloudinary_id);
            }
        }
        // Delete attachment
        if ($lesson->attachment_path) {
            Storage::disk('public')->delete($lesson->attachment_path);
        }

        $course = $lesson->section->course;
        $lesson->delete();

        // Update course video count
        $course->decrement('video_count');
        
        // Update course total duration
        $course->updateTotalDuration();

        // Redirect to admin course edit page instead of back() to avoid showing success on public course page
        return redirect()->route('admin.courses.edit', $course)->with('success', 'Đã xóa bài học!');
    }

    /**
     * Handle local HLS conversion (fallback when Cloudinary is not available)
     * Uploads video temporarily, converts to HLS, then deletes original
     */
    private function handleLocalHLSConversion($request, &$validated)
    {
        if (!$request->hasFile('video_file')) {
            return;
        }

        $videoFile = $request->file('video_file');
        // Store temporarily for HLS conversion
        $tempVideoPath = $videoFile->store('videos/lessons', 'public');
        $storedPath = Storage::disk('public')->path($tempVideoPath);
        
        if ($storedPath && file_exists($storedPath)) {
            // Calculate duration
            $calculatedDuration = $this->getVideoDurationFromPath($storedPath);
            if ($calculatedDuration > 0) {
                $validated['duration'] = $calculatedDuration;
                Log::info('Duration calculated from uploaded file (local HLS)', [
                    'duration' => $calculatedDuration,
                    'file' => $videoFile->getClientOriginalName(),
                ]);
            }
            
            // Convert to HLS (will be done after lesson is created)
            // Store temp path for conversion
            $validated['_temp_video_path'] = $tempVideoPath;
        } else {
            Log::warning('Stored video file not found for HLS conversion', [
                'path' => $storedPath,
            ]);
        }
    }

    /**
     * Get video duration from uploaded file
     * Returns duration in minutes (integer)
     */
    private function getVideoDuration($file)
    {
        try {
            $path = $file->getRealPath();
            return $this->getVideoDurationFromPath($path);
        } catch (\Exception $e) {
            Log::warning('Cannot get video duration: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get video duration from file path
     * Tries multiple methods: ffprobe, getID3, or fallback to 0
     */
    private function getVideoDurationFromPath($filePath)
    {
        if (!file_exists($filePath)) {
            Log::warning('Video file not found', ['path' => $filePath]);
            return 0;
        }

        // Method 1: Try ffprobe (if available)
        if (function_exists('shell_exec') && !ini_get('safe_mode')) {
            $ffprobePath = $this->findFFprobe();
            if ($ffprobePath) {
                try {
                    // Use absolute path for Windows compatibility
                    $absolutePath = realpath($filePath);
                    if (!$absolutePath) {
                        $absolutePath = $filePath;
                    }
                    
                    $command = escapeshellarg($ffprobePath) . ' -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 ' . escapeshellarg($absolutePath) . ' 2>&1';
                    $output = @shell_exec($command);
                    
                    if ($output && is_numeric(trim($output))) {
                        $seconds = (float) trim($output);
                        // Return seconds (as integer) for precision
                        Log::info('Duration calculated via ffprobe', ['seconds' => $seconds]);
                        return (int) round($seconds);
                    } else {
                        Log::debug('ffprobe output invalid', ['output' => $output]);
                    }
                } catch (\Exception $e) {
                    Log::warning('ffprobe execution error: ' . $e->getMessage());
                }
            } else {
                Log::debug('ffprobe not found');
            }
        }

        // Method 2: Try getID3 library (if installed via composer)
        if (class_exists('\getID3')) {
            try {
                $getID3 = new \getID3;
                $fileInfo = $getID3->analyze($filePath);
                if (isset($fileInfo['playtime_seconds']) && $fileInfo['playtime_seconds'] > 0) {
                    $seconds = (float) $fileInfo['playtime_seconds'];
                    // Return seconds (as integer) for precision
                    Log::info('Duration calculated via getID3', ['seconds' => $seconds]);
                    return (int) round($seconds);
                }
            } catch (\Exception $e) {
                Log::warning('getID3 error: ' . $e->getMessage());
            }
        }

        // Method 3: Try PHP getID3 alternative (simple file reading for MP4)
        $duration = $this->getVideoDurationSimple($filePath);
        if ($duration > 0) {
            return $duration;
        }

        // Fallback: Return 0 (user can manually set duration)
        Log::info('Could not calculate video duration, returning 0', ['path' => $filePath]);
        return 0;
    }

    /**
     * Simple method to get video duration (for MP4 files)
     * This is a fallback when ffprobe/getID3 are not available
     */
    private function getVideoDurationSimple($filePath)
    {
        // Only works for MP4 files
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        if ($extension !== 'mp4') {
            return 0;
        }

        try {
            $file = fopen($filePath, 'rb');
            if (!$file) {
                return 0;
            }

            // Read file to find duration (simplified method)
            // This is a basic implementation and may not work for all MP4 files
            fseek($file, -8, SEEK_END);
            $data = fread($file, 8);
            fclose($file);

            // This is a very basic check - for production, use ffprobe or getID3
            return 0; // Return 0 to indicate we couldn't calculate
        } catch (\Exception $e) {
            Log::debug('Simple duration calculation failed: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Find ffprobe executable path
     */
    private function findFFprobe()
    {
        $possiblePaths = [
            'ffprobe',
            '/usr/bin/ffprobe',
            '/usr/local/bin/ffprobe',
            'C:\\ffmpeg\\bin\\ffprobe.exe',
            'C:\\Program Files\\ffmpeg\\bin\\ffprobe.exe',
            'C:\\xampp\\ffmpeg\\bin\\ffprobe.exe',
            'C:\\laragon\\bin\\ffmpeg\\ffprobe.exe',
        ];

        foreach ($possiblePaths as $path) {
            if (PHP_OS_FAMILY === 'Windows') {
                // Windows: check if file exists
                if (file_exists($path)) {
                    return $path;
                }
            } else {
                // Linux/Mac: check if executable
                if (is_executable($path)) {
                    return $path;
                }
            }
        }

        // Try to find in PATH
        if (function_exists('shell_exec') && !ini_get('safe_mode')) {
            $which = PHP_OS_FAMILY === 'Windows' ? 'where' : 'which';
            $result = @shell_exec($which . ' ffprobe 2>&1');
            
            if ($result && !empty(trim($result))) {
                $result = trim($result);
                // Check if result is actually a path (not an error message)
                if (strpos($result, 'INFO:') === false && 
                    strpos($result, 'Could not find') === false &&
                    strpos($result, 'not found') === false &&
                    (file_exists($result) || strpos($result, '\\') !== false || strpos($result, '/') !== false)) {
                    return $result;
                }
            }
        }

        return null;
    }

    /**
     * API endpoint to get YouTube video duration
     * Called from frontend JavaScript
     */
    public function getYouTubeDuration(Request $request)
    {
        $request->validate([
            'video_url' => 'required|url',
        ]);

        $youtubeService = new YouTubeService();
        $result = $youtubeService->getVideoDuration($request->video_url);

        return response()->json($result);
    }
}

