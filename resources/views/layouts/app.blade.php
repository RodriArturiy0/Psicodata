<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PsicoData - Colegio Paidos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-slate-50 font-sans text-slate-800 flex min-h-screen">
    @php
        $usuarioActual = auth()->user();
        $esDocente = $usuarioActual && $usuarioActual->esDocente();
        $puedeVerExpedientes = !$usuarioActual || $usuarioActual->puedeVerExpedientes();
        $puedeEditarExpedientes = $usuarioActual && $usuarioActual->puedeEditarExpedientes();
        $puedeRespaldar = !$usuarioActual || $usuarioActual->puedeGenerarRespaldos();
    @endphp

    <!-- Barra Lateral de Navegación (Escritorio / Tablet grande) -->
    <aside class="w-64 bg-indigo-950 text-white flex flex-col justify-between shrink-0 shadow-xl hidden md:flex">
        <div>
            <div class="p-6 border-b border-indigo-900/50 flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center shadow-lg">
                    <i class="fa-solid fa-brain text-xl text-white"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold tracking-tight text-white">PsicoData</h1>
                    <p class="text-xs text-indigo-300">Colegio Paidos Campeche</p>
                </div>
            </div>

            <!-- Perfil Activo en Barra Lateral -->
            <div class="px-4 py-3 mx-4 my-3 rounded-xl bg-indigo-900/60 border border-indigo-800/60 text-xs">
                <div class="flex items-center justify-between">
                    <span class="text-indigo-300 uppercase tracking-wider text-[10px] font-semibold">Perfil Activo</span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold 
                        @if($esDocente) bg-rose-900 text-rose-200 border border-rose-700
                        @elseif($usuarioActual && $usuarioActual->esDireccion()) bg-emerald-900 text-emerald-200 border border-emerald-700
                        @elseif($usuarioActual && $usuarioActual->esAdmin()) bg-slate-800 text-slate-200 border border-slate-600
                        @else bg-indigo-800 text-indigo-200 border border-indigo-700 @endif">
                        {{ $usuarioActual->rol ?? 'PSICOPEDAGOGIA' }}
                    </span>
                </div>
                <p class="font-bold text-white mt-1 truncate">{{ $usuarioActual->name ?? 'Psicopedagogía Paidos' }}</p>
                <p class="text-[11px] text-indigo-300 truncate">{{ $usuarioActual->cargo ?? 'Especialista Titular' }}</p>
            </div>

            <nav class="p-4 space-y-1.5 text-sm font-medium">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('dashboard*') && !request()->routeIs('dashboard.imprimir') ? 'bg-indigo-900 text-white' : 'text-indigo-200 hover:bg-indigo-900/40' }} transition">
                    <i class="fa-solid fa-chart-pie w-5 text-indigo-400"></i>
                    <span>Cierres y Métricas</span>
                </a>

                @if($puedeVerExpedientes)
                    <a href="{{ route('alumnos.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('alumnos*') ? 'bg-indigo-900 text-white' : 'text-indigo-200 hover:bg-indigo-900/40' }} transition">
                        <i class="fa-solid fa-folder-open w-5 text-indigo-400"></i>
                        <span>Expedientes</span>
                    </a>
                    <a href="{{ route('ciclos.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('ciclos*') ? 'bg-indigo-900 text-white' : 'text-indigo-200 hover:bg-indigo-900/40' }} transition">
                        <i class="fa-solid fa-calendar-days w-5 text-indigo-400"></i>
                        <span>Ciclos y Brincado</span>
                    </a>

                    @if($puedeEditarExpedientes)
                        <a href="{{ route('atenciones.create') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('atenciones.create') ? 'bg-indigo-900 text-white' : 'text-indigo-200 hover:bg-indigo-900/40' }} transition">
                            <i class="fa-solid fa-notes-medical w-5 text-indigo-400"></i>
                            <span>+ Intervención</span>
                        </a>
                        <a href="{{ route('atenciones.movil.aula') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl bg-amber-500/10 text-amber-300 hover:bg-amber-500/20 border border-amber-400/20 transition">
                            <i class="fa-solid fa-mobile-screen-button w-5 text-amber-400"></i>
                            <span>Modo Aula (Móvil) 📱</span>
                        </a>
                    @endif
                @else
                    <div class="p-3 bg-rose-950/40 border border-rose-800/40 rounded-xl text-xs text-rose-300 space-y-1">
                        <div class="flex items-center gap-1.5 font-bold text-rose-200">
                            <i class="fa-solid fa-lock text-rose-400"></i>
                            <span>Expedientes Bloqueados</span>
                        </div>
                        <p class="text-[11px] leading-tight text-rose-300/80">
                            Por confidencialidad médica/escolar, la plantilla docente no tiene acceso a historiales clínicos.
                        </p>
                    </div>
                @endif

                @if($puedeRespaldar)
                    <a href="{{ route('respaldos.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('respaldos*') ? 'bg-indigo-900 text-white' : 'text-indigo-200 hover:bg-indigo-900/40' }} transition">
                        <i class="fa-solid fa-database w-5 text-emerald-400"></i>
                        <span>Copias de Seguridad</span>
                    </a>
                @endif
            </nav>
        </div>

        <div class="p-4 border-t border-indigo-900/50 text-xs text-indigo-300">
            @php
                $cicloNav = \App\Models\CicloEscolar::where('estado', 'ACTIVO')->first();
            @endphp
            <div class="flex items-center justify-between">
                <span class="font-semibold text-white">Ciclo: {{ $cicloNav->nombre_ciclo ?? '2026-2027' }}</span>
            </div>
            <p class="text-[11px] text-indigo-400 mt-0.5">Servidor Institucional Paidos</p>
        </div>
    </aside>

    <!-- Contenido Principal -->
    <main class="flex-1 flex flex-col min-w-0 pb-24 md:pb-6">
        <header class="h-16 bg-white border-b border-slate-200 px-4 md:px-8 flex items-center justify-between shadow-sm">
            <div class="flex items-center space-x-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Módulo de Psicopedagogía</span>
            </div>

            <!-- Acciones de Cabecera -->
            <div class="flex items-center space-x-2 sm:space-x-3">
                <!-- Usuario Actual / Perfil Minimalista -->
                <div class="relative group">
                    <button class="px-2.5 sm:px-3 py-1.5 rounded-xl text-xs font-semibold border flex items-center gap-2 transition
                        @if($esDocente) bg-rose-50 text-rose-800 border-rose-200
                        @elseif($usuarioActual && $usuarioActual->esDireccion()) bg-emerald-50 text-emerald-800 border-emerald-200
                        @else bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100 @endif">
                        <span class="w-6 h-6 rounded-full @if($usuarioActual && $usuarioActual->esDireccion()) bg-emerald-200 text-emerald-800 @else bg-indigo-200 text-indigo-800 @endif flex items-center justify-center text-[10px] font-bold">
                            {{ substr($usuarioActual->name ?? 'U', 0, 1) }}
                        </span>
                        <div class="text-left hidden sm:block">
                            <p class="text-xs font-bold text-slate-800 leading-none">{{ $usuarioActual->name ?? 'Usuario' }}</p>
                            <p class="text-[10px] text-slate-400 mt-0.5 leading-none">
                                @if($usuarioActual && $usuarioActual->esDireccion()) Directora (Lectura)
                                @elseif($usuarioActual && $usuarioActual->esAdmin()) Psicóloga Mercy (Admin)
                                @else Psicóloga @endif
                            </p>
                        </div>
                        <i class="fa-solid fa-chevron-down text-[9px] text-slate-400 ml-0.5"></i>
                    </button>
                    <!-- Menú Desplegable Limpio -->
                    <div class="absolute right-0 mt-1.5 w-60 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 hidden group-hover:block z-50 text-xs">
                        <div class="px-3 py-1 text-[10px] uppercase font-bold text-slate-400 tracking-wider">Cambiar de cuenta:</div>
                        <a href="{{ route('switch.role', 'MERCY') }}" class="block px-3 py-2 hover:bg-indigo-50 text-slate-800 transition">
                            <span class="font-bold block text-slate-900">Mercedes Segovia</span>
                            <span class="text-[10px] text-indigo-600">Psicóloga Mercy (Admin)</span>
                        </a>
                        <a href="{{ route('switch.role', 'GREYCI') }}" class="block px-3 py-2 hover:bg-indigo-50 text-slate-800 transition">
                            <span class="font-bold block text-slate-900">Greyci Salas</span>
                            <span class="text-[10px] text-indigo-600">Psicóloga Greyci (Completo)</span>
                        </a>
                        <a href="{{ route('switch.role', 'DAMARIS') }}" class="block px-3 py-2 hover:bg-emerald-50 text-slate-800 transition">
                            <span class="font-bold block text-slate-900">Damaris Morales</span>
                            <span class="text-[10px] text-emerald-700">Directora Primaria (Solo Lectura)</span>
                        </a>
                    </div>
                </div>

                @if(auth()->check())
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 text-xs transition rounded-lg hover:bg-slate-100" title="Cerrar sesión">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">
                        Iniciar Sesión
                    </a>
                @endif
            </div>
        </header>

        <div class="p-4 sm:p-6 md:p-8 max-w-7xl w-full mx-auto">
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-circle-check text-emerald-600"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @if(session('warning'))
                <div class="mb-6 p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-sm flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-triangle-exclamation text-amber-600"></i>
                        <span>{{ session('warning') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-amber-500 hover:text-amber-800">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-circle-xmark text-rose-600"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-800">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Barra de Navegación Móvil Inferior (Permite salir a cualquier ruta desde celular/tablet) -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur border-t border-slate-200 px-2 py-2 flex items-center justify-around shadow-2xl">
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center py-1 px-2 rounded-lg text-xs {{ request()->routeIs('dashboard*') && !request()->routeIs('dashboard.imprimir') ? 'text-indigo-600 font-bold' : 'text-slate-500 hover:text-indigo-600' }}">
            <i class="fa-solid fa-chart-pie text-base"></i>
            <span class="text-[10px] mt-0.5">Métricas</span>
        </a>

        @if($puedeVerExpedientes)
            <a href="{{ route('alumnos.index') }}" class="flex flex-col items-center py-1 px-2 rounded-lg text-xs {{ request()->routeIs('alumnos*') ? 'text-indigo-600 font-bold' : 'text-slate-500 hover:text-indigo-600' }}">
                <i class="fa-solid fa-folder-open text-base"></i>
                <span class="text-[10px] mt-0.5">Expedientes</span>
            </a>

            @if($puedeEditarExpedientes)
                <!-- Botón Central Destacado: Modo Aula -->
                <a href="{{ route('atenciones.movil.aula') }}" class="flex flex-col items-center text-amber-600 font-bold text-xs -mt-5">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-amber-500 to-amber-400 text-white flex items-center justify-center shadow-lg shadow-amber-500/30 border-2 border-white transition active:scale-95">
                        <i class="fa-solid fa-mobile-screen-button text-lg"></i>
                    </div>
                    <span class="text-[10px] mt-1 text-slate-700 font-semibold">Modo Aula</span>
                </a>

                <a href="{{ route('atenciones.create') }}" class="flex flex-col items-center py-1 px-2 rounded-lg text-xs {{ request()->routeIs('atenciones.create') ? 'text-indigo-600 font-bold' : 'text-slate-500 hover:text-indigo-600' }}">
                    <i class="fa-solid fa-notes-medical text-base"></i>
                    <span class="text-[10px] mt-0.5">+ Atención</span>
                </a>
            @endif

            <a href="{{ route('ciclos.index') }}" class="flex flex-col items-center py-1 px-2 rounded-lg text-xs {{ request()->routeIs('ciclos*') ? 'text-indigo-600 font-bold' : 'text-slate-500 hover:text-indigo-600' }}">
                <i class="fa-solid fa-calendar-days text-base"></i>
                <span class="text-[10px] mt-0.5">Ciclos</span>
            </a>
        @else
            <div class="flex flex-col items-center py-1 px-2 text-rose-500 text-xs">
                <i class="fa-solid fa-lock text-base"></i>
                <span class="text-[10px] mt-0.5 font-bold">Docente</span>
            </div>
        @endif
    </nav>
</body>
</html>
