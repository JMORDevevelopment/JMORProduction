<?php

namespace App\Http\Controllers;

use App\Http\Requests\TalkShowGuestRequest;
use App\Models\MarketingService;
use App\Models\TalkShowSetting;
use App\Services\PaymentService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class TalkShowGuestController extends Controller
{
    public function __construct(private PaymentService $payments) {}

    /**
     * Original: Home::talk_show_checkout() — order/summary page for the
     * JMOR Tech Talk Show Guest Application.
     */
    public function checkout(): View
    {
        $settings = TalkShowSetting::query()->first();

        return view('frontend.guest_checkout', [
            'title' => 'JMOR Tech Talk Show Guest Application',
            'description' => '',
            'keywords' => '',
            'price' => $settings?->price ?? '',
            'question' => $settings?->question ?? '',
            'services' => MarketingService::all(),
            'name' => old('name', ''),
            'email' => old('email', ''),
            'number' => old('number', ''),
            'expiry' => old('expiry', ''),
            'cvc' => old('cvc', ''),
            'error' => session('errors')
                ? collect(session('errors')->getBag('default')->getMessages())
                    ->map(fn ($msgs) => $msgs[0])->toArray()
                : [],
            'paymentFailed' => session('payment_failed'),
            'added' => session('added'),
        ]);
    }

    /**
     * Original: Home::guestchargecard() — the original handler was broken
     * (undefined variables, no persistence, dead redirect); this performs
     * the intended Authorize.Net charge at the displayed price.
     */
    public function charge(TalkShowGuestRequest $request): RedirectResponse
    {
        $settings = TalkShowSetting::query()->first();
        $amount = (float) ($settings?->price ?? 0);

        $charged = $this->payments->chargeGuestApplication($amount, $request->validated());

        if (! $charged) {
            return redirect()
                ->route('talk-show.checkout')
                ->with('payment_failed', 'Payment failed. Please check your card details.');
        }

        return redirect()
            ->route('talk-show.checkout')
            ->with('added', 'Thank you! Your payment was successful.');
    }
}
