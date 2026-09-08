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

        $notificaciones = $usuario->unreadNotifications;

        return view('notificaciones.index', [
            'notificaciones' => $notificaciones
        ]);
    }
}
