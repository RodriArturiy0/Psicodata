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

        <!-- Panel Derecho: Formulario de Autenticación -->
        <div class="lg:col-span-7 p-8 sm:p-12 flex flex-col justify-between">
            <div>
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Acceso Institucional Seguro</h2>
                    <p class="text-xs text-slate-500 mt-1">Ingrese sus credenciales oficiales para acceder al sistema</p>
                </div>

                @if (session('error'))
                    <div class="mb-5 p-3.5 bg-rose-50 border-l-4 border-rose-500 text-rose-800 text-xs rounded-r-xl font-medium flex items-center gap-2">
                        <svg class="w-4 h-4 text-rose-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if (session('info'))
                    <div class="mb-5 p-3.5 bg-blue-50 border-l-4 border-blue-500 text-blue-800 text-xs rounded-r-xl font-medium flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('info') }}</span>
                    </div>
                @endif

                <!-- Formulario Estándar -->
                <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label for="input-email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Correo Institucional</label>
                        <div class="relative">
                            <input type="email" id="input-email" name="email" value="{{ old('email') }}" placeholder="usuario@colegiopaidoscampeche.edu.mx" required autofocus
                                class="w-full px-4 py-3 rounded-xl border @error('email') border-rose-500 bg-rose-50/50 @else border-slate-200 @enderror text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none transition placeholder:text-slate-400">
                        </div>
                        @error('email')
                            <p class="text-xs text-rose-600 mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="input-password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Contraseña</label>
                        <div class="relative">
                            <input type="password" id="input-password" name="password" placeholder="••••••••" required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none transition placeholder:text-slate-400">
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-xs text-slate-600 pt-1">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="remember" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            <span>Recordar sesión</span>
                        </label>
                        <span class="text-indigo-600 font-semibold flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd"/>
                            </svg>
                            Intranet LAN Segura
                        </span>
                    </div>

                    <button type="submit" class="w-full py-3.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-bold text-sm rounded-xl transition shadow-lg shadow-indigo-100 flex items-center justify-center gap-2 mt-2">
                        <span>Iniciar Sesión</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </form>
            </div>

            <div class="mt-10 pt-4 border-t border-slate-100 text-[11px] text-slate-400 text-center">
                Seguridad institucional conforme a la NOM y directrices de protección de datos personales en el ámbito escolar.
            </div>
        </div>
    </div>
</body>
</html>

