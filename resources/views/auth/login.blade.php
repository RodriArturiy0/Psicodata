<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - PsicoData Colegio Paidos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-4xl w-full grid grid-cols-1 lg:grid-cols-12 bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-100">
        <!-- Panel Izquierdo: Bienvenida y Contexto -->
        <div class="lg:col-span-5 bg-gradient-to-br from-indigo-700 to-indigo-900 p-8 sm:p-10 text-white flex flex-col justify-between">
            <div>
                <div class="w-14 h-14 bg-white/20 backdrop-blur rounded-2xl flex items-center justify-center border border-white/30 mb-6 shadow-inner">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <span class="inline-block px-3 py-1 bg-white/15 text-indigo-100 rounded-full text-xs font-semibold tracking-wider uppercase mb-3 border border-white/20">
                    Colegio Paidos Campeche
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">PsicoData</h1>
                <p class="text-indigo-200 text-sm mt-2 leading-relaxed">
                    Sistema centralizado para la administración de expedientes psicopedagógicos, seguimiento clínico y métricas institucionales.
                </p>
            </div>

            <div class="mt-8 pt-6 border-t border-indigo-600/40 space-y-3 text-xs text-indigo-200">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Servidor físico local institucional (LAN)</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-indigo-300"></span>
                    <span>Control estricto de confidencialidad clínica</span>
                </div>
            </div>
        </div>

        <!-- Panel Derecho: Formulario y Perfiles de Demostración -->
        <div class="lg:col-span-7 p-8 sm:p-10 flex flex-col justify-between">
            <div>
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-slate-900">Acceso Institucional Seguro</h2>
                    <p class="text-xs text-slate-500 mt-1">Ingrese con sus credenciales autorizadas</p>
                </div>

                @if (session('error'))
                    <div class="mb-4 p-3 bg-rose-50 border-l-4 border-rose-500 text-rose-800 text-xs rounded-r-lg font-medium">
                        {{ session('error') }}
                    </div>
                @endif

                @if (session('info'))
                    <div class="mb-4 p-3 bg-blue-50 border-l-4 border-blue-500 text-blue-800 text-xs rounded-r-lg font-medium">
                        {{ session('info') }}
                    </div>
                @endif

                <!-- Formulario Estándar -->
                <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Correo Institucional</label>
                        <input type="email" id="input-email" name="email" value="{{ old('email', 'mercysegovia@colegiopaidoscampeche.edu.mx') }}" required
                            class="w-full px-4 py-2.5 rounded-xl border @error('email') border-rose-500 bg-rose-50 @else border-slate-200 @enderror text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none transition">
                        @error('email')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Contraseña</label>
                        <input type="password" name="password" value="paidos2026" required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none transition">
                    </div>

                    <div class="flex items-center justify-between text-xs text-slate-600">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remember" class="rounded text-indigo-600 focus:ring-indigo-500">
                            <span>Recordar sesión</span>
                        </label>
                        <span class="text-indigo-600 font-semibold">Servidor Local Paidos</span>
                    </div>

                    <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-xl transition shadow-lg shadow-indigo-100 flex items-center justify-center gap-2">
                        <span>Ingresar a PsicoData</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </form>

                <!-- Matriz de Acceso de Usuarios - PsicoData -->
                <div class="mt-6 pt-5 border-t border-slate-100">
                    <div class="flex items-center justify-between mb-2.5">
                        <p class="text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Matriz de Acceso Institucional:
                        </p>
                        <span class="text-[10px] bg-indigo-100 text-indigo-800 font-semibold px-2 py-0.5 rounded-full">3 Cuentas Oficiales</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                        <!-- 1. Psicóloga Mercy -->
                        <a href="{{ route('switch.role', 'MERCY') }}" class="p-2.5 bg-indigo-50/80 hover:bg-indigo-100/80 border border-indigo-200/80 rounded-xl transition text-left group">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-indigo-950 group-hover:text-indigo-600 truncate">Mercedes Segovia</span>
                                <span class="text-[9px] bg-indigo-200 text-indigo-800 px-1 py-0.2 rounded font-bold shrink-0">Admin</span>
                            </div>
                            <p class="text-[11px] text-slate-600 mt-0.5 font-medium">Psicóloga Mercy</p>
                            <p class="text-[10px] text-indigo-700 font-mono truncate mt-1">mercysegovia@...</p>
                            <span class="inline-block mt-1 text-[9px] text-indigo-600 font-semibold">● Acceso Completo</span>
                        </a>

                        <!-- 2. Psicóloga Greyci -->
                        <a href="{{ route('switch.role', 'GREYCI') }}" class="p-2.5 bg-indigo-50/80 hover:bg-indigo-100/80 border border-indigo-200/80 rounded-xl transition text-left group">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-indigo-950 group-hover:text-indigo-600 truncate">Greyci Salas C.</span>
                                <span class="text-[9px] bg-indigo-200 text-indigo-800 px-1 py-0.2 rounded font-bold shrink-0">Psico</span>
                            </div>
                            <p class="text-[11px] text-slate-600 mt-0.5 font-medium">Psicóloga Greyci</p>
                            <p class="text-[10px] text-indigo-700 font-mono truncate mt-1">greycia@...</p>
                            <span class="inline-block mt-1 text-[9px] text-indigo-600 font-semibold">● Acceso Completo</span>
                        </a>

                        <!-- 3. Directora de Primaria -->
                        <a href="{{ route('switch.role', 'DAMARIS') }}" class="p-2.5 bg-emerald-50/80 hover:bg-emerald-100/80 border border-emerald-200/80 rounded-xl transition text-left group">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-emerald-950 group-hover:text-emerald-700 truncate">Damaris Morales</span>
                                <span class="text-[9px] bg-emerald-200 text-emerald-800 px-1 py-0.2 rounded font-bold shrink-0">Lectura</span>
                            </div>
                            <p class="text-[11px] text-slate-600 mt-0.5 font-medium">Directora Primaria</p>
                            <p class="text-[10px] text-emerald-700 font-mono truncate mt-1">damarismorales@...</p>
                            <span class="inline-block mt-1 text-[9px] text-emerald-700 font-semibold">● Solo Lectura</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100 text-[11px] text-slate-400 text-center">
                Seguridad institucional conforme a la NOM y leyes de protección de datos personales en el ámbito escolar.
            </div>
        </div>
    </div>
</body>
</html>

