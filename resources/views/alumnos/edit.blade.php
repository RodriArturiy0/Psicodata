@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Encabezado de Edición -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Editar Expediente Psicopedagógico</h2>
            <p class="text-xs text-slate-500 mt-1">Estudiante: <span class="font-bold text-indigo-700">{{ $expediente->alumno->nombre_completo }}</span> • Ciclo: {{ $expediente->ciclo->nombre_ciclo ?? '2026-2027' }}</p>
        </div>
        <a href="{{ route('alumnos.show', $expediente->id) }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition">
            ← Volver al Expediente
        </a>
    </div>

    @if($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-xs space-y-1">
            <p class="font-bold text-rose-700 flex items-center gap-1.5">
                <i class="fa-solid fa-triangle-exclamation"></i> Corrige los siguientes errores:
            </p>
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Formulario de Edición Principal -->
    <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
        <form method="POST" action="{{ route('alumnos.update', $expediente->id) }}" class="space-y-6 text-xs font-bold text-slate-700">
            @csrf
            @method('PUT')

            <!-- Sección 1: Datos Personales e Identidad -->
            <div class="space-y-4 border-b border-slate-100 pb-6">
                <h3 class="text-sm font-extrabold text-indigo-900 flex items-center gap-2">
                    <i class="fa-solid fa-id-card text-indigo-600"></i>
                    <span>1. Ficha de Identificación del Alumno</span>
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label class="block mb-1">Nombre Completo del Estudiante *</label>
                        <input type="text" name="nombre_completo" required 
                               value="{{ old('nombre_completo', $expediente->alumno->nombre_completo) }}"
                               class="w-full text-sm rounded-xl border border-slate-200 p-2.5 focus:ring-2 focus:ring-indigo-500 font-normal">
                    </div>
                    <div>
                        <label class="block mb-1">CURP</label>
                        <input type="text" name="curp" maxlength="18"
                               value="{{ old('curp', $expediente->alumno->curp) }}"
                               placeholder="18 caracteres"
                               class="w-full text-sm rounded-xl border border-slate-200 p-2.5 uppercase font-mono font-normal">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block mb-1">Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nacimiento" 
                               value="{{ old('fecha_nacimiento', $expediente->alumno->fecha_nacimiento) }}"
                               class="w-full text-xs rounded-xl border border-slate-200 p-2.5 font-normal">
                    </div>
                    <div>
                        <label class="block mb-1">Nombre del Tutor / Padre Responsable</label>
                        <input type="text" name="tutor_nombre" 
                               value="{{ old('tutor_nombre', $expediente->alumno->tutor_nombre) }}"
                               placeholder="Padre, madre o tutor legal"
                               class="w-full text-xs rounded-xl border border-slate-200 p-2.5 font-normal">
                    </div>
                    <div>
                        <label class="block mb-1">Teléfono de Contacto / Emergencia</label>
                        <input type="text" name="tutor_telefono" 
                               value="{{ old('tutor_telefono', $expediente->alumno->tutor_telefono) }}"
                               placeholder="Ej. 981 123 4567"
                               class="w-full text-xs rounded-xl border border-slate-200 p-2.5 font-normal">
                    </div>
                </div>
            </div>

            <!-- Sección 2: Asignación Académica y Canalización -->
            <div class="space-y-4 border-b border-slate-100 pb-6">
                <h3 class="text-sm font-extrabold text-indigo-900 flex items-center gap-2">
                    <i class="fa-solid fa-graduation-cap text-indigo-600"></i>
                    <span>2. Asignación Académica y Canalización</span>
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-1">Grado y Grupo *</label>
                        <input type="text" name="grado_grupo" required 
                               value="{{ old('grado_grupo', $expediente->grado_grupo) }}"
                               placeholder="Ej. 2-A"
                               class="w-full text-sm rounded-xl border border-slate-200 p-2.5 font-normal">
                    </div>
                    <div>
                        <label class="block mb-1">Docente Titular a Cargo *</label>
                        <input type="text" name="docente_titular" required 
                               value="{{ old('docente_titular', $expediente->docente_titular) }}"
                               class="w-full text-sm rounded-xl border border-slate-200 p-2.5 font-normal">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-1">Canalizado / Derivado por:</label>
                        <select name="canalizado_por" class="w-full text-xs rounded-xl border border-slate-200 p-2.5 font-normal">
                            @php $canalizado = old('canalizado_por', $expediente->canalizado_por); @endphp
                            <option value="Docente titular" {{ $canalizado == 'Docente titular' ? 'selected' : '' }}>Docente Titular</option>
                            <option value="Dirección Escolar" {{ $canalizado == 'Dirección Escolar' ? 'selected' : '' }}>Dirección Escolar</option>
                            <option value="Solicitud de Padres" {{ $canalizado == 'Solicitud de Padres' ? 'selected' : '' }}>Solicitud de Padres de Familia</option>
                            <option value="Detección en Aula (Psicopedagogía)" {{ $canalizado == 'Detección en Aula (Psicopedagogía)' ? 'selected' : '' }}>Detección en Aula (Psicopedagogía)</option>
                            <option value="Dictamen Externo" {{ $canalizado == 'Dictamen Externo' ? 'selected' : '' }}>Dictamen / Informe Externo</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-1">Fecha de Ingreso a Psicopedagogía</label>
                        <input type="date" name="fecha_ingreso_programa" 
                               value="{{ old('fecha_ingreso_programa', $expediente->fecha_ingreso_programa) }}"
                               class="w-full text-xs rounded-xl border border-slate-200 p-2.5 font-normal">
                    </div>
                </div>
            </div>

            <!-- Sección 3: Perfil Clínico y Pauta de Adecuaciones -->
            <div class="space-y-4 border-b border-slate-100 pb-6">
                <h3 class="text-sm font-extrabold text-indigo-900 flex items-center gap-2">
                    <i class="fa-solid fa-stethoscope text-indigo-600"></i>
                    <span>3. Información Clínica y Adecuaciones</span>
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-1">Diagnóstico Psicopedagógico Principal *</label>
                        <input type="text" name="diagnostico" required 
                               value="{{ old('diagnostico', $expediente->diagnostico) }}"
                               placeholder="Ej. TEA Nivel 1, TDAH, Dislexia..."
                               class="w-full text-sm rounded-xl border border-slate-200 p-2.5 font-normal">
                    </div>
                    <div>
                        <label class="block mb-1">Área Principal de Intervención</label>
                        @php $area = old('area_atencion', $expediente->area_atencion); @endphp
                        <select name="area_atencion" class="w-full text-xs rounded-xl border border-slate-200 p-2.5 font-normal">
                            <option value="Socioemocional y Conductual" {{ $area == 'Socioemocional y Conductual' ? 'selected' : '' }}>Socioemocional y Conductual</option>
                            <option value="Cognitiva / Procesos de Aprendizaje" {{ $area == 'Cognitiva / Procesos de Aprendizaje' ? 'selected' : '' }}>Cognitiva / Procesos de Aprendizaje</option>
                            <option value="Lenguaje y Comunicación" {{ $area == 'Lenguaje y Comunicación' ? 'selected' : '' }}>Lenguaje y Comunicación</option>
                            <option value="Psicomotora / Sensorial" {{ $area == 'Psicomotora / Sensorial' ? 'selected' : '' }}>Psicomotora / Sensorial</option>
                            <option value="Atención Múltiple" {{ $area == 'Atención Múltiple' ? 'selected' : '' }}>Atención Múltiple Integral</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-1">Nivel de Prioridad *</label>
                        <select name="prioridad" class="w-full text-sm rounded-xl border border-slate-200 p-2.5 font-normal">
                            <option value="SEGUIMIENTO" {{ old('prioridad', $expediente->prioridad) === 'SEGUIMIENTO' ? 'selected' : '' }}>
                                Seguimiento Regular (Trimestral)
                            </option>
                            <option value="ALTA" {{ old('prioridad', $expediente->prioridad) === 'ALTA' ? 'selected' : '' }}>
                                Alta Prioridad (Seguimiento Mensual Activo)
                            </option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-1">Especialista Externo Tratante</label>
                        <input type="text" name="especialista_externo" 
                               value="{{ old('especialista_externo', $expediente->especialista_externo) }}"
                               placeholder="Médico neuropediatra, psicólogo clínico, terapeuta..."
                               class="w-full text-sm rounded-xl border border-slate-200 p-2.5 font-normal">
                    </div>
                </div>

                <!-- Checkboxes de valoraciones y fármacos -->
                <div class="bg-slate-50 p-4 rounded-xl space-y-3 border border-slate-200/80">
                    <div class="flex flex-wrap gap-6">
                        <label class="flex items-center gap-2 cursor-pointer font-semibold text-slate-700">
                            <input type="checkbox" name="tiene_valoracion_fisica" value="1" 
                                   {{ old('tiene_valoracion_fisica', $expediente->tiene_valoracion_fisica) ? 'checked' : '' }}
                                   class="w-4 h-4 text-indigo-600 rounded">
                            <span>Cuenta con expediente / dictamen médico físico</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer font-semibold text-slate-700">
                            <input type="checkbox" name="tratamiento_farmacologico" id="checkFarmaco" value="1" 
                                   {{ old('tratamiento_farmacologico', $expediente->tratamiento_farmacologico) ? 'checked' : '' }}
                                   onchange="document.getElementById('cajaDetalleFarmaco').classList.toggle('hidden', !this.checked)"
                                   class="w-4 h-4 text-indigo-600 rounded">
                            <span>Bajo tratamiento farmacológico</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer font-semibold text-slate-700">
                            <input type="checkbox" name="requiere_adecuaciones" id="checkAdecuaciones" value="1" 
                                   {{ old('requiere_adecuaciones', $expediente->requiere_adecuaciones) ? 'checked' : '' }}
                                   onchange="document.getElementById('cajaDetalleAdecuaciones').classList.toggle('hidden', !this.checked)"
                                   class="w-4 h-4 text-indigo-600 rounded">
                            <span>Requiere adecuaciones curriculares</span>
                        </label>
                    </div>

                    <!-- Detalle de Fármaco -->
                    <div id="cajaDetalleFarmaco" class="{{ old('tratamiento_farmacologico', $expediente->tratamiento_farmacologico) ? '' : 'hidden' }} pt-2">
                        <label class="block mb-1">Especificación del Medicamento y Dosificación Escolar:</label>
                        <input type="text" name="detalle_farmaco" 
                               value="{{ old('detalle_farmaco', $expediente->detalle_farmaco) }}"
                               placeholder="Ej. Metilfenidato 10mg / horario escolar..."
                               class="w-full text-xs rounded-xl border border-slate-200 p-2 font-normal bg-white">
                    </div>

                    <!-- Detalle de Adecuaciones -->
                    <div id="cajaDetalleAdecuaciones" class="{{ old('requiere_adecuaciones', $expediente->requiere_adecuaciones) ? '' : 'hidden' }} pt-2">
                        <label class="block mb-1">Pauta de Ajustes Razonables y Adecuaciones en Aula:</label>
                        <textarea name="detalle_adecuaciones" rows="2"
                                  placeholder="Ej. Material visual, tiempo adicional en evaluaciones, ubicación frente al pizarrón..."
                                  class="w-full text-xs rounded-xl border border-slate-200 p-2 font-normal bg-white">{{ old('detalle_adecuaciones', $expediente->detalle_adecuaciones) }}</textarea>
                    </div>
                </div>

                <div>
                    <label class="block mb-1">Compromisos Oficiales de la Familia / Tutor:</label>
                    <textarea name="compromisos_familia" rows="2"
                              placeholder="Acuerdos firmados por tutores: citas bimensuales, asistencia a terapias externas..."
                              class="w-full text-xs rounded-xl border border-slate-200 p-2 font-normal">{{ old('compromisos_familia', $expediente->compromisos_familia) }}</textarea>
                </div>
            </div>

            <!-- Sección 4: Estatus del Expediente en el Ciclo -->
            <div class="space-y-4">
                <h3 class="text-sm font-extrabold text-indigo-900 flex items-center gap-2">
                    <i class="fa-solid fa-flag text-indigo-600"></i>
                    <span>4. Estatus Administrativo del Expediente</span>
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-1">Estado en el Ciclo Escolar *</label>
                        <select name="estado_ciclo" class="w-full text-sm rounded-xl border border-slate-200 p-2.5 font-normal">
                            <option value="ACTIVO" {{ old('estado_ciclo', $expediente->estado_ciclo) === 'ACTIVO' ? 'selected' : '' }}>
                                ACTIVO (En seguimiento continuo)
                            </option>
                            <option value="PROMOVIDO" {{ old('estado_ciclo', $expediente->estado_ciclo) === 'PROMOVIDO' ? 'selected' : '' }}>
                                PROMOVIDO (Pasa al siguiente grado)
                            </option>
                            <option value="REPETIDOR" {{ old('estado_ciclo', $expediente->estado_ciclo) === 'REPETIDOR' ? 'selected' : '' }}>
                                REPETIDOR
                            </option>
                            <option value="SECUNDARIA" {{ old('estado_ciclo', $expediente->estado_ciclo) === 'SECUNDARIA' ? 'selected' : '' }}>
                                EGRESADO A SECUNDARIA
                            </option>
                            <option value="ARCHIVO_MUERTO" {{ old('estado_ciclo', $expediente->estado_ciclo) === 'ARCHIVO_MUERTO' ? 'selected' : '' }}>
                                ARCHIVO MUERTO (Baja institucional)
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="pt-6 flex justify-between items-center border-t border-slate-100">
                <a href="{{ route('alumnos.show', $expediente->id) }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-semibold transition">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-md font-bold transition flex items-center gap-2">
                    <i class="fa-solid fa-check"></i>
                    <span>Guardar Cambios</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Zona de Gestión de Bajas y Archivo Muerto -->
    <div class="bg-rose-50/60 p-6 rounded-2xl border border-rose-200/80 space-y-4">
        <div class="flex items-start justify-between">
            <div>
                <h3 class="text-sm font-bold text-rose-900 flex items-center gap-2">
                    <i class="fa-solid fa-user-xmark text-rose-600"></i>
                    <span>Módulo de Baja o Retiro de la Institución</span>
                </h3>
                <p class="text-xs text-rose-700 mt-1">
                    Si el estudiante egresó o fue dado de baja, puedes cambiar su estado a <strong>Archivo Muerto</strong> para retirarlo de los reportes activos pero preservar todo su historial clínico.
                </p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3 pt-2">
            <!-- Formulario de Baja Rápida -->
            <form method="POST" action="{{ route('alumnos.baja', $expediente->id) }}" class="flex items-center gap-2" onsubmit="return confirm('¿Confirmas que deseas dar de baja a este estudiante del ciclo activo?')">
                @csrf
                @method('PATCH')
                <select name="motivo_baja" class="text-xs font-semibold rounded-xl border border-rose-300 p-2 bg-white text-rose-900 focus:outline-none">
                    <option value="ARCHIVO_MUERTO">Dar de baja: Archivo Muerto</option>
                    <option value="SECUNDARIA">Egresó a Secundaria</option>
                    <option value="REPETIDOR">Repetidor</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                    Dar de Baja
                </button>
            </form>

            @if($expediente->estado_ciclo !== 'ACTIVO')
                <!-- Botón de Reactivación -->
                <form method="POST" action="{{ route('alumnos.reactivar', $expediente->id) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm transition flex items-center gap-1.5">
                        <i class="fa-solid fa-rotate-left"></i>
                        <span>Reactivar Estudiante</span>
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
