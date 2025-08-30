<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->validate([
            'email_or_mobile'=>'required|string',
            'password'=>'required|string'
        ]);

        $user = User::where('email', $data['email_or_mobile'])
                    ->orWhere('mobile', $data['email_or_mobile'])
                    ->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json(['message'=>'Invalid credentials'], 401);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json(['token'=>$token, 'user'=>$user]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message'=>'Logged out']);
    }
}
