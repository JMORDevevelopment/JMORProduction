<?php

namespace App\Filament\Admin\Pages;

use Filament\Forms\Components\Radio;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Gate;

class AuthSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shield-check';

    protected static string|\UnitEnum|null $navigationGroup = 'System';

    protected static ?string $navigationLabel = 'Auth Settings';

    protected static ?string $title = 'Authentication Settings';

    protected static ?int $navigationSort = 10;

    protected string $view = 'filament.pages.auth-settings';

    public array $data = [];

    /**
     * Authentication settings are super-admin only (M10): hidden from
     * navigation for every other role.
     */
    public static function canAccess(): bool
    {
        return auth()->guard('admin')->user()?->isAdmin() ?? false;
    }

    public function mount(): void
    {
        Gate::authorize('authSettings');

        $admin = auth()->guard('admin')->user();

        $this->form->fill([
            'auth_mode' => $admin->auth_mode ?? 'both',
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Radio::make('auth_mode')
                    ->label('Authentication Method')
                    ->options([
                        'password' => 'Password Only',
                        '2fa' => 'Two Factor Authentication Only',
                        'both' => 'Password + Two Factor Authentication',
                    ])
                    ->default('both')
                    ->required(),
            ]);
    }

    public function save(): void
    {
        Gate::authorize('authSettings');

        $data = $this->form->getState();

        $admin = auth()->guard('admin')->user();
        $admin->update(['auth_mode' => $data['auth_mode']]);

        Notification::make()
            ->title('Authentication settings updated')
            ->success()
            ->send();
    }
}
