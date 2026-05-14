<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GoogleAuthController extends Controller
{
    public function handleGoogle(Request $request)
    {
        try {
            $idToken = $request->id_token;
            $response = Http::get("https://oauth2.googleapis.com/tokeninfo?id_token={$idToken}");
            if (!$response->successful()) {
                return response()->json(['success' => false, 'message' => 'Token tidak valid'], 401);
            }
            $googleUser = $response->json();
            $user = User::firstOrCreate(
                ['email' => $googleUser['email']],
                [
                    'name' => $googleUser['name'],
                    'password' => bcrypt(str()->random(16)),
                    'role' => 'customer',
                ]
            );
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json(['success' => true, 'message' => 'Login berhasil', 'data' => ['user' => $user, 'token' => $token]]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}