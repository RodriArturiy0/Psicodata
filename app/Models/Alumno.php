<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    protected $table = 'alumnos';
    protected $fillable = [
        'nombre_completo', 'curp', 'fecha_nacimiento', 
        'tutor_nombre', 'tutor_telefono', 'estatus_general'
    ];

    public function expedientes()
    {
        return $this->hasMany(ExpedienteCiclo::class, 'alumno_id');
    }
}
