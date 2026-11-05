@extends('layouts.app')

@section('content')
<div class="space-y-8">

    <!-- Encabezado con Selector Accesible de Meses / Años -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
        <div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-indigo-600"></span>
                <h2 class="text-2xl font-black text-slate-900">Cierre Mensual de Actividades</h2>
            </div>
            <p class="text-xs text-slate-500 mt-1">
                Conteo automatizado de intervenciones para rendición de cuentas a Dirección Escolar
            </p>
        </div>

        <!-- Selector Rápido de Navegación Temporal (Mes Anterior / Siguiente) -->
        <div class="flex flex-wrap items-center gap-2">
            <!-- Botón Mes Anterior -->
            <a href="{{ route('dashboard', ['mes' => $mesPrevio, 'anio' => $anioPrevio]) }}" 
               class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition flex items-center gap-1.5"
               title="Ir al mes anterior">
                <i class="fa-solid fa-chevron-left text-[10px]"></i>
                <span class="hidden sm:inline">Mes Anterior</span>
            </a>

            <!-- Formulario Directo de Mes y Año -->
            <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-1.5 bg-slate-100/80 p-1 rounded-xl border border-slate-200">
                <select name="mes" class="text-xs border-none bg-transparent font-extrabold text-slate-800 focus:ring-0 cursor-pointer">
                    @foreach($nombresMeses as $num => $nombre)
                        <option value="{{ $num }}" {{ $mes == $num ? 'selected' : '' }}>{{ $nombre }}</option>
                    @endforeach
                </select>

                <select name="anio" class="text-xs border-none bg-transparent font-extrabold text-slate-800 focus:ring-0 cursor-pointer">
                    @for($y = 2025; $y <= 2028; $y++)
                        <option value="{{ $y }}" {{ $anio == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>

                <button type="submit" class="px-3 py-1 bg-indigo-600 text-white rounded-lg text-xs font-bold hover:bg-indigo-700 transition">
                    Ver
                </button>
            </form>

            <!-- Botón Mes Siguiente -->
            <a href="{{ route('dashboard', ['mes' => $mesSiguiente, 'anio' => $anioSiguiente]) }}" 
               class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition flex items-center gap-1.5"
               title="Ir al mes siguiente">
                <span class="hidden sm:inline">Mes Siguiente</span>
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
            </a>

            <!-- Botón Imprimir Reporte Mensual -->
            <a href="{{ route('dashboard.imprimir', ['mes' => $mes, 'anio' => $anio]) }}" target="_blank"
               class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold shadow-sm transition flex items-center gap-1.5">
                <i class="fa-solid fa-print"></i>
                <span>Imprimir Informe / PDF</span>
            </a>
        </div>
    </div>

    <!-- Indicador de Periodo Actual Consultado -->
    <div class="flex items-center justify-between text-xs text-slate-500 px-1">
        <p>Mostrando estadísticas acumuladas de: <strong class="text-indigo-900 font-extrabold">{{ $nombresMeses[$mes] ?? 'Mes' }} de {{ $anio }}</strong></p>
        <p class="text-[11px]">Colegio Paidos Campeche • Servidor LAN</p>
    </div>

    <!-- Tarjetas de Conteo Mensual Automatizado (4 Categorías) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:border-amber-300 transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase">En Aula</span>
                <span class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-chalkboard-user"></i>
                </span>
            </div>
            <p class="text-3xl font-extrabold text-slate-900 mt-2">{{ $totales['AULA'] }}</p>
            <p class="text-xs text-slate-500 mt-1">Observaciones directas de clase</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:border-blue-300 transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase">Padres de Familia</span>
                <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-people-roof"></i>
                </span>
            </div>
            <p class="text-3xl font-extrabold text-slate-900 mt-2">{{ $totales['PADRES'] }}</p>
            <p class="text-xs text-slate-500 mt-1">Reportes de entrevista y citas</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:border-emerald-300 transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase">Alumnos</span>
                <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-user-graduate"></i>
                </span>
            </div>
            <p class="text-3xl font-extrabold text-slate-900 mt-2">{{ $totales['ALUMNO'] }}</p>
            <p class="text-xs text-slate-500 mt-1">Notas de evolución individual</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:border-purple-300 transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase">Docentes</span>
                <span class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-handshake"></i>
                </span>
            </div>
            <p class="text-3xl font-extrabold text-slate-900 mt-2">{{ $totales['DOCENTE'] }}</p>
            <p class="text-xs text-slate-500 mt-1">Reuniones y acuerdos pedagógicos</p>
        </div>
    </div>

    <!-- Barra de Gran Total -->
    <div class="bg-indigo-900 text-white p-5 rounded-2xl shadow-sm flex flex-col sm:flex-row justify-between items-center gap-3">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-xl bg-indigo-800 flex items-center justify-center text-indigo-300 text-xl shadow">
                <i class="fa-solid fa-chart-line"></i>
            </div>
            <div>
                <p class="text-sm font-bold">Total Acumulado de Intervenciones en {{ $nombresMeses[$mes] ?? 'Mes' }} de {{ $anio }}</p>
                <p class="text-xs text-indigo-300">Suma consolidada de todas las atenciones institucionales registradas</p>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-4xl font-black text-amber-300">{{ $totalMes }}</span>
            <span class="text-xs text-indigo-200 uppercase font-semibold tracking-wider">intervenciones</span>
        </div>
    </div>

    <!-- Casos de Alta Prioridad con Nomenclatura Unificada (Ver Expediente) -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-rose-500 animate-pulse"></span>
                <span>Casos de Alta Prioridad (Seguimiento Mensual Activo)</span>
            </h3>
            <span class="text-xs text-slate-400 font-semibold">{{ $casosAltaPrioridad->count() }} alumnos en supervisión</span>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse($casosAltaPrioridad as $caso)
                @php
                    $atencionesEsteMes = $caso->atenciones->count();
                @endphp
                <div class="py-3.5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                    <div>
                        <div class="flex items-center gap-2">
                            <p class="font-bold text-slate-900 text-sm">{{ $caso->alumno->nombre_completo }}</p>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-700">
                                {{ $caso->grado_grupo }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Diagnóstico: <strong class="text-slate-700">{{ $caso->diagnostico }}</strong> • 
                            Docente: {{ $caso->docente_titular }}
                        </p>
                    </div>

                    <div class="flex items-center gap-2.5">
                        @if($atencionesEsteMes > 0)
                            <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold rounded-lg flex items-center gap-1">
                                <i class="fa-solid fa-check text-[10px]"></i> {{ $atencionesEsteMes }} este mes
                            </span>
                        @else
                            <span class="px-2.5 py-1 bg-rose-50 text-rose-700 border border-rose-200 text-xs font-bold rounded-lg flex items-center gap-1">
                                <i class="fa-solid fa-clock text-[10px]"></i> Pendiente
                            </span>
                        @endif

                        <!-- Nomenclatura unificada: Ver Expediente -->
                        <a href="{{ route('alumnos.show', $caso->id) }}" 
                           class="px-3.5 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-xl text-xs font-bold transition flex items-center gap-1.5">
                            <i class="fa-solid fa-folder-open text-xs"></i>
                            <span>Ver Expediente</span>
                        </a>
                    </div>
                </div>
            @empty
                <p class="text-xs text-slate-400 py-4 text-center">No hay alumnos marcados con Alta Prioridad en este ciclo.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
