<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        return view('auth.login');
    }

    public function signup()
    {
        return view('auth.signup');
    }

    public function signupPost(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email',
            'password'          => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => strtolower($request->email),
            'password' => $request->password,
            'user_type' => EVENT_ORGANIZER,
        ]);

        $user->assignRole('event-organizer');

        Auth::login($user);

        return response()->json([
            'status' => true,
            'message' => "Registration successfully!",
            'redirect' => route(routePrefix() . 'dashboard')
        ]);
    }

    public function loginPost(Request $request)
    {
        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);
        // Detect guard from prefix
        $prefix = request()->segment(1); // admin OR vendor
        $guard =  'web';

        // Try to login with selected guard
        if (Auth::guard($guard)->attempt([
            "email" => $request->email,
            "password" => $request->password
        ])) {
            $user = Auth::guard($guard)->user();

            // Ensure future Auth calls use this guard
            Auth::shouldUse($guard);

            // Block suspended users immediately
            if ($user->status != 'active') {
                Auth::guard($guard)->logout();
                return response()->json([
                    'status' => false,
                    'message' => 'Your account is suspended. Please contact the administrator.'
                ], 401);
            }


            if ($prefix === 'admin' && $user->user_type != 1) {
                Auth::guard($guard)->logout();
                return response()->json([
                    'status' => false,
                    'message' => 'You are not allowed to login here as admin.'
                ], 401);
            }

            if ($prefix === 'vendor' && $user->user_type == 1) {
                Auth::guard($guard)->logout();
                return response()->json([
                    'status' => false,
                    'message' => 'Admins cannot login from vendor login.'
                ], 401);
            }

            // Vendor / Event Organizer / Admin login handling
            if ($user->user_type == EVENT_ORGANIZER) {
                if ($user->status != 'active') {
                    return response()->json([
                        'status' => false,
                        'message' => 'Your account is suspended. Please contact the administrator.'
                    ], 401);
                }
                return response()->json([
                    'status' => true,
                    'redirect' => route(routePrefix() . 'dashboard')
                ]);
            } elseif ($user->user_type == 1) { // Admin with MFA
                if ($user->is_mfa_enabled) {
                    Auth::guard($guard)->logout();
                    session(['mfa_user_id' => $user->id]);

                    $otp = OtpService::send('email', $user->email, 'login');

                    return response()->json([
                        'status' => true,
                        'redirect' => route(routePrefix() . 'mfa.verify')
                    ]);
                }
            } elseif ($user->user_type == 3) { // Vendor
                return response()->json([
                    'status' => true,
                    'redirect' => route(routePrefix() . 'dashboard')
                ]);
            }

            // Default redirect
            return response()->json([
                'status' => true,
                'redirect' => route(routePrefix() . 'dashboard')
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Invalid credentials'
        ], 401);
    }


    // Forgot password

    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }


    public function sendResetOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $otp = OtpService::send('email', $request->email, 'reset'); // 👈 use type "reset"

        // Save user_id in session for verification
        $user = User::where('email', $request->email)->first();
        session(['reset_user_id' => $user->id]);
        $email = $user->email;
        // return response()->json([
        //     'status' => true,
        //     'message' => 'OTP sent to your email address.'
        // ]);
        return view('auth.reset-password-otp', compact('email'));
    }


    public function resetOtpPage()
    {
        $user = User::find(session('reset_user_id'));
        if (!$user) {
            return redirect()->route(routePrefix() . 'forgot.password')
                ->withError('Session expired. Please try again.');
        }

        $email = $user->email;
        return view('auth.mfa-verify-reset', compact('email'));
    }


    public function verifyResetOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:4',
        ]);


        // verify OTP for type 'reset'
        $isVerify = OtpService::verify($request->otp);


        if ($isVerify) {

            return response()->json([
                'status'   => true, // boolean true
                'redirect' => route(routePrefix() . 'forgot.password.resetForm'),
            ]);
        }

        return response()->json(['status' => false, 'message' => 'Invalid OTP']);
    }


    // public function verifyResetOtp(Request $request)
    // {
    //     $request->validate([
    //         'otp' => 'required|digits:4',
    //     ]);

    //     $user = User::find(session('reset_user_id'));
    //     if (!$user) {
    //         return response()->json(['status' => 'error', 'message' => 'Session expired. Please try again.']);
    //     }

    //     $isVerify = OtpService::verify('email', $user->email, 'reset', $request->otp);

    //     if ($isVerify) {
    //         session(['reset_verified' => true]);
    //         return response()->json([
    //             'status' => 'success',
    //             'redirect' => route(routePrefix() . 'forgot.password.resetForm')
    //         ]);
    //     }

    //     return response()->json(['status' => 'error', 'message' => 'Invalid OTP']);
    // }

    public function resetPasswordForm()
    {
        if (!session('reset_verified')) {
            return redirect()->route(routePrefix() . 'forgot.password')->withError('Unauthorized access.');
        }

        return view('auth.reset-password'); // create a blade file
    }



    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::find(session('reset_user_id'));
        if (!$user || !session('reset_verified')) {
            return redirect()->route(routePrefix() . 'forgot.password')->withError('Unauthorized access.');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Clear session
        session()->forget(['reset_user_id', 'reset_verified']);

        return redirect()->route(routePrefix() . 'login')->with('success', 'Password reset successfully. Please login.');
    }












    public function resendOtp(Request $request)
    {
        $otp = OtpService::send('email', $request->email, 'login');
        return response()->json([
            'status' => true,
            'message' => 'Otp has been send successfully'
        ]);
    }

    public function mfaVerify()
    {
        $email = User::find(session('mfa_user_id'))->email;
        return view('auth.mfa-verify', compact('email'));
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:4',
        ]);

        $user = User::find(session('mfa_user_id'));

        if (!$user) {
            return redirect()->route(routePrefix() . 'login')->withError('Session expired. Please login again.');
        }

        $isVerify = OtpService::verify('email', $user->email, 'login', $request->otp);

        if ($isVerify) {
            Auth::login($user);
            return response()->json(['status' => 'success', 'redirect' => route(routePrefix() . 'dashboard')]);
        }

        return response()->json(['status' => 'error', 'message' => 'Invalid OTP']);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route(routePrefix() . 'login');
    }

    public function dashboard(Request $request)
    {
        return view('dashboard');
    }

    public function profile(Request $request)
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    public function profileUpdate(Request $request)
    {
        $data = $request->validate([
            "name"  => "nullable|string",
            "phone" => "nullable",
            "avatar" => "nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048",
        ]);

        $user = auth()->user();


        if ($request->hasFile('avatar')) {

            // Delete old avatar safely (relative path only)
            // if ($user->avatar_url && Storage::disk('public')->exists($user->avatar_url)) {
            //     Storage::disk('public')->delete($user->avatar_url);
            // }

            // Store new avatar
            $path = $request->file('avatar')->store('avatars', 'public');

            // Make sure ONLY relative path is stored in DB
            $data['avatar_url'] = $path;
        }

        $user->update($data);

        return redirect()->back()->withSuccess("Profile saved.");
    }


    public function profileChangePassword(Request $request)
    {
        // Validate the form input
        $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed', // will require a confirm_password field with same name
        ], [
            'new_password.confirmed' => 'The new password and confirm password must match.'
        ]);

        $user = Auth::user();

        // Check if old password matches
        if (!Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'Old password is incorrect']);
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password changed successfully.');
    }
}
