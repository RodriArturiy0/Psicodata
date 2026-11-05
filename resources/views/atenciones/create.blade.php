@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Registrar Intervención Psicopedagógica</h2>
            <p class="text-xs text-slate-500 mt-1">Bitácora institucional de atenciones y acuerdos</p>
        </div>
        <a href="{{ route('dashboard') }}" class="text-xs text-slate-400 hover:text-slate-600 font-semibold">
            ✕ Cerrar
        </a>
    </div>

    @if($errors->any())
        <div class="mb-4 p-3.5 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('atenciones.store') }}" class="space-y-4 text-xs font-bold text-slate-700">
        @csrf
        <div>
            <label class="block mb-1">Estudiante *</label>
            <select name="expediente_ciclo_id" required class="w-full text-sm rounded-xl border border-slate-200 p-2.5 font-medium">
                @foreach($expedientes as $exp)
                    <option value="{{ $exp->id }}" {{ $expedienteId == $exp->id ? 'selected' : '' }}>
                        [{{ $exp->grado_grupo }}] {{ $exp->alumno->nombre_completo }} — {{ $exp->diagnostico }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
                <label class="block mb-1">Ámbito / Tipo *</label>
                <select name="tipo_atencion" required class="w-full text-xs rounded-xl border border-slate-200 p-2.5 font-medium">
                    <option value="AULA">Observación en Aula</option>
                    <option value="PADRES">Entrevista con Padres</option>
                    <option value="ALUMNO">Sesión con Alumno</option>
                    <option value="DOCENTE">Junta con Docente</option>
                </select>
            </div>
            <div>
                <label class="block mb-1">Duración (minutos)</label>
                <input type="number" name="duracion_minutos" min="5" max="240" value="{{ old('duracion_minutos', 30) }}" 
                       placeholder="30"
                       class="w-full text-xs rounded-xl border border-slate-200 p-2.5 font-normal">
            </div>
            <div>
                <label class="block mb-1">Fecha y Hora *</label>
                <input type="datetime-local" name="fecha_hora" value="{{ now()->format('Y-m-d\TH:i') }}" required 
                       class="w-full text-xs rounded-xl border border-slate-200 p-2.5 font-normal">
            </div>
        </div>

        <div>
            <label class="block mb-1">Participantes Presentes</label>
            <input type="text" name="participantes" value="{{ old('participantes') }}"
                   placeholder="Ej. Madre de familia, Psicopedagoga y Maestra titular..."
                   class="w-full text-xs rounded-xl border border-slate-200 p-2.5 font-normal">
        </div>

        <div>
            <label class="block mb-1">Motivo o Situación Tratada *</label>
            <input type="text" name="motivo" required value="{{ old('motivo') }}"
                   placeholder="Breve descripción del motivo de atención..." 
                   class="w-full text-sm rounded-xl border border-slate-200 p-2.5 font-normal">
        </div>

        <div>
            <label class="block mb-1">Observaciones Técnicas y Hechos *</label>
            <textarea name="observaciones" rows="3" required 
                      placeholder="Anotaciones clínicas, conducta observada, respuestas a consignas..."
                      class="w-full text-xs rounded-xl border border-slate-200 p-2.5 font-normal leading-relaxed">{{ old('observaciones') }}</textarea>
        </div>

        <div>
            <label class="block mb-1">Acuerdos, Compromisos y Fecha de Seguimiento</label>
            <textarea name="acuerdos" rows="2" 
                      placeholder="Tareas específicas asumidas por padres, docente o alumno..."
                      class="w-full text-xs rounded-xl border border-slate-200 p-2.5 font-normal">{{ old('acuerdos') }}</textarea>
        </div>

        <div class="pt-4 flex justify-between items-center">
            <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 rounded-xl text-slate-600 transition">
                Cancelar
            </a>
            <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow font-bold transition flex items-center gap-1.5">
                <i class="fa-solid fa-floppy-disk"></i>
                <span>Guardar Intervención</span>
            </button>
        </div>
    </form>
</div>
@endsection
