<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Http\Exceptions\PostTooLargeException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'File quá lớn',
                    'message' => 'Kích thước file vượt quá giới hạn cho phép. Vui lòng kiểm tra cấu hình PHP (upload_max_filesize và post_max_size) hoặc giảm kích thước file.',
                    'current_limit' => [
                        'upload_max_filesize' => ini_get('upload_max_filesize'),
                        'post_max_size' => ini_get('post_max_size'),
                    ],
                    'recommended' => 'Cần cấu hình PHP: upload_max_filesize = 1500M, post_max_size = 1500M (để hỗ trợ file lên đến 1.3GB)'
                ], 413);
            }

            return back()->withErrors([
                'file' => 'File quá lớn. Kích thước file vượt quá giới hạn cho phép (' . ini_get('upload_max_filesize') . '). Hệ thống hỗ trợ upload lên đến 1.3GB. Vui lòng xem file UPLOAD_CONFIG.md để cấu hình PHP.'
            ])->withInput();
        });
    })->create();
