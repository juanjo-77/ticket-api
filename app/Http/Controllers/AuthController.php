<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
                'success' => true,
                'message' => 'Usuario registrado correctamente',
                'data'    => [
                    'user'  => $user,
                    'token' => $token,
                ],
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
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
                    'success' => false,
                    'message' => 'Credenciales incorrectas',
                ], 401);
            }

            $user  = User::where('email', $request->email)->firstOrFail();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login exitoso',
                'data'    => [
                    'user'  => $user,
                    'token' => $token,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en el login',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function logout(): JsonResponse
    {
        try {
            auth()->user()->tokens()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Sesión cerrada correctamente',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cerrar sesión',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
