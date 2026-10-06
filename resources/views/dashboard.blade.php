@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Cierre Mensual de Actividades</h2>
            <p class="text-sm text-slate-500 mt-1">Conteo automatizado para entrega a Dirección</p>
        </div>
        <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-2 bg-white p-2 rounded-xl border border-slate-200 shadow-sm">
            <select name="mes" class="text-sm border-none bg-transparent font-medium text-slate-700">
                <option value="9" {{ $mes == 9 ? 'selected' : '' }}>Septiembre</option>
                <option value="10" {{ $mes == 10 ? 'selected' : '' }}>Octubre</option>
                <option value="11" {{ $mes == 11 ? 'selected' : '' }}>Noviembre</option>
            </select>
            <button type="submit" class="px-3 py-1.5 bg-indigo-600 text-white rounded-lg text-xs font-semibold">Ver Mes</button>
        </form>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-xs font-bold text-slate-400 uppercase">En Aula</span>
            <p class="text-3xl font-extrabold text-slate-800 mt-2">{{ $totales['AULA'] }}</p>
            <p class="text-xs text-slate-500 mt-1">Observaciones de clase</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-xs font-bold text-slate-400 uppercase">Padres de Familia</span>
            <p class="text-3xl font-extrabold text-slate-800 mt-2">{{ $totales['PADRES'] }}</p>
            <p class="text-xs text-slate-500 mt-1">Reportes de entrevista</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-xs font-bold text-slate-400 uppercase">Alumnos</span>
            <p class="text-3xl font-extrabold text-slate-800 mt-2">{{ $totales['ALUMNO'] }}</p>
            <p class="text-xs text-slate-500 mt-1">Notas de evolución</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-xs font-bold text-slate-400 uppercase">Docentes</span>
            <p class="text-3xl font-extrabold text-slate-800 mt-2">{{ $totales['DOCENTE'] }}</p>
            <p class="text-xs text-slate-500 mt-1">Reuniones de acuerdos</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
            Casos de Alta Prioridad (Seguimiento Mensual Activo)
        </h3>
        <div class="divide-y divide-slate-100">
            @foreach($casosAltaPrioridad as $caso)
                <div class="py-3 flex justify-between items-center">
                    <div>
                        <p class="font-bold text-slate-800 text-sm">{{ $caso->alumno->nombre_completo }} ({{ $caso->grado_grupo }})</p>
                        <p class="text-xs text-slate-500">Diagnóstico: {{ $caso->diagnostico }}</p>
                    </div>
                    <a href="{{ route('alumnos.show', $caso->id) }}" class="px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-lg text-xs font-bold">Ver Expediente</a>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection