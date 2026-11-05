<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransicionCicloRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre_nuevo_ciclo' => ['required', 'string', 'max:20', 'unique:ciclos_escolares,nombre_ciclo'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date', 'after:fecha_inicio'],
            'estudiantes' => ['required', 'array'],
            'estudiantes.*.accion' => ['required', 'in:PROMOVER,REPETIR,SECUNDARIA,ARCHIVO_MUERTO'],
            'estudiantes.*.nuevo_grado' => ['nullable', 'string', 'max:10'],
            'estudiantes.*.nuevo_docente' => ['nullable', 'string', 'max:120'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre_nuevo_ciclo.required' => 'Debe ingresar el nombre del nuevo ciclo escolar (ej. 2027-2028).',
            'nombre_nuevo_ciclo.unique' => 'Ya existe un ciclo escolar registrado con ese nombre.',
            'fecha_inicio.required' => 'La fecha de inicio del nuevo ciclo es obligatoria.',
            'fecha_inicio.date' => 'La fecha de inicio debe tener un formato válido.',
            'fecha_fin.required' => 'La fecha de fin del nuevo ciclo es obligatoria.',
            'fecha_fin.after' => 'La fecha de fin debe ser posterior a la fecha de inicio.',
            'estudiantes.required' => 'No se encontraron expedientes para transicionar.',
        ];
    }
}

