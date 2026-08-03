<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

use function Pest\Laravel\json;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
            'security_question' => ['required', 'string'],
            'security_answer' => ['required', 'string'],
        ]);
        
        $user = User::create([
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'security_question' => $validated['security_question'],
            'security_answer' => Hash::make($validated['security_answer']),
        ]);

        return response()->json([
            'message' => 'success',
            'data' => $user
        ]);
    }

    public function login(Request $request)
    {
        // validate request
        // check if it matches
        // create token
        // response
        $validated = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required']
        ]);

        $user = User::where('username', '=', $validated['username'])->first();
        if(!$user || !Hash::check($validated['password'], $user->password))
        {
            return response()->json(['message' => 'Invalid credentials'], 401); 
        }

        $apiToken = $user->createToken('api')->plainTextToken;

        return response()->json([
            'message' => 'success',
            'data' => $user,
            'auth_token' => $apiToken
        ], 200);
    }
}
