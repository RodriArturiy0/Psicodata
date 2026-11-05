<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMovilAulaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'expediente_ciclo_id' => ['required', 'integer', 'exists:expedientes_ciclo,id'],
            'duracion_minutos' => ['nullable', 'integer', 'min:5', 'max:240'],
            'fecha_hora' => ['required', 'date'],
            'motivo' => ['required', 'string', 'min:3', 'max:250'],
            'observaciones' => ['required', 'string', 'min:5'],
            'acuerdos' => ['nullable', 'string'],
            'estatus_compromiso' => ['nullable', 'in:EN_PROCESO,CUMPLIDO,PENDIENTE'],
            'accion_siguiente' => ['nullable', 'in:seguir_aula,ver_expediente'],
        ];
    }

    public function messages(): array
    {
        return [
            'expediente_ciclo_id.required' => 'Debe seleccionar al estudiante observado en el aula.',
            'expediente_ciclo_id.exists' => 'El expediente del estudiante no fue encontrado.',
            'fecha_hora.required' => 'La fecha y hora de la observación es obligatoria.',
            'fecha_hora.date' => 'El formato de fecha y hora es incorrecto.',
            'motivo.required' => 'El motivo de la observación en aula es obligatorio.',
            'motivo.min' => 'El motivo debe tener al menos 3 caracteres.',
            'observaciones.required' => 'Debe ingresar el detalle de lo observado en clase.',
            'observaciones.min' => 'Las observaciones deben contener al menos 5 caracteres.',
        ];
    }
}

