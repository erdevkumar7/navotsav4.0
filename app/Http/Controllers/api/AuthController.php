<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Jobs\VerifyUserEmailJob;
use App\Models\User;
use App\Models\UserDevice;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name'     => $request->name,
            'email'    => strtolower($request->email),
            'password' => $request->password,
            'user_type' => $request->user_type,
        ]);

        $token = $user->createToken('api')->plainTextToken;

        VerifyUserEmailJob::dispatch($user);

        return response()->json([
            'status' => true,
            'message' => 'User registered successfully',
            'user'    => $user,
            'token'   => $token
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $cred = $request->validated();

        $user = User::when(isset($cred['email']), fn($q) => $q->where('email', $cred['email']))
            ->when(isset($cred['phone']), fn($q) => $q->where('phone', $cred['phone']))
            ->first();

        if ($user?->user_type != $request->user_type) {
            return response()->json(['message' => 'Unauthorized to access'], 401);
        }


        if (!$user || (!Hash::check($cred['password'], $user->password))) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        if ($request->device_token && $request->platform) {
            UserDevice::updateOrCreate(['user_id' => $user->id, 'platform' => $request->platform], [
                'device_token' => $request->device_token
            ]);
        }

        $token = $user->createToken('api')->plainTextToken;

        return response()->json([
            'message' => 'Logged in.',
            'user' => $user,
            'token' => $token,
            'status' => true
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out.']);
    }

    public function resendOtp(Request $request)
    {
        $request->validate([
            "email" => "required|email|exists:users",
            "purpose" => "required"
        ]);
        $otp = OtpService::send('email', $request->email, $request->purpose);
        return response()->json([
            'status' => true,
            'message' => 'Otp has been sent successfully'
        ]);
    }

    public function verifyOtp(Request $request)
    {

        $request->validate([
            "email" => "required|email|exists:users",
            "otp" => "required|digits:4"
        ]);

        if (OtpService::verify($request->otp)) {
            $user = User::where('email', $request->email)->first();
            if (!$user->email_verified_at) {
                $user->email_verified_at = Carbon::now();
                $user->save();
            }
            return response()->json([
                "status" => true,
                "message" => "Otp verified"
            ]);
        }

        return response()->json([
            "status" => false,
            "message" => "Otp does not match"
        ]);
    }
}
