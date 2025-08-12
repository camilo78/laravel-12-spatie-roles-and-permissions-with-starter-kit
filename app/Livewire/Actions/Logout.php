<?php

namespace App\Livewire\Actions;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

/**
 * Acción Livewire para cerrar sesión de usuarios
 * 
 * Esta clase maneja el proceso completo de cierre de sesión,
 * incluyendo la invalidación de la sesión y regeneración del token CSRF
 * 
 * @package App\Livewire\Actions
 */
class Logout
{
    /**
     * Cierra la sesión del usuario actual de la aplicación
     * 
     * Realiza las siguientes acciones de seguridad:
     * - Cierra la sesión del usuario autenticado
     * - Invalida la sesión actual
     * - Regenera el token CSRF para prevenir ataques
     * - Redirecciona al usuario a la página principal
     * 
     * @return RedirectResponse
     */
    public function __invoke(): RedirectResponse
    {
        // Cerrar sesión del usuario autenticado usando el guard 'web'
        Auth::guard('web')->logout();

        // Invalidar la sesión actual para limpiar todos los datos
        Session::invalidate();
        
        // Regenerar el token CSRF para prevenir ataques de falsificación
        Session::regenerateToken();

        // Redireccionar al usuario a la página principal
        return redirect('/');
    }
}
