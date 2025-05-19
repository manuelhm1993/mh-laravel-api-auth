<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request) 
    {
        // Validación de los datos de entrada
        $validator = Validator::make($request->all(), [
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Si la validación falla, devuelve los mensajes de error
        if ($validator->fails()) 
        {
            return response()->json($validator->errors());
        }

        // Intenta autenticar al usuario
        if(!Auth::attempt($request->only('email', 'password'))) 
        {
            // Si la autenticación falla, devuelve un mensaje de error
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // $user  = Auth::user(); // Obtiene el usuario autenticado, pero da error al crear el token 
        $user  = User::where('email', $request->email)->firstOrFail();
        
        $token = $user->createToken('auth_token')->plainTextToken;

        // Crea un objeto de respuesta
        $response = [
            'message'      => "Hi {$user->name}",
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => $user,
        ];

        // Devuelve la respuesta en formato JSON
        return response()->json($response);
    }

    public function register(Request $request)
    {
        // Validación de los datos de entrada
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        // Si la validación falla, devuelve los mensajes de error
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        // Si la validación es exitosa, crea un nuevo usuario
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password), // bcrypt($request->password),
        ]);

        // Genera un token de acceso para el nuevo usuario
        $token = $user->createToken('auth_token')->plainTextToken;

        // Crea un objeto de respuesta
        $response = [
            'data'         => $user,
            'access_token' => $token,
            'token_type'   => 'Bearer',
        ];

        // Devuelve la respuesta en formato JSON
        return response()->json($response);
    }
}
