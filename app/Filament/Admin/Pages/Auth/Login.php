<?php

namespace App\Filament\Admin\Pages\Auth;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Actions\Action;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Facades\Filament;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\SimplePage;
use Filament\Schemas\Components\Actions as SchemaActions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Illuminate\Auth\Events\Failed;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Locked;
use Stephenjude\FilamentTwoFactorAuthentication\TwoFactorAuthenticationProvider;

class Login extends SimplePage
{
    use WithRateLimiting;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    #[Locked]
    public ?string $userUndertakingMultiFactorAuthentication = null;

    public ?string $resolvedAuthMode = null;

    public function mount(): void
    {
        if (Filament::auth()->check()) {
            redirect()->intended(Filament::getUrl());
        }

        $this->form->fill();
    }

    public function authenticate(): ?LoginResponse
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            Notification::make()
                ->title('Too many login attempts')
                ->body('Please try again in '.$exception->secondsUntilAvailable.' seconds.')
                ->danger()
                ->send();

            return null;
        }

        $data = $this->form->getState();
        $email = $data['email'];

        if (blank($email)) {
            $this->throwValidationExceptionWithMessage('data.email', 'Please enter your email address.');
        }

        // Retrieve admin by email
        $adminProvider = Filament::auth()->getProvider();
        $user = $adminProvider->retrieveByCredentials(['email' => $email]);

        if (! $user) {
            $this->throwFailureValidationException();
        }

        // Use the admin's configured auth mode
        $authMode = $user->auth_mode ?? 'both';
        $this->resolvedAuthMode = $authMode;

        // If this was just the Continue step (no auth mode resolved yet), re-render the form
        if (is_null($this->resolvedAuthMode) || ! in_array($authMode, ['password', '2fa', 'both'])) {
            return null;
        }

        // Mode: password only (no 2FA)
        if ($authMode === 'password') {
            return $this->authenticateWithPasswordOnly($user, $data);
        }

        // Mode: 2FA only (no password)
        if ($authMode === '2fa') {
            return $this->authenticateWith2faOnly($user, $data);
        }

        // Mode: both (password + 2FA)
        return $this->authenticateWithBoth($user, $data);
    }

    protected function authenticateWithPasswordOnly(Authenticatable $user, array $data): ?LoginResponse
    {
        $password = $data['password'] ?? '';
        $remember = $data['remember'] ?? false;

        if (blank($password)) {
            $this->throwValidationExceptionWithMessage('data.password', 'Please enter your password.');
        }

        if (! $user->validatePassword($password)) {
            event(new Failed('admin', $user, ['email' => $data['email'], 'password' => $password]));

            $this->throwFailureValidationException();
        }

        $credentials = ['email' => $data['email'], 'password' => $password];

        if (! Filament::auth()->attemptWhen($credentials, function (Authenticatable $user): bool {
            return $user->canAccessPanel(Filament::getCurrentOrDefaultPanel());
        }, $remember)) {
            $this->throwFailureValidationException();
        }

        session()->regenerate();

        return app(LoginResponse::class);
    }

    protected function authenticateWith2faOnly(Authenticatable $user, array $data): ?LoginResponse
    {
        if (! $user->hasEnabledTwoFactorAuthentication()) {
            $this->throwValidationExceptionWithMessage('data.otp_code', 'Two factor authentication is not enabled for this account.');
        }

        $codeType = $data['code_type'] ?? 'otp';

        if ($codeType === 'recovery') {
            $this->validateRecoveryCode($user, $data);
        } else {
            $this->validateOtpCode($user, $data);
        }

        // Check panel access
        if (! $user->canAccessPanel(Filament::getCurrentOrDefaultPanel())) {
            $this->throwFailureValidationException();
        }

        // Log in directly since OTP/recovery code already validated
        Filament::auth()->login($user, false);
        session()->regenerate();

        return app(LoginResponse::class);
    }

    protected function authenticateWithBoth(Authenticatable $user, array $data): ?LoginResponse
    {
        $password = $data['password'] ?? '';
        $remember = $data['remember'] ?? false;

        if (blank($password)) {
            $this->throwValidationExceptionWithMessage('data.password', 'Please enter your password.');
        }

        // Step 1: Validate password
        if (! $user->validatePassword($password)) {
            event(new Failed('admin', $user, ['email' => $data['email'], 'password' => $password]));

            $this->throwFailureValidationException();
        }

        // Step 2: Validate 2FA if enabled
        if ($user->hasEnabledTwoFactorAuthentication()) {
            $codeType = $data['code_type'] ?? 'otp';

            if ($codeType === 'recovery') {
                $this->validateRecoveryCode($user, $data);
            } else {
                $this->validateOtpCode($user, $data);
            }
        }

        // Step 3: Log in
        $credentials = ['email' => $data['email'], 'password' => $password];

        if (! Filament::auth()->attemptWhen($credentials, function (Authenticatable $user): bool {
            return $user->canAccessPanel(Filament::getCurrentOrDefaultPanel());
        }, $remember)) {
            $this->throwFailureValidationException();
        }

        session()->regenerate();

        return app(LoginResponse::class);
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.email' => __('filament-panels::auth/pages/login.messages.failed'),
        ]);
    }

    protected function throwValidationExceptionWithMessage(string $key, string $message): never
    {
        throw ValidationException::withMessages([
            $key => $message,
        ]);
    }

    protected function validateOtpCode(Authenticatable $user, array $data): void
    {
        $otpCode = $data['otp_code'] ?? '';

        if (blank($otpCode)) {
            $this->throwValidationExceptionWithMessage('data.otp_code', 'Please enter your authenticator code.');
        }

        $secret = decrypt($user->two_factor_secret);
        $google2fa = app(TwoFactorAuthenticationProvider::class);

        if (! $google2fa->verify($secret, $otpCode)) {
            event(new Failed('admin', $user, ['email' => $data['email']]));

            $this->throwValidationExceptionWithMessage('data.otp_code', 'The provided authenticator code is invalid.');
        }
    }

    protected function validateRecoveryCode(Authenticatable $user, array $data): void
    {
        $recoveryCode = $data['recovery_code'] ?? '';

        if (blank($recoveryCode)) {
            $this->throwValidationExceptionWithMessage('data.recovery_code', 'Please enter your recovery code.');
        }

        $validCode = collect($user->recoveryCodes())->first(
            fn ($code) => hash_equals($code, $recoveryCode) ? $code : null
        );

        if (! $validCode) {
            event(new Failed('admin', $user, ['email' => $data['email']]));

            $this->throwValidationExceptionWithMessage('data.recovery_code', 'The provided recovery code is invalid.');
        }

        // Replace the used recovery code with a new one
        $recoveryCodes = $user->recoveryCodes();
        $newCode = strtoupper(bin2hex(random_bytes(4)));
        $recoveryCodes = array_map(fn ($code) => $code === $validCode ? $newCode : $code, $recoveryCodes);
        $user->forceFill([
            'two_factor_recovery_codes' => encrypt(json_encode($recoveryCodes)),
        ])->save();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->trim()
                    ->autocomplete()
                    ->autofocus()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function () {
                        $email = $this->data['email'] ?? null;

                        if (blank($email)) {
                            $this->resolvedAuthMode = null;

                            return;
                        }

                        $adminProvider = Filament::auth()->getProvider();
                        $user = $adminProvider->retrieveByCredentials(['email' => $email]);
                        $this->resolvedAuthMode = $user?->auth_mode ?? null;
                    }),

                Placeholder::make('info')
                    ->hiddenLabel()
                    ->content(fn (): string => match ($this->resolvedAuthMode) {
                        'password' => 'Enter your password to sign in.',
                        '2fa' => 'Enter your authenticator or recovery code to sign in.',
                        'both' => 'Enter your password and authenticator code to sign in.',
                        default => 'Enter your email above, then click Continue.',
                    })
                    ->visible(fn (): bool => filled($this->data['email'] ?? null)),

                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->revealable(filament()->arePasswordsRevealable())
                    ->autocomplete('current-password')
                    ->required()
                    ->maxLength(255)
                    ->visible(fn (): bool => in_array($this->resolvedAuthMode, ['password', 'both'])),

                Radio::make('code_type')
                    ->label('Verification')
                    ->options([
                        'otp' => 'Authenticator Code',
                        'recovery' => 'Recovery Code',
                    ])
                    ->default('otp')
                    ->live()
                    ->inline()
                    ->visible(fn (): bool => in_array($this->resolvedAuthMode, ['2fa', 'both'])),

                TextInput::make('otp_code')
                    ->label('Authenticator Code')
                    ->length(6)
                    ->required()
                    ->autocomplete('one-time-code')
                    ->visible(fn (Get $get): bool => in_array($this->resolvedAuthMode, ['2fa', 'both']) && ($get('code_type') ?? 'otp') !== 'recovery'),

                TextInput::make('recovery_code')
                    ->label('Recovery Code')
                    ->required()
                    ->maxLength(255)
                    ->autocomplete('one-time-code')
                    ->visible(fn (Get $get): bool => in_array($this->resolvedAuthMode, ['2fa', 'both']) && ($get('code_type') ?? 'otp') === 'recovery'),

                Checkbox::make('remember')
                    ->label('Remember me')
                    ->visible(fn (): bool => in_array($this->resolvedAuthMode, ['password', 'both'])),
            ]);
    }

    public function getTitle(): string|HtmlString
    {
        return 'JMOR Connection';
    }

    public function getHeading(): string|HtmlString|null
    {
        return 'Sign in to your account';
    }

    protected function getFormActions(): array
    {
        return [
            SchemaActions::make([
                Action::make('authenticate')
                    ->label($this->resolvedAuthMode ? 'Sign in' : 'Continue')
                    ->submit('authenticate'),
            ])->fullWidth(),
        ];
    }

    protected function hasFullWidthFormActions(): bool
    {
        return true;
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([EmbeddedSchema::make('form')])
                    ->id('form')
                    ->livewireSubmitHandler('authenticate')
                    ->footer([
                        SchemaActions::make($this->getFormActions())
                            ->alignment(Alignment::End)
                            ->fullWidth($this->hasFullWidthFormActions()),
                    ]),
            ]);
    }
}
