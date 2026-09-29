<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\AdminPasswordReset;
use App\Models\Admin;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AdminForgotPasswordController extends Controller
{
    public function showForm()
    {
        return view('admin.auth.forgot_password');
    }

    /**
     * The response is identical whether or not the email exists, so the
     * form cannot be used to enumerate admin accounts.
     */
    public function send(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $admin = Admin::where('email', $data['email'])->first();

        if ($admin) {
            $token = Str::random(40);

            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $admin->email],
                ['token' => hash('sha256', $token), 'created_at' => now()]
            );

            Mail::to($admin->email)->send(new AdminPasswordReset(
                trim($admin->firstname.' '.$admin->lastname),
                route('admin.reset-password', $token),
            ));
        }

        return redirect()->route('admin.forgot-password')->with('status', 'sent');
    }

    public function showResetForm(string $token)
    {
        if (! $this->validTokenRow($token)) {
            return redirect()
                ->route('admin.forgot-password')
                ->withErrors(['email' => 'This password reset link is invalid or has expired.']);
        }

        return view('admin.auth.reset_password', ['token' => $token]);
    }

    public function reset(Request $request)
    {
        $data = $request->validate([
            'token' => ['required'],
            'password' => ['required', 'confirmed', 'min:8', 'max:255'],
        ]);

        $row = $this->validTokenRow($data['token']);

        if (! $row) {
            return redirect()
                ->route('admin.forgot-password')
                ->withErrors(['email' => 'This password reset link is invalid or has expired.']);
        }

        $admin = Admin::where('email', $row->email)->first();

        if (! $admin) {
            return redirect()
                ->route('admin.forgot-password')
                ->withErrors(['email' => 'This password reset link is invalid or has expired.']);
        }

        $admin->forceFill(['password' => bcrypt($data['password'])])->save();

        DB::table('password_reset_tokens')->where('email', $row->email)->delete();

        Notification::make()
            ->title('Password updated')
            ->body('You can now sign in with your new password.')
            ->success()
            ->send();

        return redirect()->route('filament.admin.auth.login');
    }

    private function validTokenRow(string $token): ?object
    {
        $row = DB::table('password_reset_tokens')
            ->where('token', hash('sha256', $token))
            ->first();

        if (! $row || ! $row->created_at) {
            return null;
        }

        if (Carbon::parse($row->created_at)->addMinutes(60)->isPast()) {
            return null;
        }

        return $row;
    }
}
