<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    protected $table = 'alumnos';
    protected $fillable = ['nombre_completo', 'curp', 'estatus_general'];

    public function expedientes()
    {
        return $this->hasMany(ExpedienteCiclo::class, 'alumno_id');
    }
}