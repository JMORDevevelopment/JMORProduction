<?php

namespace App\Providers;

use App\Auth\MD5EloquentUserProvider;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Auth::provider('md5-eloquent', function ($app, array $config) {
            return new MD5EloquentUserProvider($app['hash'], $config['model']);
        });

        $this->configureFileUploads();
    }

    /**
     * Upload hardening (H6): cap file size at 5 MB and block SVG uploads
     * (which can carry embedded scripts) via an additional server-side
     * MIME whitelist that survives component-level ->image() settings.
     */
    private function configureFileUploads(): void
    {
        $allowedImageMimes = implode(',', [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
            'image/avif',
            'image/bmp',
            'image/tiff',
            'image/x-icon',
            'image/vnd.microsoft.icon',
        ]);

        FileUpload::configureUsing(function (FileUpload $upload) use ($allowedImageMimes): void {
            $upload
                ->maxSize(5120)
                ->rule("mimetypes:{$allowedImageMimes}");
        });
    }
}
