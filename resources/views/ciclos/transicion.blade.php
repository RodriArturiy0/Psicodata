@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Encabezado y Advertencia Institucional -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-black text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-arrows-split-up-and-left text-indigo-600"></i>
                <span>Transición y Pase de Ciclo Escolar ("Brincado")</span>
            </h2>
            <p class="text-xs text-slate-500 mt-1">
                Cierre del ciclo actual <strong class="text-indigo-800">{{ $cicloActivo->nombre_ciclo }}</strong> e inicio del nuevo periodo lectivo
            </p>
        </div>
        <a href="{{ route('ciclos.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition">
            ← Cancelar
        </a>
    </div>

    <!-- Banner Informativo de Blindaje de Datos -->
    <div class="bg-indigo-900 text-white p-6 rounded-2xl shadow-sm space-y-2 border border-indigo-950">
        <h3 class="text-sm font-extrabold flex items-center gap-2 text-amber-300">
            <i class="fa-solid fa-shield-halved"></i>
            <span>Garantía de Inmutabilidad del Historial Clínico (Cero Pérdida de Datos)</span>
        </h3>
        <p class="text-xs text-indigo-100 leading-relaxed">
            Al ejecutar este proceso, el ciclo <strong>{{ $cicloActivo->nombre_ciclo }}</strong> pasará a estado <strong>CONGELADO</strong>. Ninguna nota de evolución, observación en aula ni acuerdo previo se borrará ni se sobrescribirá. Los alumnos promovidos y repetidores tendrán un nuevo expediente en el nuevo periodo que heredará automáticamente todo su historial clínico, adecuaciones curriculares y medicamentos.
        </p>
    </div>

    @if($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-xs space-y-1">
            <p class="font-bold text-rose-700 flex items-center gap-1.5">
                <i class="fa-solid fa-triangle-exclamation"></i> Por favor revisa los siguientes campos:
            </p>
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('ciclos.transicion.store') }}" class="space-y-6" onsubmit="return confirm('¿Confirmas que deseas cerrar y congelar el ciclo escolar actual e iniciar el nuevo periodo?')">
        @csrf

        <!-- Tarjeta 1: Parámetros del Nuevo Ciclo Escolar -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider flex items-center gap-2 border-b border-slate-100 pb-2">
                <i class="fa-solid fa-calendar-plus text-indigo-600"></i>
                <span>1. Configuración del Nuevo Periodo Escolar</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs font-bold text-slate-700">
                <div>
                    <label class="block mb-1">Nombre del Nuevo Ciclo *</label>
                    <input type="text" name="nombre_nuevo_ciclo" required 
                           value="{{ old('nombre_nuevo_ciclo', $siguienteCiclo) }}"
                           placeholder="Ej. 2027-2028"
                           class="w-full text-sm rounded-xl border border-slate-200 p-2.5 font-normal focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block mb-1">Fecha de Inicio *</label>
                    <input type="date" name="fecha_inicio" required 
                           value="{{ old('fecha_inicio', now()->addYear()->startOfYear()->addMonths(7)->format('Y-08-23')) }}"
                           class="w-full text-xs rounded-xl border border-slate-200 p-2.5 font-normal">
                </div>
                <div>
                    <label class="block mb-1">Fecha de Fin *</label>
                    <input type="date" name="fecha_fin" required 
                           value="{{ old('fecha_fin', now()->addYears(2)->startOfYear()->addMonths(6)->format('Y-07-09')) }}"
                           class="w-full text-xs rounded-xl border border-slate-200 p-2.5 font-normal">
                </div>
            </div>
        </div>

        <!-- Tarjeta 2: Pauta Individual de Pase de Grado y Casos Especiales -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-users-gear text-indigo-600"></i>
                        <span>2. Pauta Individual de Brincado y Manejo de Casos Especiales</span>
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Define la situación académica de cada alumno atendido en el ciclo</p>
                </div>
                <span class="text-xs text-slate-400 font-bold">{{ $expedientes->count() }} alumnos activos a procesar</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-[10px] font-extrabold uppercase text-slate-500 border-b border-slate-200">
                            <th class="p-3">Estudiante</th>
                            <th class="p-3">Grado Actual</th>
                            <th class="p-3">Acción de Pase de Grado *</th>
                            <th class="p-3">Grado en Nuevo Ciclo</th>
                            <th class="p-3">Docente Titular Asignado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($expedientes as $exp)
                            @php
                                $esSexto = preg_match('/^6/i', trim($exp->grado_grupo));
                                $siguienteGradoSugerido = $esSexto ? 'SECUNDARIA' : '';
                                if (!$esSexto && preg_match('/^(\d)(.*)$/', trim($exp->grado_grupo), $m)) {
                                    $siguienteGradoSugerido = ((int)$m[1] + 1) . $m[2];
                                }
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition" id="fila-{{ $exp->id }}">
                                <td class="p-3">
                                    <span class="font-bold text-slate-900 block">{{ $exp->alumno->nombre_completo }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $exp->diagnostico }}</span>
                                </td>
                                <td class="p-3">
                                    <span class="px-2 py-0.5 rounded font-bold bg-indigo-50 text-indigo-700 text-xs">
                                        {{ $exp->grado_grupo }}
                                    </span>
                                </td>
                                <td class="p-3">
                                    <select name="estudiantes[{{ $exp->id }}][accion]" 
                                            onchange="actualizarAccionEstudiante({{ $exp->id }}, this.value, '{{ $exp->grado_grupo }}', '{{ $siguienteGradoSugerido }}')"
                                            class="p-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-800 bg-white focus:ring-2 focus:ring-indigo-500">
                                        <option value="PROMOVER" {{ !$esSexto ? 'selected' : '' }}>✓ Promover (Pasa de Grado)</option>
                                        <option value="REPETIR">↻ Repetidor (Mismo Grado)</option>
                                        <option value="SECUNDARIA" {{ $esSexto ? 'selected' : '' }}>🎓 Egresado a Secundaria</option>
                                        <option value="ARCHIVO_MUERTO">✕ Archivo Muerto (Baja)</option>
                                    </select>
                                </td>
                                <td class="p-3">
                                    <input type="text" name="estudiantes[{{ $exp->id }}][nuevo_grado]" 
                                           id="grado-{{ $exp->id }}"
                                           value="{{ old("estudiantes.{$exp->id}.nuevo_grado", $siguienteGradoSugerido) }}"
                                           placeholder="Ej. 3-A"
                                           class="w-24 p-2 rounded-xl border border-slate-200 text-xs font-bold text-indigo-900 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-indigo-500">
                                </td>
                                <td class="p-3">
                                    <input type="text" name="estudiantes[{{ $exp->id }}][nuevo_docente]" 
                                           value="{{ old("estudiantes.{$exp->id}.nuevo_docente", 'Por asignar') }}"
                                           placeholder="Docente titular..."
                                           class="w-40 p-2 rounded-xl border border-slate-200 text-xs font-medium text-slate-700 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-indigo-500">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Botón de Ejecución -->
        <div class="pt-4 flex justify-between items-center">
            <a href="{{ route('ciclos.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-semibold transition text-xs">
                Cancelar
            </a>
            <button type="submit" class="px-7 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl shadow-lg shadow-indigo-600/30 font-bold transition flex items-center gap-2 text-sm">
                <i class="fa-solid fa-lock"></i>
                <span>Congelar Ciclo Actual y Brincar al Nuevo Periodo</span>
            </button>
        </div>
    </form>
</div>

<script>
    function actualizarAccionEstudiante(id, accion, gradoActual, gradoSiguiente) {
        const inputGrado = document.getElementById('grado-' + id);
        const fila = document.getElementById('fila-' + id);

        if (accion === 'PROMOVER') {
            inputGrado.value = gradoSiguiente;
            inputGrado.disabled = false;
            fila.classList.remove('opacity-50', 'bg-rose-50/40');
        } else if (accion === 'REPETIR') {
            inputGrado.value = gradoActual;
            inputGrado.disabled = false;
            fila.classList.remove('opacity-50', 'bg-rose-50/40');
        } else if (accion === 'SECUNDARIA') {
            inputGrado.value = 'SECUNDARIA';
            inputGrado.disabled = true;
            fila.classList.add('bg-blue-50/30');
        } else if (accion === 'ARCHIVO_MUERTO') {
            inputGrado.value = 'BAJA';
            inputGrado.disabled = true;
            fila.classList.add('opacity-50', 'bg-rose-50/40');
        }
    }
</script>
@endsection

