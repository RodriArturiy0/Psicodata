@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Encabezado -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Alta de Expediente Psicopedagógico</h2>
            <p class="text-xs text-slate-500 mt-1">Formato oficial institucional • Colegio Paidos Campeche</p>
        </div>
        <a href="{{ route('alumnos.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition">
            ← Volver al Directorio
        </a>
    </div>

    @if($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-xs space-y-1">
            <p class="font-bold text-rose-700 flex items-center gap-1.5">
                <i class="fa-solid fa-triangle-exclamation"></i> Por favor corrige los siguientes campos:
            </p>
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
        <form method="POST" action="{{ route('alumnos.store') }}" class="space-y-6 text-xs font-bold text-slate-700">
            @csrf

            <!-- Rubro 1: Datos de Identificación del Estudiante -->
            <div class="space-y-4 border-b border-slate-100 pb-6">
                <h3 class="text-sm font-extrabold text-indigo-900 flex items-center gap-2">
                    <i class="fa-solid fa-id-card text-indigo-600"></i>
                    <span>1. Ficha de Identificación del Alumno</span>
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label class="block mb-1">Nombre Completo del Estudiante *</label>
                        <input type="text" name="nombre_completo" required value="{{ old('nombre_completo') }}"
                               placeholder="Nombre(s) y Apellidos"
                               class="w-full text-sm rounded-xl border border-slate-200 p-2.5 font-normal focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block mb-1">CURP</label>
                        <input type="text" name="curp" maxlength="18" value="{{ old('curp') }}"
                               placeholder="18 caracteres"
                               class="w-full text-sm rounded-xl border border-slate-200 p-2.5 font-mono uppercase font-normal focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block mb-1">Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}"
                               class="w-full text-xs rounded-xl border border-slate-200 p-2.5 font-normal">
                    </div>
                    <div>
                        <label class="block mb-1">Nombre del Tutor / Padre Responsable</label>
                        <input type="text" name="tutor_nombre" value="{{ old('tutor_nombre') }}"
                               placeholder="Padre, madre o tutor legal"
                               class="w-full text-xs rounded-xl border border-slate-200 p-2.5 font-normal">
                    </div>
                    <div>
                        <label class="block mb-1">Teléfono de Contacto / Emergencia</label>
                        <input type="text" name="tutor_telefono" value="{{ old('tutor_telefono') }}"
                               placeholder="Ej. 981 123 4567"
                               class="w-full text-xs rounded-xl border border-slate-200 p-2.5 font-normal">
                    </div>
                </div>
            </div>

            <!-- Rubro 2: Asignación Académica y Canalización -->
            <div class="space-y-4 border-b border-slate-100 pb-6">
                <h3 class="text-sm font-extrabold text-indigo-900 flex items-center gap-2">
                    <i class="fa-solid fa-graduation-cap text-indigo-600"></i>
                    <span>2. Datos Escolares y Motivo de Canalización</span>
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-1">Grado y Grupo *</label>
                        <input type="text" name="grado_grupo" required value="{{ old('grado_grupo') }}"
                               placeholder="Ej. 2-A"
                               class="w-full text-sm rounded-xl border border-slate-200 p-2.5 font-normal">
                    </div>
                    <div>
                        <label class="block mb-1">Docente Titular a Cargo *</label>
                        <input type="text" name="docente_titular" required value="{{ old('docente_titular') }}"
                               placeholder="Nombre del maestro(a) titular"
                               class="w-full text-sm rounded-xl border border-slate-200 p-2.5 font-normal">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-1">Canalizado / Derivado por:</label>
                        <select name="canalizado_por" class="w-full text-xs rounded-xl border border-slate-200 p-2.5 font-normal">
                            <option value="Docente titular">Docente Titular</option>
                            <option value="Dirección Escolar">Dirección Escolar</option>
                            <option value="Solicitud de Padres">Solicitud de Padres de Familia</option>
                            <option value="Detección en Aula (Psicopedagogía)">Detección en Aula (Psicopedagogía)</option>
                            <option value="Dictamen Externo">Dictamen / Informe Externo</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-1">Fecha de Ingreso a Psicopedagogía</label>
                        <input type="date" name="fecha_ingreso_programa" value="{{ old('fecha_ingreso_programa', now()->format('Y-m-d')) }}"
                               class="w-full text-xs rounded-xl border border-slate-200 p-2.5 font-normal">
                    </div>
                </div>
            </div>

            <!-- Rubro 3: Condición Diagnóstica y Pauta Pedagógica -->
            <div class="space-y-4 border-b border-slate-100 pb-6">
                <h3 class="text-sm font-extrabold text-indigo-900 flex items-center gap-2">
                    <i class="fa-solid fa-stethoscope text-indigo-600"></i>
                    <span>3. Pauta Diagnóstica y Adecuaciones Oficiales</span>
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-1">Diagnóstico Principal *</label>
                        <input type="text" name="diagnostico" required value="{{ old('diagnostico') }}"
                               placeholder="Ej. TDAH, TEA Nivel 1, Trastorno de Lenguaje..."
                               class="w-full text-sm rounded-xl border border-slate-200 p-2.5 font-normal">
                    </div>
                    <div>
                        <label class="block mb-1">Área Principal de Intervención</label>
                        <select name="area_atencion" class="w-full text-xs rounded-xl border border-slate-200 p-2.5 font-normal">
                            <option value="Socioemocional y Conductual">Socioemocional y Conductual</option>
                            <option value="Cognitiva / Procesos de Aprendizaje">Cognitiva / Procesos de Aprendizaje</option>
                            <option value="Lenguaje y Comunicación">Lenguaje y Comunicación</option>
                            <option value="Psicomotora / Sensorial">Psicomotora / Sensorial</option>
                            <option value="Atención Múltiple">Atención Múltiple Integral</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-1">Nivel de Prioridad de Supervisión *</label>
                        <select name="prioridad" class="w-full text-sm rounded-xl border border-slate-200 p-2.5 font-normal">
                            <option value="SEGUIMIENTO">Seguimiento Regular (Trimestral)</option>
                            <option value="ALTA">Alta Prioridad (Seguimiento Mensual Activo)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-1">Especialista Externo Tratante</label>
                        <input type="text" name="especialista_externo" value="{{ old('especialista_externo') }}"
                               placeholder="Neuropediatra, terapeuta, psicólogo externo..."
                               class="w-full text-sm rounded-xl border border-slate-200 p-2.5 font-normal">
                    </div>
                </div>

                <!-- Checkboxes de Apoyos Clínicos -->
                <div class="bg-slate-50 p-4 rounded-xl space-y-3 border border-slate-200/80">
                    <div class="flex flex-wrap gap-6">
                        <label class="flex items-center gap-2 cursor-pointer font-semibold text-slate-700">
                            <input type="checkbox" name="tiene_valoracion_fisica" value="1" checked class="w-4 h-4 text-indigo-600 rounded">
                            <span>Cuenta con expediente / dictamen médico físico</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer font-semibold text-slate-700">
                            <input type="checkbox" name="tratamiento_farmacologico" id="checkFarmacoCreate" value="1" 
                                   onchange="document.getElementById('cajaDetalleFarmacoCreate').classList.toggle('hidden', !this.checked)"
                                   class="w-4 h-4 text-indigo-600 rounded">
                            <span>Bajo tratamiento farmacológico</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer font-semibold text-slate-700">
                            <input type="checkbox" name="requiere_adecuaciones" id="checkAdecuacionesCreate" value="1" 
                                   onchange="document.getElementById('cajaDetalleAdecuacionesCreate').classList.toggle('hidden', !this.checked)"
                                   class="w-4 h-4 text-indigo-600 rounded">
                            <span>Requiere adecuaciones curriculares</span>
                        </label>
                    </div>

                    <!-- Detalle de Fármaco -->
                    <div id="cajaDetalleFarmacoCreate" class="hidden pt-2">
                        <label class="block mb-1">Especificación del Medicamento y Dosificación Escolar:</label>
                        <input type="text" name="detalle_farmaco" value="{{ old('detalle_farmaco') }}"
                               placeholder="Nombre del medicamento, dosis y horario..."
                               class="w-full text-xs rounded-xl border border-slate-200 p-2 font-normal bg-white">
                    </div>

                    <!-- Detalle de Adecuaciones -->
                    <div id="cajaDetalleAdecuacionesCreate" class="hidden pt-2">
                        <label class="block mb-1">Pauta de Ajustes Razonables y Adecuaciones en Aula:</label>
                        <textarea name="detalle_adecuaciones" rows="2"
                                  placeholder="Ej. Ubicación frente al pizarrón, pausas activas cada 20 min, material visual estructurado, tiempo adicional en evaluaciones..."
                                  class="w-full text-xs rounded-xl border border-slate-200 p-2 font-normal bg-white">{{ old('detalle_adecuaciones') }}</textarea>
                    </div>
                </div>

                <div>
                    <label class="block mb-1">Compromisos Oficiales de la Familia / Tutor:</label>
                    <textarea name="compromisos_familia" rows="2"
                              placeholder="Acuerdos firmados por tutores: citas bimensuales, asistencia a terapias externas, seguimiento de tareas en casa..."
                              class="w-full text-xs rounded-xl border border-slate-200 p-2 font-normal">{{ old('compromisos_familia') }}</textarea>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="pt-4 flex justify-between items-center">
                <a href="{{ route('alumnos.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-semibold transition">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-md font-bold transition flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Guardar Ficha Oficial</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
