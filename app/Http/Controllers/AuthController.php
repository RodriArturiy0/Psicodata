<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Muestra la pantalla de inicio de sesión institucional limpia y segura.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Procesa la autenticación de credenciales institucionales.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'El correo institucional es obligatorio.',
            'email.email' => 'Ingrese una dirección de correo válida.',
            'password.required' => 'La contraseña es requerida.',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'))
                ->with('success', 'Sesión iniciada correctamente como ' . Auth::user()->name . ' (' . Auth::user()->rol . ').');
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con los registros institucionales.',
        ])->onlyInput('email');
    }

    /**
     * Cierre de sesión seguro e invalidación de token de sesión.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('info', 'Has cerrado sesión en PsicoData de manera segura.');
    }
}
