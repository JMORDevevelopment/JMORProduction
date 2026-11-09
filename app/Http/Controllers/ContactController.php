<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Http\Requests\TalkShowInterviewRequest;
use App\Models\ContactUs;
use App\Models\Setting;
use App\Models\TalkShow;
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

        // Seed the challenge server-side; the posted firstNumber/secondNumber
        // fields are ignored during validation (M12).
        session(['captcha_numbers' => [$randomNumber1, $randomNumber2]]);

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
            'assessment_scope' => old('assessment_scope', ''),
            'business_name' => old('business_name', ''),
            'business_type' => old('business_type', ''),
            'address' => Setting::get('address', ''),
            'phone_number' => Setting::get('phone', ''),
            'success' => session('contact_success'),
            'error' => session('errors') ? collect(session('errors')->getBag('default')->getMessages())->map(fn ($msgs) => $msgs[0])->toArray() : [],
            // Re-open the talk show interview form when it was the form that
            // failed validation (the original relied on query-string alerts).
            'open_interview' => old('last_name') !== null || old('bio') !== null || old('interview') !== null,
        ]);
    }

    public function submit(ContactRequest $request)
    {
        $post = $request->validated();

        $resumePath = null;
        if ($request->hasFile('resume')) {
            // Stored on the private local disk so resumes are never
            // publicly URL-addressable (issue 055).
            $resumePath = $request->file('resume')->store('resumes', 'local');
        }

        ContactUs::create([
            'name' => htmlspecialchars($post['name']),
            'email' => htmlspecialchars($post['email']),
            'phone' => htmlspecialchars($post['phone']),
            'reason' => $post['reason'],
            'message' => $post['message'],
            'assessment_scope' => $post['assessment_scope'] ?? null,
            'business_name' => isset($post['business_name']) ? htmlspecialchars($post['business_name']) : null,
            'business_type' => isset($post['business_type']) ? htmlspecialchars($post['business_type']) : null,
            'resume_path' => $resumePath,
            'ip' => $request->ip(),
            'date_time' => date('m/d/y h:i:s a'),
        ]);

        $this->sendNotification($post);

        return redirect()->route('contact')->with('contact_success', 'Message is sent we will contact you soon.');
    }

    public function interviewSubmit(TalkShowInterviewRequest $request)
    {
        $post = $request->validated();

        TalkShow::create([
            'name' => htmlspecialchars($post['name'], ENT_QUOTES, 'UTF-8'),
            'last_name' => htmlspecialchars($post['last_name'], ENT_QUOTES, 'UTF-8'),
            'company_name' => htmlspecialchars($post['company_name'] ?? '', ENT_QUOTES, 'UTF-8'),
            'phone' => htmlspecialchars($post['phone'], ENT_QUOTES, 'UTF-8'),
            'email' => htmlspecialchars($post['email'], ENT_QUOTES, 'UTF-8'),
            'bio' => htmlspecialchars($post['bio'], ENT_QUOTES, 'UTF-8'),
            'work_name' => implode(',', $post['work_name']),
            'work_detail' => implode(',', $post['work_detail']),
            'weblink' => implode(',', $post['weblink']),
            'interview' => htmlspecialchars($post['interview'], ENT_QUOTES, 'UTF-8'),
            'ip' => $request->ip(),
            'date_time' => date('m/d/y h:i:s a'),
        ]);

        $this->sendInterviewNotification($post);

        return redirect()->route('contact')->with('contact_success', 'Message is sent we will contact you soon.');
    }

    public function loadWork(Request $request)
    {
        $html = view('frontend.ajaxv', [
            'id' => (int) $request->post('id'),
            'work_name' => $request->post('work_name'),
            'work_detail' => $request->post('work_detail'),
            'work_web' => $request->post('work_web'),
        ])->render();

        return response()->json(['html' => $html]);
    }

    private function sendInterviewNotification(array $post): void
    {
        $to = Setting::get('email');

        try {
            Mail::send('mails.talk-show', [
                'name' => $post['name'],
                'email' => $post['email'],
                'phone' => $post['phone'],
                'bio' => $post['bio'],
                'interview' => $post['interview'],
            ], function ($mail) use ($to, $post) {
                $mail->to($to)
                    ->subject('Talk Show')
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->replyTo($post['email']);
            });
        } catch (\Exception $e) {
            Log::error('Failed to send talk show interview email: '.$e->getMessage());
        }
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
                $mail->to($to)
                    ->subject('Contact Us')
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->replyTo($post['email']);
            });
        } catch (\Exception $e) {
            Log::error('Failed to send contact us email: '.$e->getMessage());
        }
    }
}
