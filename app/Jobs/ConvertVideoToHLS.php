<?php

namespace App\Jobs;

use App\Models\Lesson;
use App\Services\HLSConverter;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ConvertVideoToHLS implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     */
    public $timeout = 3600; // 1 hour

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Lesson $lesson,
        public string $videoPath
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Log::info('Starting HLS conversion job', [
                'lesson_id' => $this->lesson->id,
                'video_path' => $this->videoPath
            ]);

            $hlsConverter = new HLSConverter();
            $hlsResult = $hlsConverter->convertToHLS($this->videoPath, null, [
                'quality' => 'medium', // Can be 'low', 'medium', 'high'
                'segment_time' => 10,
                'preset' => 'veryfast', // Fast encoding: ultrafast, superfast, veryfast, faster, fast, medium, slow, slower, veryslow
            ]);

            if ($hlsResult['success']) {
                // Update lesson with HLS path
                $this->lesson->update([
                    'hls_path' => $hlsResult['playlist_path']
                ]);

                Log::info('HLS conversion completed successfully', [
                    'lesson_id' => $this->lesson->id,
                    'hls_path' => $hlsResult['playlist_path']
                ]);

                // Delete original video file to save storage
                $hlsConverter->deleteOriginalVideo($this->videoPath);
                
                // Clear video_path since we deleted it
                $this->lesson->update([
                    'video_path' => null
                ]);

                Log::info('Original video file deleted after HLS conversion', [
                    'lesson_id' => $this->lesson->id,
                    'video_path' => $this->videoPath
                ]);
            } else {
                Log::warning('HLS conversion failed in job', [
                    'lesson_id' => $this->lesson->id,
                    'video_path' => $this->videoPath,
                    'error' => $hlsResult['message']
                ]);
            }
        } catch (\Exception $e) {
            Log::error('HLS conversion job exception', [
                'lesson_id' => $this->lesson->id,
                'video_path' => $this->videoPath,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw $e; // Re-throw to trigger retry mechanism
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('HLS conversion job failed permanently', [
            'lesson_id' => $this->lesson->id,
            'video_path' => $this->videoPath,
            'error' => $exception->getMessage()
        ]);
    }
}
