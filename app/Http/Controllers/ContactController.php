<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\ContactUs;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function show(Request $request)
    {
        $min = 1;
        $max = 15;
        $randomNumber1 = mt_rand($min, $max);
        $randomNumber2 = mt_rand($min, $max);

        return view('frontend.contact_us', [
            'title' => 'Contact Us',
            'description' => '',
            'keywords' => '',
            'random_number1' => $randomNumber1,
            'random_number2' => $randomNumber2,
            'random_number1_show' => $randomNumber1,
            'random_number2_show' => $randomNumber2,
            'name' => old('name', ''),
            'email' => old('email', ''),
            'phone' => old('phone', ''),
            'reason' => old('reason', ''),
            'message' => old('message', ''),
            'error' => session('errors') ? session('errors')->getBag('default')->getMessages() : [],
        ]);
    }

    public function submit(ContactRequest $request)
    {
        $post = $request->validated();

        ContactUs::create([
            'name' => htmlspecialchars($post['name']),
            'email' => htmlspecialchars($post['email']),
            'phone' => htmlspecialchars($post['phone']),
            'reason' => $post['reason'],
            'message' => $post['message'],
            'ip' => $request->ip(),
            'date_time' => date('m/d/y h:i:s a'),
        ]);

        $this->sendNotification($post);

        return redirect()->route('contact', ['form' => 'submit']);
    }

    private function sendNotification(array $post): void
    {
        $to = Setting::get('email');

        try {
            Mail::send('mails.contact-us', [
                'name' => $post['name'],
                'email' => $post['email'],
                'phone' => $post['phone'],
                'reason' => $post['reason'],
                'message' => $post['message'],
            ], function ($mail) use ($to, $post) {
                $mail->to($to)->subject('Contact Us')->from($post['email']);
            });
        } catch (\Exception $e) {
            Log::error('Failed to send contact us email: '.$e->getMessage());
        }
    }
}
