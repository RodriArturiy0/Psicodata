@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <!-- Tarjeta de Encabezado y Acciones Principales -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h2 class="text-2xl font-black text-slate-900">{{ $expediente->alumno->nombre_completo }}</h2>
                @if($expediente->estado_ciclo === 'ARCHIVO_MUERTO')
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-300">
                        Archivo Muerto (Baja)
                    </span>
                @elseif($expediente->estado_ciclo === 'SECUNDARIA')
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                        Egresado a Secundaria
                    </span>
                @else
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        ● Activo
                    </span>
                @endif
            </div>
            <p class="text-xs text-slate-500 mt-1">
                Grado: <strong class="text-indigo-700">{{ $expediente->grado_grupo }}</strong> • 
                Docente: <strong class="text-slate-700">{{ $expediente->docente_titular }}</strong> • 
                CURP: <span class="font-mono text-slate-600">{{ $expediente->alumno->curp ?? 'No registrada' }}</span> • 
                Ciclo: <span class="font-medium text-slate-600">{{ $expediente->ciclo->nombre_ciclo ?? '2026-2027' }}</span>
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            @if(auth()->user() && auth()->user()->puedeEditarExpedientes())
                <a href="{{ route('atenciones.create', ['expediente_id' => $expediente->id]) }}" 
                   class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-sm transition flex items-center gap-1.5">
                    <i class="fa-solid fa-plus"></i>
                    <span>+ Intervención</span>
                </a>
                <a href="{{ route('alumnos.edit', $expediente->id) }}" 
                   class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition flex items-center gap-1.5">
                    <i class="fa-solid fa-pen-to-square"></i>
                    <span>Editar Ficha</span>
                </a>
            @endif
            <a href="{{ route('alumnos.imprimir', $expediente->id) }}" target="_blank"
               class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-indigo-700 rounded-xl text-xs font-bold transition flex items-center gap-1.5" title="Exportar o Imprimir PDF">
                <i class="fa-solid fa-print"></i>
                <span>Imprimir / PDF</span>
            </a>
            <!-- Nomenclatura clara: Volver al Directorio -->
            <a href="{{ route('alumnos.index') }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition">
                ← Directorio
            </a>
        </div>
    </div>

    @if($expediente->estado_ciclo !== 'ACTIVO')
        <!-- Alerta de alumno en baja con opción de reactivar -->
        <div class="p-4 bg-amber-50 border border-amber-200 rounded-2xl flex items-center justify-between text-xs text-amber-900">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation text-base text-amber-600"></i>
                <span>Este estudiante se encuentra en estado <strong>{{ $expediente->estado_ciclo }}</strong> (inactivo en las métricas de ciclo).</span>
            </div>
            <form method="POST" action="{{ route('alumnos.reactivar', $expediente->id) }}">
                @csrf
                @method('PATCH')
                <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold transition">
                    Reactivar Estudiante
                </button>
            </form>
        </div>
    @endif

    <!-- Ficha Diagnóstica y Clínica Oficial Resumida -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-5">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                <i class="fa-solid fa-notes-medical text-indigo-600"></i>
                <span>Pauta y Ficha Psicopedagógica Oficial</span>
            </h3>
            <span class="text-[11px] text-slate-400 font-mono">ID: #EXP-{{ str_pad($expediente->id, 5, '0', STR_PAD_LEFT) }}</span>
        </div>

        <!-- Rubro 1: Datos de Identificación y Tutor -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-xs bg-slate-50 p-4 rounded-xl border border-slate-100">
            <div>
                <span class="text-slate-400 font-bold uppercase block text-[10px]">Tutor / Padre Responsable:</span>
                <span class="font-bold text-slate-800">{{ $expediente->alumno->tutor_nombre ?? 'No registrado' }}</span>
            </div>
            <div>
                <span class="text-slate-400 font-bold uppercase block text-[10px]">Teléfono de Contacto:</span>
                <span class="font-bold text-slate-800">{{ $expediente->alumno->tutor_telefono ?? 'No registrado' }}</span>
            </div>
            <div>
                <span class="text-slate-400 font-bold uppercase block text-[10px]">Fecha de Nacimiento:</span>
                <span class="font-bold text-slate-800">{{ $expediente->alumno->fecha_nacimiento ? \Carbon\Carbon::parse($expediente->alumno->fecha_nacimiento)->format('d/m/Y') : 'No registrada' }}</span>
            </div>
            <div>
                <span class="text-slate-400 font-bold uppercase block text-[10px]">Canalizado por:</span>
                <span class="font-bold text-indigo-700">{{ $expediente->canalizado_por ?? 'Docente titular' }}</span>
            </div>
        </div>

        <!-- Rubro 2: Diagnóstico, Área y Prioridad -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
            <div>
                <span class="text-slate-400 font-bold uppercase block text-[10px]">Diagnóstico Clínico / Educativo:</span>
                <span class="font-bold text-slate-900 text-sm mt-0.5 block">{{ $expediente->diagnostico }}</span>
                @if($expediente->area_atencion)
                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold bg-indigo-50 text-indigo-700 mt-1">
                        Área: {{ $expediente->area_atencion }}
                    </span>
                @endif
            </div>
            <div>
                <span class="text-slate-400 font-bold uppercase block text-[10px]">Prioridad de Supervisión:</span>
                @if($expediente->prioridad == 'ALTA')
                    <span class="inline-block px-2.5 py-1 bg-rose-50 text-rose-700 border border-rose-200 text-xs font-bold rounded-lg mt-0.5">
                        Alta Prioridad (Seguimiento Mensual Activo)
                    </span>
                @else
                    <span class="inline-block px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold rounded-lg mt-0.5">
                        Seguimiento Regular (Trimestral)
                    </span>
                @endif
            </div>
            <div>
                <span class="text-slate-400 font-bold uppercase block text-[10px]">Especialista Externo Tratante:</span>
                <span class="font-semibold text-slate-700 mt-0.5 block">{{ $expediente->especialista_externo ?? 'Sin especialista externo reportado' }}</span>
            </div>
        </div>

        <!-- Rubro 3: Adecuaciones, Medicación y Compromisos -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 pt-2 border-t border-slate-100 text-xs">
            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                <span class="text-slate-400 font-semibold block text-[10px]">Dictamen / Valoración Física:</span>
                <span class="font-bold text-slate-800">{{ $expediente->tiene_valoracion_fisica ? '✓ Cuenta con dictamen físico' : '✗ Sin dictamen físico' }}</span>
            </div>
            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                <span class="text-slate-400 font-semibold block text-[10px]">Tratamiento Farmacológico:</span>
                <span class="font-bold text-slate-800">{{ $expediente->tratamiento_farmacologico ? '✓ Sí' : '✗ No' }}</span>
                @if($expediente->tratamiento_farmacologico && $expediente->detalle_farmaco)
                    <p class="text-[11px] text-indigo-700 font-medium mt-1">{{ $expediente->detalle_farmaco }}</p>
                @endif
            </div>
            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                <span class="text-slate-400 font-semibold block text-[10px]">Adecuaciones Curriculares en Aula:</span>
                <span class="font-bold text-slate-800">{{ $expediente->requiere_adecuaciones ? '✓ Requiere adecuaciones' : '✗ No requeridas' }}</span>
                @if($expediente->requiere_adecuaciones && $expediente->detalle_adecuaciones)
                    <p class="text-[11px] text-indigo-700 font-medium mt-1">{{ $expediente->detalle_adecuaciones }}</p>
                @endif
            </div>
        </div>

        @if($expediente->compromisos_familia)
            <div class="p-3 bg-amber-50/70 rounded-xl border border-amber-200/60 text-xs">
                <span class="font-bold text-amber-900 block mb-0.5">Compromisos Oficiales de la Familia / Tutores:</span>
                <p class="text-amber-800 font-normal leading-relaxed">{{ $expediente->compromisos_familia }}</p>
            </div>
        @endif
    </div>

    <!-- Línea de Tiempo de Intervenciones -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-timeline text-indigo-600"></i>
                <span>Historial de Intervenciones y Bitácora</span>
            </h3>
            <span class="text-xs text-slate-400 font-semibold">{{ $expediente->atenciones->count() }} eventos registrados</span>
        </div>

        <div class="border-l-2 border-indigo-100 ml-4 space-y-6">
            @forelse($expediente->atenciones as $a)
                <div class="ml-6 relative">
                    <span class="absolute -left-[31px] mt-1 w-4 h-4 rounded-full 
                        @if($a->tipo_atencion == 'AULA') bg-amber-500
                        @elseif($a->tipo_atencion == 'PADRES') bg-blue-600
                        @elseif($a->tipo_atencion == 'ALUMNO') bg-emerald-600
                        @else bg-purple-600 @endif border-2 border-white shadow"></span>

                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                        <div class="flex justify-between items-center text-xs font-bold text-slate-700">
                            <span class="px-2 py-0.5 rounded text-[10px] uppercase
                                @if($a->tipo_atencion == 'AULA') bg-amber-100 text-amber-800
                                @elseif($a->tipo_atencion == 'PADRES') bg-blue-100 text-blue-800
                                @elseif($a->tipo_atencion == 'ALUMNO') bg-emerald-100 text-emerald-800
                                @else bg-purple-100 text-purple-800 @endif">
                                {{ $a->tipo_atencion }}
                            </span>
                            <span class="text-slate-400 font-normal">
                                {{ $a->fecha_hora->format('d/m/Y H:i') }} hrs
                                @if($a->duracion_minutos)
                                    • ({{ $a->duracion_minutos }} min)
                                @endif
                            </span>
                        </div>
                        <p class="text-xs font-bold text-slate-900 mt-2">{{ $a->motivo }}</p>
                        <p class="text-xs text-slate-600 mt-1 leading-relaxed">{{ $a->observaciones }}</p>
                        
                        @if($a->participantes)
                            <p class="text-[11px] text-slate-400 mt-1 italic">Participantes: {{ $a->participantes }}</p>
                        @endif

                        @if($a->acuerdos)
                            <div class="mt-2.5 p-2.5 bg-indigo-50/80 rounded-lg border border-indigo-100 text-xs font-semibold text-indigo-900">
                                <strong>Acuerdos / Compromisos:</strong> {{ $a->acuerdos }}
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-xs text-slate-400 ml-6">Sin intervenciones registradas en este ciclo escolar.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
