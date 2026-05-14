<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    #[OA\Post(path: "/api/register", tags: ["Auth"], summary: "Register user baru",
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(
            required: ["name","email","password","password_confirmation","role"],
            properties: [
                new OA\Property(property: "name", type: "string"),
                new OA\Property(property: "email", type: "string", format: "email"),
                new OA\Property(property: "password", type: "string", format: "password"),
                new OA\Property(property: "password_confirmation", type: "string"),
                new OA\Property(property: "role", type: "string", enum: ["customer","bengkel","mekanik"]),
                new OA\Property(property: "phone", type: "string"),
                new OA\Property(property: "address", type: "string"),
            ]
        )),
        responses: [new OA\Response(response: 201, description: "Registrasi berhasil")]
    )]
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "name" => "required|string|max:255",
                "email" => "required|string|email|max:255|unique:users",
                "password" => "required|string|min:8|confirmed",
                "role" => "required|in:customer,bengkel,mekanik",
                "phone" => "nullable|string",
                "address" => "nullable|string",
            ]);
            if ($validator->fails()) {
                return response()->json(["success" => false, "message" => "Validasi gagal", "errors" => $validator->errors()], 422);
            }
            $user = User::create([
                "name" => $request->name,
                "email" => $request->email,
                "password" => Hash::make($request->password),
                "role" => $request->role,
                "phone" => $request->phone,
                "address" => $request->address,
            ]);
            $token = $user->createToken("api-token")->plainTextToken;
            return response()->json(["success" => true, "message" => "Registrasi berhasil", "data" => ["user" => $user, "token" => $token]], 201);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => "Error: " . $e->getMessage()], 500);
        }
    }

    #[OA\Post(path: "/api/login", tags: ["Auth"], summary: "Login user",
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(
            required: ["email","password"],
            properties: [
                new OA\Property(property: "email", type: "string", format: "email"),
                new OA\Property(property: "password", type: "string", format: "password"),
            ]
        )),
        responses: [new OA\Response(response: 200, description: "Login berhasil")]
    )]
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "email" => "required|string|email",
                "password" => "required|string"
            ]);
            if ($validator->fails()) {
                return response()->json(["success" => false, "message" => "Validasi gagal", "errors" => $validator->errors()], 422);
            }
            $user = User::where("email", $request->email)->first();
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json(["success" => false, "message" => "Email atau password salah"], 401);
            }
            $token = $user->createToken("api-token")->plainTextToken;
            return response()->json(["success" => true, "message" => "Login berhasil", "data" => ["user" => $user, "token" => $token]], 200);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => "Error: " . $e->getMessage()], 500);
        }
    }

    #[OA\Post(path: "/api/logout", tags: ["Auth"], summary: "Logout user",
        security: [["token" => []]],
        responses: [new OA\Response(response: 200, description: "Logout berhasil")]
    )]
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json(["success" => true, "message" => "Logout berhasil"], 200);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => "Error: " . $e->getMessage()], 500);
        }
    }

    #[OA\Get(path: "/api/profile", tags: ["Auth"], summary: "Get user profile",
        security: [["token" => []]],
        responses: [new OA\Response(response: 200, description: "Data user berhasil diambil")]
    )]
    public function profile(Request $request)
    {
        try {
            return response()->json(["success" => true, "message" => "Data user berhasil diambil", "data" => ["user" => $request->user()]], 200);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => "Error: " . $e->getMessage()], 500);
        }
    }
}