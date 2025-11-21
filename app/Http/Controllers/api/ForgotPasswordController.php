<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;

use const Adminer\DB;

class ForgotPasswordController extends Controller
{
    public function forgotPassword(Request $request)
    {

        $request->validate([
            "email" => "required|email|exists:users"
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        $otp = OtpService::send('email', $request->email, 'Forgot password',$user->name);

        return response()->json([
            'status' => true,
            'message' => "Otp has been sent"
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            "email" => "required|email|exists:users",
            'password' => 'required|confirmed|min:5',
        ]);

        $user = User::where('email', $request->email)->first();

        $user->update(['password' => $request->password]);

        return response()->json([
            "status" => true,
            "message" => "password has been reset successfully!"
        ]);
    }
}
