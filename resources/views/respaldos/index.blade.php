@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Encabezado -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                    Seguridad Institucional
                </span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight mt-1">Copias de Seguridad y Respaldos</h1>
            <p class="text-xs text-slate-500 mt-1">
                Estrategia automatizada de respaldo para el resguardo de la información y la confidencialidad clínica en el servidor del Colegio Paidos.
            </p>
        </div>

        <form action="{{ route('respaldos.crear') }}" method="POST">
            @csrf
            <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold transition shadow-md shadow-indigo-100 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                </svg>
                <span>Generar Copia de Seguridad Ahora</span>
            </button>
        </form>
    </div>

    <!-- Mensajes Flash -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 text-sm rounded-r-xl font-medium shadow-sm flex items-center justify-between">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-800 text-sm rounded-r-xl font-medium shadow-sm flex items-center justify-between">
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Tarjetas de Estado -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Archivos de Respaldo</span>
            <div class="text-3xl font-extrabold text-slate-800 mt-2">{{ count($respaldos) }}</div>
            <p class="text-xs text-slate-500 mt-1">Copias preservadas en el servidor</p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Espacio en Disco Utilizado</span>
            <div class="text-3xl font-extrabold text-indigo-600 mt-2">{{ $totalMB }} <span class="text-sm font-normal text-slate-500">MB</span></div>
            <p class="text-xs text-slate-500 mt-1">En almacenamiento local <code>storage/app/backups</code></p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Última Copia Generada</span>
            <div class="text-lg font-bold text-slate-800 mt-2 truncate">
                {{ $ultimoRespaldo['fecha_relativa'] ?? 'Sin respaldos registrados' }}
            </div>
            <p class="text-xs text-slate-500 mt-1 truncate">
                {{ $ultimoRespaldo['nombre'] ?? 'Ejecute un respaldo inicial' }}
            </p>
        </div>
    </div>

    <!-- Lista de Respaldos -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Historial de Respaldos en el Servidor</h2>
            <span class="text-xs text-slate-500">Política de retención: Se conservan los 30 más recientes</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-[11px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                        <th class="py-3 px-6">Archivo SQL</th>
                        <th class="py-3 px-6">Fecha de Generación</th>
                        <th class="py-3 px-6">Tamaño</th>
                        <th class="py-3 px-6 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($respaldos as $res)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-4 px-6 font-mono text-slate-700 font-semibold flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ $res['nombre'] }}</span>
                            </td>
                            <td class="py-4 px-6 text-slate-600">
                                <div class="font-medium text-slate-800">{{ $res['fecha'] }}</div>
                                <span class="text-[11px] text-slate-400">{{ $res['fecha_relativa'] }}</span>
                            </td>
                            <td class="py-4 px-6 font-semibold text-slate-700">
                                {{ $res['tamano_kb'] }} KB
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('respaldos.descargar', $res['nombre']) }}" 
                                       class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-lg text-xs font-semibold transition border border-emerald-200 flex items-center gap-1"
                                       title="Descargar copia a unidad externa o USB">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                        <span>Descargar SQL</span>
                                    </a>

                                    <form action="{{ route('respaldos.eliminar', $res['nombre']) }}" method="POST" onsubmit="return confirm('¿Confirma que desea eliminar este archivo de respaldo de forma permanente?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 transition" title="Eliminar respaldo">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center text-slate-400">
                                <div class="max-w-xs mx-auto">
                                    <svg class="w-12 h-12 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 7v10c0 2 1 3 3 3h10c2 0 3-1 3-3V7M4 7c0-2 1-3 3-3h10c2 0 3 1 3 3M4 7h16m-5 4h.01M9 11h.01M9 15h.01M15 15h.01"/>
                                    </svg>
                                    <p class="font-medium text-slate-600 text-xs">No hay copias de seguridad generadas aún.</p>
                                    <p class="text-[11px] text-slate-400 mt-1">Presione "Generar Respaldo Ahora" para crear la primera copia completa.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Guía de Buenas Prácticas de Infraestructura -->
    <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-6">
        <h3 class="text-sm font-bold text-indigo-900 flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            Protocolo de Respaldo para el Servidor Físico del Colegio Paidos
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4 text-xs text-indigo-800">
            <div class="bg-white/80 p-3.5 rounded-xl border border-indigo-100/60">
                <p class="font-bold text-indigo-900">1. Respaldo Diario Automático</p>
                <p class="mt-1 text-slate-600">El servidor ejecuta la copia de seguridad cada noche a las 23:00 hrs programado en el sistema.</p>
            </div>
            <div class="bg-white/80 p-3.5 rounded-xl border border-indigo-100/60">
                <p class="font-bold text-indigo-900">2. Copia Fuera de Sitio (USB/Disco)</p>
                <p class="mt-1 text-slate-600">Se recomienda descargar semanalmente un archivo SQL a una memoria USB institucional resguardada en Dirección.</p>
            </div>
            <div class="bg-white/80 p-3.5 rounded-xl border border-indigo-100/60">
                <p class="font-bold text-indigo-900">3. Antes del Cierre de Ciclo</p>
                <p class="mt-1 text-slate-600">Siempre genere un respaldo manual antes de ejecutar el pase de grado ("Brincado") en el módulo de ciclos.</p>
            </div>
        </div>
    </div>
</div>
@endsection

