<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $verificationToken = Str::random(60);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'verification_token' => $verificationToken,
            'email_verified_at' => null,
        ]);

        // Gửi email xác thực
        Mail::send('emails.verify', ['token' => $verificationToken], function($message) use ($user) {
            $message->to($user->email);
            $message->subject('Xác thực tài khoản');
        });

        return redirect()->route('login')->with('success', 'Vui lòng kiểm tra email để xác thực tài khoản.');
    }

    public function verify($token)
    {
        $user = User::where('verification_token', $token)->first();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Link xác thực không hợp lệ.');
        }

        $user->email_verified_at = now();
        $user->verification_token = null;
        $user->save();

        return redirect()->route('login')->with('success', 'Tài khoản đã được xác thực thành công.');
    }
}
