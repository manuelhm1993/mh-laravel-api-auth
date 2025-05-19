<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use \stdClass;

class AuthController extends Controller
{
    public function login(Request $request) 
    {

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
