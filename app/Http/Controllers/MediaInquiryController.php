<?php

namespace App\Http\Controllers;

use App\Http\Requests\MediaInquiryRequest;
use App\Models\Blog;
use App\Models\MediaInquiry;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MediaInquiryController extends Controller
{
    public function index()
    {
        $min = 1;
        $max = 15;
        $randomNumber1 = mt_rand($min, $max);
        $randomNumber2 = mt_rand($min, $max);

        $latestPosts = Blog::orderBy('id', 'desc')->limit(5)->get();

        return view('frontend.media_inquiries', [
            'title' => 'Media inquiries',
            'description' => '',
            'keywords' => '',
            'random_number1' => $randomNumber1,
            'random_number2' => $randomNumber2,
            'random_number1_show' => $randomNumber1,
            'random_number2_show' => $randomNumber2,
            'media' => old('media', ''),
            'contact' => old('contact', ''),
            'email' => old('email', ''),
            'phone' => old('phone', ''),
            'story_concept' => old('story_concept', ''),
            'press_deadline' => old('press_deadline', ''),
            'story_details' => old('story_details', ''),
            'best_contact' => old('best_contact', ''),
            'latestPosts' => $latestPosts,
            'error' => session('errors') ? session('errors')->getBag('default')->getMessages() : [],
        ]);
    }

    public function validate(MediaInquiryRequest $request)
    {
        $post = $request->validated();

        $mediaInquiry = MediaInquiry::create([
            'media' => htmlspecialchars($post['media'], ENT_QUOTES, 'UTF-8'),
            'contact' => $post['contact'],
            'email' => htmlspecialchars($post['email'], ENT_QUOTES, 'UTF-8'),
            'phone' => $post['phone'],
            'story_concept' => $post['story_concept'],
            'press_deadline' => $post['press_deadline'],
            'story_details' => $post['story_details'],
            'best_contact' => $post['best_contact'],
            'media_status' => 0,
        ]);

        $this->sendNotification($mediaInquiry);

        return redirect()->route('media-inquiries');
    }

    private function sendNotification(MediaInquiry $mediaInquiry): void
    {
        $to = Setting::get('email');

        try {
            Mail::send('mails.media-inquiry', [
                'mediaId' => $mediaInquiry->id,
            ], function ($mail) use ($to, $mediaInquiry) {
                $mail->to($to)->subject('Media inquiries')->from($mediaInquiry->email);
            });
        } catch (\Exception $e) {
            Log::error('Failed to send media inquiry email: '.$e->getMessage());
        }
    }
}
