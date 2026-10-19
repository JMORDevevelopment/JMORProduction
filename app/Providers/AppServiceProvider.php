<?php

namespace App\Providers;

use App\Auth\MD5EloquentUserProvider;
use App\Models\Admin;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
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

        $this->registerAdminAuthorization();
        $this->configureFileUploads();
    }

    /**
     * Central authorization for the admin panel:
     *
     * - Super admins (role = 1) are allowed every ability.
     * - Every other admin is limited to the permissions granted to their
     *   role(s) via Shield (deny by default).
     * - Users of other guards are unaffected.
     */
    private function registerAdminAuthorization(): void
    {
        Gate::before(function ($user, string $ability): ?bool {
            if (! $user instanceof Admin) {
                return null;
            }

            if ($user->isAdmin()) {
                return true;
            }

            return null;
        });

        Action::configureUsing(function (Action $action): void {
            if ($action instanceof DeleteAction) {
                $action->authorize('delete');
            }

            if ($action instanceof DeleteBulkAction) {
                $action->authorize('deleteAny');
            }
        });
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
