<?php

namespace App\Http\Controllers;

use App\Mail\NewsletterConfirmation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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

        // Duplicates: treat an already-signed-up address as success, not an error.
        $existing = $this->findSubscriber($validated['EMAIL']);
        if ($existing === null) {
            $this->storeSubscriber($validated);
            Log::info('Newsletter subscriber stored', ['email' => $validated['EMAIL']]);
        }

        try {
            Mail::to($validated['EMAIL'])->send(new NewsletterConfirmation(
                firstName: $validated['FNAME'],
                lastName: $validated['LNAME'],
                email: $validated['EMAIL'],
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

    private function findSubscriber(string $email): ?array
    {
        $path = storage_path('app/newsletter_subscribers.json');
        if (! is_file($path)) {
            return null;
        }

        $rows = json_decode((string) file_get_contents($path), true) ?: [];

        foreach ($rows as $row) {
            if (strcasecmp((string) ($row['email'] ?? ''), $email) === 0) {
                return $row;
            }
        }

        return null;
    }

    private function storeSubscriber(array $data): void
    {
        $path = storage_path('app/newsletter_subscribers.json');
        $rows = is_file($path)
            ? (json_decode((string) file_get_contents($path), true) ?: [])
            : [];

        $rows[] = [
            'first_name' => $data['FNAME'],
            'last_name' => $data['LNAME'],
            'email' => $data['EMAIL'],
            'consent' => $data['NEWSLETTER_CONSENT'],
            'status' => 'pending',
            'subscribed_at' => now()->toIso8601String(),
        ];

        if (! is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        file_put_contents($path, json_encode($rows, JSON_PRETTY_PRINT));
    }
}
