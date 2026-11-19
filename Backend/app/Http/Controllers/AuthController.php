<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Procesar el Login (Modo API con Debug)
    public function login(Request $request) {
        $request->validate([
            'name' => ['required'],
            'password' => ['required'],
        ]);

        $credentials = [
            'name' => $request->name,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
           $token = $user->createToken('API Token', ['*'], now()->addMinutes(120))->plainTextToken;

            return response()->json([
                'mensaje' => 'Login exitoso',
                'user' => $user,
                'token' => $token
            ], 200);
        }

        return response()->json(['mensaje' => 'Credenciales incorrectas'], 401);
    }

    // Procesar el Registro (Modo API)
    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), 
        ]);

        return response()->json([
            'mensaje' => 'Usuario registrado con éxito',
            'user' => $user
        ], 201);
    }

     // Cerrar sesión (Modo API)
    public function logout(Request $request) {
        // Elimina el token actual que usó el usuario para autenticarse
        $request->user()->currentAccessToken()->delete();
        
        return response()->json(['mensaje' => 'Sesión cerrada correctamente'], 200);
    }
    
    
}