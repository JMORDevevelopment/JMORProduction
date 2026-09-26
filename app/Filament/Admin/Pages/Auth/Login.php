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
use Illuminate\Http\Exceptions\HttpResponseException;
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

    public function mount(): void
    {
        if (Filament::auth()->check()) {
            throw new HttpResponseException(redirect()->intended(Filament::getUrl()));
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

        // Use the admin's configured auth mode. This is resolved server-side
        // only — the form never reveals which mode an account uses.
        $authMode = $user->auth_mode ?? 'both';

        if (! in_array($authMode, ['password', '2fa', 'both'])) {
            $authMode = 'both';
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

        if (blank($password) || ! $user->validatePassword($password)) {
            event(new Failed('admin', $user, ['email' => $data['email']]));

            $this->throwFailureValidationException();
        }

        $credentials = ['email' => $data['email'], 'password' => $password];

        if (! Filament::auth()->attemptWhen($credentials, function (Authenticatable $user): bool {
            return $user->canAccessPanel(Filament::getCurrentOrDefaultPanel());
        }, $remember)) {
            $this->throwFailureValidationException();
        }

        $this->completeLogin();

        return app(LoginResponse::class);
    }

    protected function authenticateWith2faOnly(Authenticatable $user, array $data): ?LoginResponse
    {
        if (! $user->hasEnabledTwoFactorAuthentication()) {
            $this->throwFailureValidationException();
        }

        $codeType = $data['code_type'] ?? 'otp';

        if ($codeType === 'recovery') {
            $valid = $this->validateRecoveryCodeSilently($user, $data);
        } else {
            $valid = $this->validateOtpCodeSilently($user, $data);
        }

        // Uniform failure so the response never discloses whether the account
        // exists or which authentication mode it uses.
        if (! $valid) {
            event(new Failed('admin', $user, ['email' => $data['email']]));

            $this->throwFailureValidationException();
        }

        // Check panel access
        if (! $user->canAccessPanel(Filament::getCurrentOrDefaultPanel())) {
            $this->throwFailureValidationException();
        }

        // The 2FA challenge has been satisfied on this request.
        $user->setTwoFactorChallengePassed();

        // Log in directly since OTP/recovery code already validated
        Filament::auth()->login($user, false);
        $this->completeLogin();

        return app(LoginResponse::class);
    }

    protected function authenticateWithBoth(Authenticatable $user, array $data): ?LoginResponse
    {
        $password = $data['password'] ?? '';
        $remember = $data['remember'] ?? false;

        // Step 1: Validate password. Failures are reported with the same
        // generic message as every other credential failure.
        if (blank($password) || ! $user->validatePassword($password)) {
            event(new Failed('admin', $user, ['email' => $data['email']]));

            $this->throwFailureValidationException();
        }

        // Step 2: Validate 2FA if enabled. The password was correct at this
        // point, so field-specific feedback no longer leaks account state.
        if ($user->hasEnabledTwoFactorAuthentication()) {
            $codeType = $data['code_type'] ?? 'otp';

            if ($codeType === 'recovery') {
                if (! $this->validateRecoveryCodeSilently($user, $data)) {
                    event(new Failed('admin', $user, ['email' => $data['email']]));

                    $this->throwValidationExceptionWithMessage('data.recovery_code', 'The provided recovery code is invalid.');
                }

                $user->setTwoFactorChallengePassed();
            } else {
                if (! $this->validateOtpCodeSilently($user, $data)) {
                    event(new Failed('admin', $user, ['email' => $data['email']]));

                    $this->throwValidationExceptionWithMessage('data.otp_code', 'The provided authenticator code is invalid.');
                }

                $user->setTwoFactorChallengePassed();
            }
        }

        // Step 3: Log in
        $credentials = ['email' => $data['email'], 'password' => $password];

        if (! Filament::auth()->attemptWhen($credentials, function (Authenticatable $user): bool {
            return $user->canAccessPanel(Filament::getCurrentOrDefaultPanel());
        }, $remember)) {
            $this->throwFailureValidationException();
        }

        $this->completeLogin();

        return app(LoginResponse::class);
    }

    /**
     * Final steps shared by every successful authentication path:
     * regenerate the session and clear the failed-attempt counter.
     */
    protected function completeLogin(): void
    {
        session()->regenerate();
        $this->clearRateLimiter('authenticate');
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

    protected function validateOtpCodeSilently(Authenticatable $user, array $data): bool
    {
        $otpCode = $data['otp_code'] ?? '';

        if (blank($otpCode) || blank($user->two_factor_secret)) {
            return false;
        }

        $secret = decrypt($user->two_factor_secret);
        $google2fa = app(TwoFactorAuthenticationProvider::class);

        return $google2fa->verify($secret, $otpCode);
    }

    protected function validateRecoveryCodeSilently(Authenticatable $user, array $data): bool
    {
        $recoveryCode = $data['recovery_code'] ?? '';

        if (blank($recoveryCode)) {
            return false;
        }

        $validCode = collect($user->recoveryCodes())->first(
            fn ($code) => hash_equals($code, $recoveryCode) ? $code : null
        );

        if (! $validCode) {
            return false;
        }

        // Replace the used recovery code with a new one
        $recoveryCodes = $user->recoveryCodes();
        $newCode = strtoupper(bin2hex(random_bytes(4)));
        $recoveryCodes = array_map(fn ($code) => $code === $validCode ? $newCode : $code, $recoveryCodes);
        $user->forceFill([
            'two_factor_recovery_codes' => encrypt(json_encode($recoveryCodes)),
        ])->save();

        return true;
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
                    ->autofocus(),

                Placeholder::make('info')
                    ->hiddenLabel()
                    ->content('Enter your credentials to sign in.')
                    ->visible(fn (): bool => filled($this->data['email'] ?? null)),

                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->revealable(filament()->arePasswordsRevealable())
                    ->autocomplete('current-password')
                    ->maxLength(255),

                Radio::make('code_type')
                    ->label('Verification')
                    ->options([
                        'otp' => 'Authenticator Code',
                        'recovery' => 'Recovery Code',
                    ])
                    ->default('otp')
                    ->live()
                    ->inline(),

                TextInput::make('otp_code')
                    ->label('Authenticator Code')
                    ->length(6)
                    ->maxLength(6)
                    ->autocomplete('one-time-code')
                    ->visible(fn (Get $get): bool => ($get('code_type') ?? 'otp') !== 'recovery'),

                TextInput::make('recovery_code')
                    ->label('Recovery Code')
                    ->maxLength(255)
                    ->autocomplete('one-time-code')
                    ->visible(fn (Get $get): bool => ($get('code_type') ?? 'otp') === 'recovery'),

                Checkbox::make('remember')
                    ->label('Remember me'),
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
                    ->label('Sign in')
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
