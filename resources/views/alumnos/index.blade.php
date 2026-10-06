@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Expedientes Psicopedagógicos</h2>
            <p class="text-sm text-slate-500 mt-1">Ciclo Escolar 2026-2027</p>
        </div>
        <a href="{{ route('alumnos.create') }}" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold shadow-sm transition">
            + Nueva Ficha
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 text-xs font-bold uppercase text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4">Estudiante</th>
                    <th class="px-6 py-4">Grado / Docente</th>
                    <th class="px-6 py-4">Diagnóstico</th>
                    <th class="px-6 py-4">Prioridad</th>
                    <th class="px-6 py-4 text-right">Acción</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($expedientes as $exp)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="px-6 py-4 font-bold text-slate-800">{{ $exp->alumno->nombre_completo }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 bg-indigo-50 text-indigo-700 rounded font-bold text-xs">{{ $exp->grado_grupo }}</span>
                            <span class="text-xs text-slate-500 ml-1">{{ $exp->docente_titular }}</span>
                        </td>
                        <td class="px-6 py-4 text-xs">{{ $exp->diagnostico }}</td>
                        <td class="px-6 py-4">
                            @if($exp->prioridad == 'ALTA')
                                <span class="px-2 py-1 bg-rose-50 text-rose-700 border border-rose-200 text-xs font-bold rounded">Alta</span>
                            @else
                                <span class="px-2 py-1 bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold rounded">Trimestral</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('alumnos.show', $exp->id) }}" class="text-indigo-600 font-semibold text-xs hover:underline">Ver Historial</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection