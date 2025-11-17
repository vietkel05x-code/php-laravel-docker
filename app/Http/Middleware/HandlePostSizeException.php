<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Symfony\Component\HttpFoundation\Response;

class HandlePostSizeException
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            return $next($request);
        } catch (PostTooLargeException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'File quá lớn',
                    'message' => 'Kích thước file vượt quá giới hạn cho phép. Vui lòng kiểm tra cấu hình PHP (upload_max_filesize và post_max_size) hoặc giảm kích thước file.',
                    'current_limit' => [
                        'upload_max_filesize' => ini_get('upload_max_filesize'),
                        'post_max_size' => ini_get('post_max_size'),
                    ],
                    'recommended' => 'Cần cấu hình PHP: upload_max_filesize = 500M, post_max_size = 500M'
                ], 413);
            }

            return back()->withErrors([
                'file' => 'File quá lớn. Kích thước file vượt quá giới hạn cho phép (' . ini_get('upload_max_filesize') . '). Vui lòng xem file UPLOAD_CONFIG.md để cấu hình PHP.'
            ])->withInput();
        }
    }
}

