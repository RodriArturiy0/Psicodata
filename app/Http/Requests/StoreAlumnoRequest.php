<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAlumnoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre_completo' => ['required', 'string', 'min:3', 'max:150'],
            'curp' => ['nullable', 'string', 'max:18', 'unique:alumnos,curp'],
            'fecha_nacimiento' => ['nullable', 'date', 'before:today'],
            'tutor_nombre' => ['nullable', 'string', 'max:150'],
            'tutor_telefono' => ['nullable', 'string', 'max:30'],
            'grado_grupo' => ['required', 'string', 'max:10'],
            'docente_titular' => ['required', 'string', 'max:120'],
            'canalizado_por' => ['nullable', 'string', 'max:100'],
            'fecha_ingreso_programa' => ['nullable', 'date'],
            'diagnostico' => ['required', 'string', 'max:150'],
            'area_atencion' => ['nullable', 'string', 'max:100'],
            'tiene_valoracion_fisica' => ['nullable', 'boolean'],
            'tratamiento_farmacologico' => ['nullable', 'boolean'],
            'detalle_farmaco' => ['nullable', 'string', 'max:150'],
            'requiere_adecuaciones' => ['nullable', 'boolean'],
            'detalle_adecuaciones' => ['nullable', 'string'],
            'compromisos_familia' => ['nullable', 'string'],
            'especialista_externo' => ['nullable', 'string', 'max:150'],
            'prioridad' => ['required', 'in:ALTA,SEGUIMIENTO'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre_completo.required' => 'El nombre completo del estudiante es obligatorio.',
            'nombre_completo.min' => 'El nombre del estudiante debe contener al menos 3 caracteres.',
            'curp.unique' => 'La CURP especificada ya se encuentra registrada para otro estudiante.',
            'fecha_nacimiento.date' => 'La fecha de nacimiento debe tener un formato de fecha válido.',
            'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser una fecha anterior al día de hoy.',
            'grado_grupo.required' => 'El grado y grupo escolar es obligatorio (ej. 2-A).',
            'docente_titular.required' => 'El nombre del docente titular a cargo es obligatorio.',
            'diagnostico.required' => 'El diagnóstico psicopedagógico principal es obligatorio.',
            'prioridad.required' => 'Debe seleccionar el nivel de prioridad de atención.',
            'prioridad.in' => 'El nivel de prioridad debe ser ALTA o SEGUIMIENTO.',
            'fecha_ingreso_programa.date' => 'La fecha de ingreso debe tener un formato de fecha válido.',
        ];
    }
}

