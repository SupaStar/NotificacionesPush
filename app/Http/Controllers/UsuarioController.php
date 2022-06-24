<?php

namespace App\Http\Controllers;

use App\Http\Requests\Usuarios as UsuariosRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsuarioController extends Controller
{
    public function registrar(UsuariosRequest\Registro $request)
    {
        $usuario = new User();
        $usuario->username = $request->username;
        $usuario->email = $request->email;
        $usuario->password = Hash::make($request->password);
        $usuario->save();
        return response()->json(['mensaje' => 'Usuario registrado correctamente']);
    }
    public function login(UsuariosRequest\Login $request)
    {
        $usuario = User::query()->where('email', $request->email)->first();
        if ($usuario) {
            if (Hash::check($request->password, $usuario->password)) {
                $usuario->firebase_token = $request->firebase_token;
                $usuario->save();
                return response()->json(['mensaje' => 'Usuario autenticado correctamente']);
            } else {
                return response()->json(['mensaje' => 'Contraseña incorrecta']);
            }
        } else {
            return response()->json(['mensaje' => 'Usuario no existe']);
        }
    }
}
