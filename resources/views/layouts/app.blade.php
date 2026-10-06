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
    <aside class="w-64 bg-indigo-950 text-white flex flex-col justify-between shrink-0 shadow-xl">
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
            <nav class="p-4 space-y-2 text-sm font-medium">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl bg-indigo-900/70 text-white">
                    <i class="fa-solid fa-chart-pie w-5 text-indigo-400"></i>
                    <span>Cierres y Métricas</span>
                </a>
                <a href="{{ route('alumnos.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-indigo-200 hover:bg-indigo-900/40">
                    <i class="fa-solid fa-folder-open w-5 text-indigo-400"></i>
                    <span>Expedientes</span>
                </a>
                <a href="{{ route('atenciones.create') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-indigo-200 hover:bg-indigo-900/40">
                    <i class="fa-solid fa-notes-medical w-5 text-indigo-400"></i>
                    <span>+ Intervención</span>
                </a>
            </nav>
        </div>
        <div class="p-4 border-t border-indigo-900/50 text-xs text-indigo-300">
            <p class="font-semibold text-white">Ciclo: 2026-2027</p>
            <p class="text-[11px]">Servidor Físico Local • LAN</p>
        </div>
    </aside>

    <main class="flex-1 flex flex-col min-w-0">
        <header class="h-16 bg-white border-b border-slate-200 px-8 flex items-center justify-between shadow-sm">
            <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Módulo de Psicopedagogía</span>
            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                ● MariaDB Local Conectada
            </span>
        </header>
        <div class="p-8 max-w-7xl w-full mx-auto">
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center space-x-2">
                    <i class="fa-solid fa-circle-check text-emerald-600"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @yield('content')
        </div>
    </main>
</body>
</html>