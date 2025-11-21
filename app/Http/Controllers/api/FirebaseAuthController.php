<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Firebase\Auth\Token\Exception\InvalidToken;
use Kreait\Firebase\Auth as FirebaseAuth;
use Illuminate\Http\Request;

class FirebaseAuthController extends Controller
{
    protected $firebaseAuth;

    public function __construct()
    {
        $this->firebaseAuth =  app('firebase.auth');
    }

    public function login(Request $request)
    {
        try {
            // Verify with Firebase
            $verifiedIdToken = $this->firebaseAuth->verifyIdToken($request->token);
            $uid = $verifiedIdToken->claims()->get('sub');

            // Get Firebase user info
            $firebaseUser = $this->firebaseAuth->getUser($uid);

            // Find or create local user
            $user = User::firstOrCreate(
                ['email' => $firebaseUser->email],
                ['user_type' => BUYER, 'name' => $firebaseUser->displayName ?? 'Unknown', 'social_provider' => $request->provider ?? null]
            );

            if ($request->has('device_token')) {
                $user->device()->updateOrCreate([
                    'platform' => 'web',
                ], [
                    "device_token" => $request->device_token
                ]);
            }

            // Issue Laravel token (Sanctum example)
            $token = $user->createToken('api_token')->plainTextToken;

            $user = User::find($user->id);
            return response()->json([
                "message" => "Login Success",
                'user' => $user,
                'token' => $token
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unauthorized', 'message' => $e->getMessage()], 401);
        }
    }
}
