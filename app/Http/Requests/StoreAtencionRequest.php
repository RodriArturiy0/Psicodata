<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAtencionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'expediente_ciclo_id' => ['required', 'integer', 'exists:expedientes_ciclo,id'],
            'tipo_atencion' => ['required', 'in:AULA,PADRES,ALUMNO,DOCENTE'],
            'duracion_minutos' => ['nullable', 'integer', 'min:5', 'max:240'],
            'fecha_hora' => ['required', 'date'],
            'participantes' => ['nullable', 'string', 'max:200'],
            'motivo' => ['required', 'string', 'min:3', 'max:250'],
            'observaciones' => ['required', 'string', 'min:5'],
            'acuerdos' => ['nullable', 'string'],
            'estatus_compromiso' => ['nullable', 'in:EN_PROCESO,CUMPLIDO,PENDIENTE'],
        ];
    }

    public function messages(): array
    {
        return [
            'expediente_ciclo_id.required' => 'Debe seleccionar un expediente de estudiante válido.',
            'expediente_ciclo_id.exists' => 'El expediente seleccionado no existe en el sistema.',
            'tipo_atencion.required' => 'Debe seleccionar el ámbito de atención (Aula, Padres, Alumno o Docente).',
            'tipo_atencion.in' => 'El tipo de atención seleccionado no es válido.',
            'fecha_hora.required' => 'La fecha y hora de la intervención es obligatoria.',
            'fecha_hora.date' => 'El formato de fecha y hora no es válido.',
            'duracion_minutos.integer' => 'La duración debe ser un número entero de minutos.',
            'duracion_minutos.min' => 'La duración mínima debe ser de al menos 5 minutos.',
            'duracion_minutos.max' => 'La duración no puede superar los 240 minutos.',
            'motivo.required' => 'El motivo o situación tratada es obligatorio.',
            'motivo.min' => 'El motivo debe tener al menos 3 caracteres.',
            'observaciones.required' => 'Las observaciones técnicas y descripción de los hechos son obligatorias.',
            'observaciones.min' => 'Las observaciones deben contener al menos 5 caracteres descriptivos.',
        ];
    }
}

