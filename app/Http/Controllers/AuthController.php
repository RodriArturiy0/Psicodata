<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Muestra la pantalla de inicio de sesión institucional con selector de perfiles demo.
     */
    public function showLoginForm()
    {
        // Matriz de Acceso de Usuarios Oficial - PsicoData
        $usuariosInstitucionales = [
            [
                'key' => 'mercy',
                'email' => 'mercysegovia@colegiopaidoscampeche.edu.mx',
                'rol' => 'PSICOPEDAGOGIA',
                'nombre' => 'Mercedes Segovia Mayo',
                'cargo' => 'Psicóloga de Primaria / Admin',
                'badge' => 'Acceso Completo (Admin)',
                'color' => 'indigo',
                'desc' => 'Admin, edición de expedientes, captura de intervenciones y cierres de mes.'
            ],
            [
                'key' => 'greyci',
                'email' => 'greycia@colegiopaidoscampeche.edu.mx',
                'rol' => 'PSICOPEDAGOGIA',
                'nombre' => 'Greyci Salas Cahuich',
                'cargo' => 'Psicóloga de Primaria',
                'badge' => 'Acceso Completo',
                'color' => 'indigo',
                'desc' => 'Edición de expedientes, captura en aula y notas de evolución.'
            ],
            [
                'key' => 'damaris',
                'email' => 'damarismorales@colegiopaidoscampeche.edu.mx',
                'rol' => 'DIRECCION',
                'nombre' => 'Damaris Morales Bastarrachea',
                'cargo' => 'Directora de Primaria',
                'badge' => 'Solo Lectura (Dirección)',
                'color' => 'emerald',
                'desc' => 'Consulta de expedientes, reportes e informes trimestrales y mensuales.'
            ],
        ];

        // Accesos de prueba / técnicos
        $usuariosDemo = [
            [
                'key' => 'docente',
                'email' => 'docente@colegiopaidoscampeche.edu.mx',
                'rol' => 'DOCENTE',
                'nombre' => 'Mtra. Docente Titular',
                'cargo' => 'Plantilla Docente',
                'badge' => 'Prueba Bloqueo 403',
                'color' => 'rose',
                'desc' => 'Acceso restringido (Bloqueo 403 por confidencialidad clínica según RN-01).'
            ],
            [
                'key' => 'admin',
                'email' => 'admin@colegiopaidoscampeche.edu.mx',
                'rol' => 'ADMIN',
                'nombre' => 'Administración de TI',
                'cargo' => 'Servidor Local y Respaldos',
                'badge' => 'TI y Backups',
                'color' => 'slate',
                'desc' => 'Gestión de respaldos mysqldump, base de datos MariaDB y ciclos escolares.'
            ],
        ];

        return view('auth.login', compact('usuariosInstitucionales', 'usuariosDemo'));
    }

    /**
     * Procesa la autenticación de credenciales.
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
     * Cierre de sesión seguro.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('info', 'Has cerrado sesión en PsicoData de manera segura.');
    }

    /**
     * Conmutador rápido de perfiles demo y cuentas institucionales para pruebas y demostración.
     */
    public function switchRole(Request $request, string $rol)
    {
        $clave = strtoupper($rol);
        $rolesValidos = ['MERCY', 'GREYCI', 'DAMARIS', 'PSICOPEDAGOGIA', 'DIRECCION', 'DOCENTE', 'ADMIN'];

        if (!in_array($clave, $rolesValidos)) {
            return back()->with('error', 'Usuario o perfil no reconocido.');
        }

        $usuarioConfig = match ($clave) {
            'MERCY' => [
                'email' => 'mercysegovia@colegiopaidoscampeche.edu.mx',
                'name' => 'Mercedes Segovia Mayo',
                'rol' => 'PSICOPEDAGOGIA',
                'cargo' => 'Psicóloga de Primaria / Admin',
            ],
            'GREYCI' => [
                'email' => 'greycia@colegiopaidoscampeche.edu.mx',
                'name' => 'Greyci Salas Cahuich',
                'rol' => 'PSICOPEDAGOGIA',
                'cargo' => 'Psicóloga de Primaria',
            ],
            'DAMARIS' => [
                'email' => 'damarismorales@colegiopaidoscampeche.edu.mx',
                'name' => 'Damaris Morales Bastarrachea',
                'rol' => 'DIRECCION',
                'cargo' => 'Directora de Primaria',
            ],
            'PSICOPEDAGOGIA' => [
                'email' => 'mercysegovia@colegiopaidoscampeche.edu.mx',
                'name' => 'Mercedes Segovia Mayo',
                'rol' => 'PSICOPEDAGOGIA',
                'cargo' => 'Psicóloga de Primaria / Admin',
            ],
            'DIRECCION' => [
                'email' => 'damarismorales@colegiopaidoscampeche.edu.mx',
                'name' => 'Damaris Morales Bastarrachea',
                'rol' => 'DIRECCION',
                'cargo' => 'Directora de Primaria',
            ],
            'DOCENTE' => [
                'email' => 'docente@colegiopaidoscampeche.edu.mx',
                'name' => 'Mtra. Docente Titular',
                'rol' => 'DOCENTE',
                'cargo' => 'Plantilla Docente (Prueba Bloqueo 403)',
            ],
            'ADMIN' => [
                'email' => 'admin@colegiopaidoscampeche.edu.mx',
                'name' => 'Administración de TI',
                'rol' => 'ADMIN',
                'cargo' => 'Servidor Local y TI',
            ],
        };

        $user = User::firstOrCreate(
            ['email' => $usuarioConfig['email']],
            [
                'name' => $usuarioConfig['name'],
                'password' => Hash::make('paidos2026'),
                'rol' => $usuarioConfig['rol'],
                'cargo' => $usuarioConfig['cargo'],
                'activo' => true,
            ]
        );

        if ($user->rol !== $usuarioConfig['rol'] || $user->name !== $usuarioConfig['name'] || $user->cargo !== $usuarioConfig['cargo']) {
            $user->rol = $usuarioConfig['rol'];
            $user->name = $usuarioConfig['name'];
            $user->cargo = $usuarioConfig['cargo'];
            $user->save();
        }

        Auth::login($user);
        $request->session()->regenerate();

        if ($user->rol === 'DOCENTE') {
            return redirect()->route('dashboard')->with('warning', 'Atención: Has iniciado como Docente. El acceso a expedientes clínicos se encuentra bloqueado por confidencialidad (RN-01).');
        }

        return redirect()->route('dashboard')->with('success', 'Sesión iniciada como ' . $user->name . ' (' . $user->cargo . ').');
    }
}

