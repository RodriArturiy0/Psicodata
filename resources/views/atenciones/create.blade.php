@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
    <h2 class="text-xl font-bold text-slate-900 mb-6">Registrar Intervención Psicopedagógica</h2>
    <form method="POST" action="{{ route('atenciones.store') }}" class="space-y-4 text-xs font-bold text-slate-700">
        @csrf
        <div>
            <label class="block mb-1">Estudiante *</label>
            <select name="expediente_ciclo_id" required class="w-full text-sm rounded-xl border border-slate-200 p-2.5">
                @foreach($expedientes as $exp)
                    <option value="{{ $exp->id }}" {{ $expedienteId == $exp->id ? 'selected' : '' }}>
                        {{ $exp->alumno->nombre_completo }} ({{ $exp->grado_grupo }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block mb-1">Tipo de Intervención *</label>
                <select name="tipo_atencion" required class="w-full text-sm rounded-xl border border-slate-200 p-2.5">
                    <option value="AULA">Observación en Aula</option>
                    <option value="PADRES">Reporte de Entrevista (Padres)</option>
                    <option value="ALUMNO">Nota de Evolución (Alumno)</option>
                    <option value="DOCENTE">Reunión con Docente</option>
                </select>
            </div>
            <div>
                <label class="block mb-1">Fecha y Hora *</label>
                <input type="datetime-local" name="fecha_hora" value="{{ now()->format('Y-m-d\TH:i') }}" required class="w-full text-sm rounded-xl border border-slate-200 p-2.5">
            </div>
        </div>
        <div>
            <label class="block mb-1">Motivo de la Atención *</label>
            <input type="text" name="motivo" required placeholder="Motivo o situación tratada..." class="w-full text-sm rounded-xl border border-slate-200 p-2.5">
        </div>
        <div>
            <label class="block mb-1">Observaciones Detalladas *</label>
            <textarea name="observaciones" rows="3" required class="w-full text-sm rounded-xl border border-slate-200 p-2.5"></textarea>
        </div>
        <div>
            <label class="block mb-1">Acuerdos o Compromisos</label>
            <textarea name="acuerdos" rows="2" class="w-full text-sm rounded-xl border border-slate-200 p-2.5"></textarea>
        </div>
        <div class="pt-4 flex justify-end gap-2">
            <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-slate-100 rounded-xl text-slate-600">Cancelar</a>
            <button type="submit" class="px-5 py-2 bg-indigo-600 text-white rounded-xl shadow">Guardar Intervención</button>
        </div>
    </form>
</div>
@endsection