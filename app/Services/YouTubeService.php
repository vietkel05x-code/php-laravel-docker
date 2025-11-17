<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class YouTubeService
{
    private $apiKey;
    private $baseUrl = 'https://www.googleapis.com/youtube/v3';

    public function __construct()
    {
        $this->apiKey = config('services.youtube.api_key');
    }

    /**
     * Extract YouTube video ID from various URL formats
     * 
     * @param string $url YouTube URL
     * @return string|null Video ID or null if invalid
     */
    public function extractVideoId($url)
    {
        if (empty($url)) {
            return null;
        }

        // Patterns for different YouTube URL formats
        $patterns = [
            // Standard: https://www.youtube.com/watch?v=VIDEO_ID
            '/youtube\.com\/watch\?v=([a-zA-Z0-9_-]{11})/',
            // Short: https://youtu.be/VIDEO_ID
            '/youtu\.be\/([a-zA-Z0-9_-]{11})/',
            // Embed: https://www.youtube.com/embed/VIDEO_ID
            '/youtube\.com\/embed\/([a-zA-Z0-9_-]{11})/',
            // Mobile: https://m.youtube.com/watch?v=VIDEO_ID
            '/m\.youtube\.com\/watch\?v=([a-zA-Z0-9_-]{11})/',
            // With additional parameters: https://www.youtube.com/watch?v=VIDEO_ID&feature=share
            '/youtube\.com\/watch\?.*v=([a-zA-Z0-9_-]{11})/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }

        // If URL is just the video ID itself (11 characters)
        if (preg_match('/^[a-zA-Z0-9_-]{11}$/', trim($url))) {
            return trim($url);
        }

        return null;
    }

    /**
     * Get video duration from YouTube Data API v3
     * 
     * @param string $videoUrl YouTube video URL
     * @return array ['duration' => int (minutes), 'seconds' => int, 'formatted' => string]
     */
    public function getVideoDuration($videoUrl)
    {
        if (empty($this->apiKey)) {
            Log::warning('YouTube API key not configured');
            return [
                'duration' => 0,
                'seconds' => 0,
                'formatted' => '0:00',
                'error' => 'YouTube API key not configured'
            ];
        }

        $videoId = $this->extractVideoId($videoUrl);
        
        if (!$videoId) {
            Log::warning('Invalid YouTube URL', ['url' => $videoUrl]);
            return [
                'duration' => 0,
                'seconds' => 0,
                'formatted' => '0:00',
                'error' => 'Invalid YouTube URL'
            ];
        }

        // Check cache first (cache for 24 hours)
        $cacheKey = "youtube_duration_{$videoId}";
        $cached = Cache::get($cacheKey);
        if ($cached !== null) {
            Log::info('YouTube duration retrieved from cache', ['video_id' => $videoId]);
            return $cached;
        }

        try {
            $response = Http::timeout(10)->get("{$this->baseUrl}/videos", [
                'id' => $videoId,
                'part' => 'contentDetails',
                'key' => $this->apiKey,
            ]);

            if (!$response->successful()) {
                $error = $response->json();
                Log::error('YouTube API error', [
                    'status' => $response->status(),
                    'error' => $error,
                    'video_id' => $videoId
                ]);
                
                return [
                    'duration' => 0,
                    'seconds' => 0,
                    'formatted' => '0:00',
                    'error' => 'YouTube API error: ' . ($error['error']['message'] ?? 'Unknown error')
                ];
            }

            $data = $response->json();

            if (empty($data['items'])) {
                Log::warning('YouTube video not found', ['video_id' => $videoId]);
                return [
                    'duration' => 0,
                    'seconds' => 0,
                    'formatted' => '0:00',
                    'error' => 'Video not found'
                ];
            }

            $duration = $data['items'][0]['contentDetails']['duration'] ?? null;
            
            if (!$duration) {
                Log::warning('Duration not found in YouTube response', ['video_id' => $videoId]);
                return [
                    'duration' => 0,
                    'seconds' => 0,
                    'formatted' => '0:00',
                    'error' => 'Duration not found'
                ];
            }

            // Parse ISO 8601 duration (e.g., PT1H2M10S)
            // YouTube API returns duration in ISO 8601 format (PT1H2M10S = 1 hour 2 minutes 10 seconds)
            // parseISO8601Duration() converts it to SECONDS
            $seconds = $this->parseISO8601Duration($duration);
            $minutes = max(1, (int) ceil($seconds / 60)); // Convert to minutes, minimum 1 (for display only)
            $formatted = $this->formatDuration($seconds);

            $result = [
                'duration' => $minutes,  // Minutes (for display/backward compatibility)
                'seconds' => $seconds,    // SECONDS - this is what we store in database
                'formatted' => $formatted,
                'video_id' => $videoId,
            ];

            // Cache the result for 24 hours
            Cache::put($cacheKey, $result, now()->addHours(24));

            Log::info('YouTube duration retrieved successfully', [
                'video_id' => $videoId,
                'iso8601_duration' => $duration,  // Raw ISO 8601 format from YouTube API
                'seconds' => $seconds,            // Parsed to seconds
                'minutes' => $minutes,            // Converted to minutes (for display)
                'note' => 'Duration stored in database as SECONDS, not minutes'
            ]);

            return $result;

        } catch (\Exception $e) {
            Log::error('YouTube API exception', [
                'message' => $e->getMessage(),
                'video_id' => $videoId,
                'url' => $videoUrl
            ]);

            return [
                'duration' => 0,
                'seconds' => 0,
                'formatted' => '0:00',
                'error' => 'Exception: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Parse ISO 8601 duration format (PT1H2M10S) to seconds
     * 
     * @param string $duration ISO 8601 duration string
     * @return int Duration in seconds
     */
    private function parseISO8601Duration($duration)
    {
        // Remove PT prefix
        $duration = str_replace('PT', '', $duration);
        
        $seconds = 0;
        
        // Match hours
        if (preg_match('/(\d+)H/', $duration, $matches)) {
            $seconds += (int) $matches[1] * 3600;
        }
        
        // Match minutes
        if (preg_match('/(\d+)M/', $duration, $matches)) {
            $seconds += (int) $matches[1] * 60;
        }
        
        // Match seconds
        if (preg_match('/(\d+)S/', $duration, $matches)) {
            $seconds += (int) $matches[1];
        }
        
        return $seconds;
    }

    /**
     * Format seconds to human-readable format (H:MM:SS or M:SS)
     * 
     * @param int $seconds
     * @return string Formatted duration
     */
    private function formatDuration($seconds)
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = $seconds % 60;

        if ($hours > 0) {
            return sprintf('%d:%02d:%02d', $hours, $minutes, $secs);
        } else {
            return sprintf('%d:%02d', $minutes, $secs);
        }
    }

    /**
     * Check if URL is a YouTube URL
     * 
     * @param string $url
     * @return bool
     */
    public function isYouTubeUrl($url)
    {
        if (empty($url)) {
            return false;
        }

        $patterns = [
            '/youtube\.com/',
            '/youtu\.be/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url)) {
                return true;
            }
        }

        return false;
    }
}

