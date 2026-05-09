<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => $request->role ?? 'user',
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Usuario registrado exitosamente',
                'token'   => $token,
                'user'    => $user,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al registrar usuario',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'message' => 'Credenciales incorrectas',
                ], 401);
            }

            $user  = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Login exitoso',
                'token'   => $token,
                'user'    => $user,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al iniciar sesión',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function logout(): JsonResponse
    {
        try {
            Auth::user()->currentAccessToken()->delete();

            return response()->json([
                'message' => 'Sesión cerrada exitosamente',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al cerrar sesión',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function me(): JsonResponse
    {
        return response()->json(Auth::user());
    }
}
