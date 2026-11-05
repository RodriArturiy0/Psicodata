<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\ExpedienteCiclo;

class UpdateAlumnoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $expedienteId = $this->route('alumno') ?? $this->route('id');
        $expediente = ExpedienteCiclo::find($expedienteId);
        $alumnoId = $expediente?->alumno_id;

        return [
            'nombre_completo' => ['required', 'string', 'min:3', 'max:150'],
            'curp' => [
                'nullable', 
                'string', 
                'max:18', 
                $alumnoId ? Rule::unique('alumnos', 'curp')->ignore($alumnoId) : 'nullable'
            ],
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
            'estado_ciclo' => ['required', 'in:ACTIVO,PROMOVIDO,REPETIDOR,SECUNDARIA,ARCHIVO_MUERTO'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre_completo.required' => 'El nombre completo del estudiante es obligatorio.',
            'nombre_completo.min' => 'El nombre del estudiante debe contener al menos 3 caracteres.',
            'curp.unique' => 'La CURP especificada ya pertenece a otro estudiante registrado.',
            'grado_grupo.required' => 'El grado y grupo escolar es obligatorio.',
            'docente_titular.required' => 'El docente titular es obligatorio.',
            'diagnostico.required' => 'El diagnóstico psicopedagógico principal es obligatorio.',
            'prioridad.required' => 'El nivel de prioridad es obligatorio.',
            'estado_ciclo.required' => 'Debe indicar el estado administrativo del expediente en el ciclo.',
            'estado_ciclo.in' => 'El estado en el ciclo no es válido.',
            'fecha_nacimiento.date' => 'La fecha de nacimiento no tiene un formato válido.',
            'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior al día de hoy.',
            'fecha_ingreso_programa.date' => 'La fecha de ingreso debe tener un formato de fecha válido.',
        ];
    }
}

