@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">{{ $expediente->alumno->nombre_completo }}</h2>
            <p class="text-xs text-slate-500 mt-1">{{ $expediente->grado_grupo }} • Docente: {{ $expediente->docente_titular }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('atenciones.create', ['expediente_id' => $expediente->id]) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-semibold">
                + Nueva Intervención
            </a>
            <a href="{{ route('alumnos.index') }}" class="px-3 py-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-semibold">Volver</a>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <h3 class="text-lg font-bold text-slate-900 mb-4">Línea de Tiempo de Intervenciones</h3>
        <div class="border-l-2 border-indigo-100 ml-4 space-y-6">
            @forelse($expediente->atenciones as $a)
                <div class="ml-6 relative">
                    <span class="absolute -left-[31px] mt-1 w-4 h-4 rounded-full bg-indigo-600 border-2 border-white shadow"></span>
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                        <div class="flex justify-between items-center text-xs font-bold text-slate-700">
                            <span>{{ $a->tipo_atencion }}</span>
                            <span class="text-slate-400 font-normal">{{ $a->fecha_hora->format('d/m/Y H:i') }}</span>
                        </div>
                        <p class="text-xs font-semibold text-slate-800 mt-2">{{ $a->motivo }}</p>
                        <p class="text-xs text-slate-600 mt-1">{{ $a->observaciones }}</p>
                        @if($a->acuerdos)
                            <p class="text-xs text-indigo-700 mt-2 font-semibold">Acuerdos: {{ $a->acuerdos }}</p>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-xs text-slate-400 ml-6">Sin intervenciones registradas en este ciclo.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection