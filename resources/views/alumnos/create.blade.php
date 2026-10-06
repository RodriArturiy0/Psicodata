@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
    <h2 class="text-xl font-bold text-slate-900 mb-6">Alta de Expediente Psicopedagógico</h2>
    <form method="POST" action="{{ route('alumnos.store') }}" class="space-y-4 text-xs font-bold text-slate-700">
        @csrf
        <div>
            <label class="block mb-1">Nombre Completo del Estudiante *</label>
            <input type="text" name="nombre_completo" required class="w-full text-sm rounded-xl border border-slate-200 p-2.5">
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block mb-1">Grado y Grupo *</label>
                <input type="text" name="grado_grupo" placeholder="Ej. 2-A" required class="w-full text-sm rounded-xl border border-slate-200 p-2.5">
            </div>
            <div>
                <label class="block mb-1">Docente Titular *</label>
                <input type="text" name="docente_titular" required class="w-full text-sm rounded-xl border border-slate-200 p-2.5">
            </div>
        </div>
        <div>
            <label class="block mb-1">Diagnóstico *</label>
            <input type="text" name="diagnostico" required placeholder="Ej. TDAH, TEA..." class="w-full text-sm rounded-xl border border-slate-200 p-2.5">
        </div>
        <div>
            <label class="block mb-1">Prioridad *</label>
            <select name="prioridad" class="w-full text-sm rounded-xl border border-slate-200 p-2.5">
                <option value="SEGUIMIENTO">Seguimiento (Trimestral)</option>
                <option value="ALTA">Alta Prioridad (Mensual)</option>
            </select>
        </div>
        <div>
            <label class="block mb-1">Especialista Externo Tratante</label>
            <input type="text" name="especialista_externo" placeholder="Médico o terapeuta..." class="w-full text-sm rounded-xl border border-slate-200 p-2.5">
        </div>
        <div class="flex gap-4 pt-2">
            <label><input type="checkbox" name="tiene_valoracion_fisica" value="1" checked> Cuenta con valoración física</label>
            <label><input type="checkbox" name="tratamiento_farmacologico" value="1"> Tratamiento farmacológico</label>
        </div>
        <div class="pt-4 flex justify-end gap-2">
            <a href="{{ route('alumnos.index') }}" class="px-4 py-2 bg-slate-100 rounded-xl text-slate-600">Cancelar</a>
            <button type="submit" class="px-5 py-2 bg-indigo-600 text-white rounded-xl shadow">Guardar Ficha</button>
        </div>
    </form>
</div>
@endsection