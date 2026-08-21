<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestInformationRequest;
use App\Models\Blog;
use App\Models\RequestInformation;
use App\Models\Setting;
use App\Models\Slider;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RequestInformationController extends Controller
{
    public function index()
    {
        $min = 1;
        $max = 15;
        $randomNumber1 = mt_rand($min, $max);
        $randomNumber2 = mt_rand($min, $max);

        $services = Slider::pluck('slider_name')->toArray();
        $latestPosts = Blog::orderBy('id', 'desc')->limit(5)->get();

        return view('frontend.request_information', [
            'title' => 'Request Information',
            'description' => '',
            'keywords' => '',
            'random_number1' => $randomNumber1,
            'random_number2' => $randomNumber2,
            'random_number1_show' => $randomNumber1,
            'random_number2_show' => $randomNumber2,
            'services' => $services,
            'first_name' => old('first_name', ''),
            'last_name' => old('last_name', ''),
            'company' => old('company', ''),
            'email' => old('email', ''),
            'phone' => old('phone', ''),
            'fax' => old('fax', ''),
            'address' => old('address', ''),
            'suite' => old('suite', ''),
            'city' => old('city', ''),
            'state' => old('state', ''),
            'zip' => old('zip', ''),
            'service_intersted' => old('service_intersted', ''),
            'message' => old('message', ''),
            'latestPosts' => $latestPosts,
            'error' => session('errors') ? collect(session('errors')->getBag('default')->getMessages())->map(fn ($msgs) => $msgs[0])->toArray() : [],
        ]);
    }

    public function validate(RequestInformationRequest $request)
    {
        $post = $request->validated();

        $requestInfo = RequestInformation::create([
            'first_name' => htmlspecialchars($post['first_name'], ENT_QUOTES, 'UTF-8'),
            'last_name' => htmlspecialchars($post['last_name'], ENT_QUOTES, 'UTF-8'),
            'company' => $post['company'],
            'email' => htmlspecialchars($post['email'], ENT_QUOTES, 'UTF-8'),
            'phone' => $post['phone'],
            'fax' => $post['fax'] ?? null,
            'address' => $post['address'] ?? null,
            'suite' => $post['suite'] ?? null,
            'city' => $post['city'] ?? null,
            'state' => $post['state'] ?? null,
            'zip' => $post['zip'] ?? null,
            'service_intersted' => $post['service_intersted'],
            'message' => $post['message'],
            'protection_question' => $post['protection_question'],
            'status' => 0,
            'ip' => $request->ip(),
        ]);

        $this->sendNotification($requestInfo);

        return redirect()->route('request-information');
    }

    private function sendNotification(RequestInformation $requestInfo): void
    {
        $to = Setting::get('email');

        try {
            Mail::send('mails.request-information', [
                'requestId' => $requestInfo->id,
                'name' => $requestInfo->first_name.' '.$requestInfo->last_name,
                'email' => $requestInfo->email,
            ], function ($mail) use ($to, $requestInfo) {
                $mail->to($to)->subject('Request information')->from($requestInfo->email);
            });
        } catch (\Exception $e) {
            Log::error('Failed to send request information email: '.$e->getMessage());
        }
    }
}
