<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use App\Models\ContactUs;
use App\Models\Setting;
use Illuminate\Http\Request;
=======
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)
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

    public function submit(Request $request)
    {
        $post = $request->all();
        $error = $this->validatePost($post);

        $rand1 = $request->input('firstNumber');
        $rand2 = $request->input('secondNumber');
        $total = $request->input('protection_question');
        if ($rand1 + $rand2 != $total) {
            $error['error_protection_question'] = 'Your answer is wrong!';
        }

        if (! empty($error)) {
            return redirect()->back()->withInput()->withErrors($error);
        }

<<<<<<< HEAD
        ContactUs::create([
=======
        DB::table('contact_us')->insert([
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)
            'name' => htmlspecialchars($post['name']),
            'email' => htmlspecialchars($post['email']),
            'phone' => htmlspecialchars($post['phone']),
            'reason' => $post['reason'],
            'message' => $post['message'],
            'ip' => $request->ip(),
            'date_time' => date('m/d/y h:i:s a'),
        ]);

        $this->sendNotification($post);

        return redirect('/contact?form=submit');
    }

    private function validatePost(array $post): array
    {
        $error = [];

        if (empty($post['name'])) {
            $error['error_name'] = 'Enter Name';
        }
        if (empty($post['email'])) {
            $error['error_email'] = 'Enter Email';
        }
        if (empty($post['phone'])) {
            $error['error_phone'] = 'Enter Phone';
        }
        if (empty($post['reason'])) {
            $error['error_reason'] = 'Enter Reason';
        }
        if (empty($post['message'])) {
            $error['error_message'] = 'Enter Message';
        }

        return $error;
    }

    private function sendNotification(array $post): void
    {
<<<<<<< HEAD
        $to = Setting::get('email');
=======
        $to = DB::table('settings')->where('option', 'email')->value('value');
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)

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
<<<<<<< HEAD
            Log::error('Failed to send contact us email: ' . $e->getMessage());
            
=======
            // Match legacy behaviour: a failed email should not block the redirect
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)
        }
    }
}
