<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)

    {
        $usuario = $request->user();

        abort_unless($usuario->rol === 2, 403);

        $notificaciones =  auth()->user()->unreadNotifications;
          
        //limpiar notificaciones 
        auth()->user()->unreadNotifications->markAsRead();

        return view('notificaciones.index', [
            'notificaciones' => $notificaciones
        ]);
    }
}
