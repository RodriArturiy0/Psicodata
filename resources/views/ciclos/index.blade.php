@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <!-- Encabezado -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Periodos y Ciclos Escolares</h2>
            <p class="text-sm text-slate-500 mt-1">Gestión de vigencias, historiales congelados y brincado de grado</p>
        </div>

        @if($cicloActivo)
            <a href="{{ route('ciclos.transicion') }}" 
               class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-bold shadow-sm transition flex items-center gap-2">
                <i class="fa-solid fa-arrows-split-up-and-left"></i>
                <span>Cierre de Ciclo y Brincado de Grado</span>
            </a>
        @endif
    </div>

    <!-- Lista de Ciclos -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        @foreach($ciclos as $c)
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden space-y-4">
                @if($c->estado === 'ACTIVO')
                    <div class="absolute top-0 right-0 bg-emerald-500 text-white text-[10px] font-black uppercase px-3 py-1 rounded-bl-xl tracking-wider">
                        Ciclo Activo Vigente
                    </div>
                @elseif($c->estado === 'CONGELADO')
                    <div class="absolute top-0 right-0 bg-slate-400 text-white text-[10px] font-black uppercase px-3 py-1 rounded-bl-xl tracking-wider flex items-center gap-1">
                        <i class="fa-solid fa-lock text-[9px]"></i> Historial Congelado
                    </div>
                @endif

                <div>
                    <h3 class="text-xl font-black text-slate-900">{{ $c->nombre_ciclo }}</h3>
                    <p class="text-xs text-slate-500 mt-1">
                        Vigencia: {{ \Carbon\Carbon::parse($c->fecha_inicio)->format('d/m/Y') }} — {{ \Carbon\Carbon::parse($c->fecha_fin)->format('d/m/Y') }}
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-3 pt-3 border-t border-slate-100 text-xs">
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase block">Expedientes Totales:</span>
                        <span class="text-base font-extrabold text-slate-800">{{ $c->total_expedientes }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase block">Activos en Periodo:</span>
                        <span class="text-base font-extrabold text-indigo-700">{{ $c->activos_expedientes }}</span>
                    </div>
                </div>

                <div class="pt-2 flex justify-between items-center text-xs">
                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold
                        @if($c->estado === 'ACTIVO') bg-emerald-50 text-emerald-700 border border-emerald-200
                        @elseif($c->estado === 'CONGELADO') bg-slate-100 text-slate-600 border border-slate-300
                        @else bg-amber-50 text-amber-700 @endif">
                        Estado: {{ $c->estado }}
                    </span>

                    @if($c->estado === 'ACTIVO')
                        <a href="{{ route('ciclos.transicion') }}" class="text-indigo-600 font-bold hover:underline">
                            Realizar Brincado →
                        </a>
                    @else
                        <span class="text-slate-400 text-[11px] italic">Inmovilizado (Solo lectura)</span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

