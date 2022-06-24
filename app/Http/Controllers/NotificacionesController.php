<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class NotificacionesController extends Controller
{
    public function notificacion(Request $request)
    {
        $recipients = $request->destinatarios;
        if(!is_array($recipients)){
            $recipients=[$recipients];
        }
        $estado = fcm()
            ->to($recipients) // $recipients must an array
            ->priority('normal')
            ->timeToLive(0)
            ->notification([
                'title' => $request->titulo,
                'body' => $request->mensaje,
            ])
            ->send();
        return $estado;
    }
    public function notificacionGlobal(Request $request)
    {
        $recipients = User::whereNotNull('firebase_token')->pluck('firebase_token')->toArray();
        $estado = fcm()
            ->to($recipients) // $recipients must an array
            ->priority('normal')
            ->timeToLive(0)
            ->notification([
                'title' => $request->titulo,
                'body' => $request->mensaje,
            ])
            ->send();
        return $estado;
    }
}
