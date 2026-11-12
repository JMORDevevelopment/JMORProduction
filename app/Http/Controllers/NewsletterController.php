<?php

namespace App\Http\Controllers;

use App\Mail\NewsletterConfirmation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class NewsletterController extends Controller
{
    /**
     * Accept a newsletter signup. In production the form action can be
     * pointed at Mailchimp via NEWSLETTER_FORM_ACTION; locally we handle
     * it here so the flow is testable end to end.
     */
    public function subscribe(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'FNAME' => ['required', 'string', 'max:100'],
            'LNAME' => ['required', 'string', 'max:100'],
            'EMAIL' => ['required', 'email', 'max:255'],
            'NEWSLETTER_CONSENT' => ['required'],
        ], [
            'NEWSLETTER_CONSENT.required' => 'Please agree to receive the JMOR newsletter.',
        ]);

        $existing = $this->findSubscriber($validated['EMAIL']);
        $isNew = $existing === null;

        if ($isNew) {
            $existing = $this->storeSubscriber($validated);
            Log::info('Newsletter subscriber stored', ['email' => $validated['EMAIL']]);
        }

        // Already confirmed: no need to re-prompt.
        if (($existing['status'] ?? '') === 'confirmed') {
            return redirect()
                ->back()
                ->with('newsletter_success', 'You are already subscribed to the JMOR newsletter.');
        }

        $confirmUrl = route('newsletter.confirm', ['token' => $existing['token']]);

        try {
            Mail::to($validated['EMAIL'])->send(new NewsletterConfirmation(
                firstName: $validated['FNAME'],
                lastName: $validated['LNAME'],
                email: $validated['EMAIL'],
                confirmUrl: $confirmUrl,
            ));
            Log::info('Newsletter confirmation sent', ['email' => $validated['EMAIL']]);
        } catch (\Throwable $e) {
            Log::error('Newsletter confirmation failed', [
                'email' => $validated['EMAIL'],
                'error' => $e->getMessage(),
            ]);
        }

        return redirect()
            ->back()
            ->with('newsletter_success', 'Thanks for subscribing! Please check your email to confirm.');
    }

    /**
     * Double opt-in: mark the subscriber confirmed when they click the
     * link in the confirmation email.
     */
    public function confirm(string $token): View|RedirectResponse
    {
        $subscriber = $this->findByToken($token);

        if ($subscriber === null) {
            return view('frontend.newsletter-confirm', [
                'state' => 'invalid',
                'title' => 'Invalid confirmation link',
            ]);
        }

        if (($subscriber['status'] ?? '') === 'confirmed') {
            return view('frontend.newsletter-confirm', [
                'state' => 'already',
                'title' => 'Already confirmed',
                'firstName' => $subscriber['first_name'] ?? '',
            ]);
        }

        $this->updateSubscriber($subscriber['email'], [
            'status' => 'confirmed',
            'confirmed_at' => now()->toIso8601String(),
        ]);
        Log::info('Newsletter subscription confirmed', ['email' => $subscriber['email']]);

        return view('frontend.newsletter-confirm', [
            'state' => 'confirmed',
            'title' => 'Subscription confirmed',
            'firstName' => $subscriber['first_name'] ?? '',
        ]);
    }

    private function subscribersPath(): string
    {
        return storage_path('app/newsletter_subscribers.json');
    }

    private function readSubscribers(): array
    {
        $path = $this->subscribersPath();
        if (! is_file($path)) {
            return [];
        }

        return json_decode((string) file_get_contents($path), true) ?: [];
    }

    private function writeSubscribers(array $rows): void
    {
        $path = $this->subscribersPath();
        if (! is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        file_put_contents($path, json_encode($rows, JSON_PRETTY_PRINT));
    }

    private function findSubscriber(string $email): ?array
    {
        foreach ($this->readSubscribers() as $row) {
            if (strcasecmp((string) ($row['email'] ?? ''), $email) === 0) {
                return $row;
            }
        }

        return null;
    }

    private function findByToken(string $token): ?array
    {
        if ($token === '') {
            return null;
        }

        foreach ($this->readSubscribers() as $row) {
            if (hash_equals((string) ($row['token'] ?? ''), $token)) {
                return $row;
            }
        }

        return null;
    }

    /**
     * @return array<string, mixed>
     */
    private function storeSubscriber(array $data): array
    {
        $rows = $this->readSubscribers();

        $row = [
            'first_name' => $data['FNAME'],
            'last_name' => $data['LNAME'],
            'email' => $data['EMAIL'],
            'consent' => $data['NEWSLETTER_CONSENT'],
            'status' => 'pending',
            'token' => Str::random(40),
            'subscribed_at' => now()->toIso8601String(),
        ];

        $rows[] = $row;
        $this->writeSubscribers($rows);

        return $row;
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function updateSubscriber(string $email, array $attributes): void
    {
        $rows = $this->readSubscribers();

        foreach ($rows as $i => $row) {
            if (strcasecmp((string) ($row['email'] ?? ''), $email) === 0) {
                $rows[$i] = array_merge($row, $attributes);
                break;
            }
        }

        $this->writeSubscribers($rows);
    }
}
