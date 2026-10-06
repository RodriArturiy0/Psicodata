<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CicloEscolar extends Model
{
    protected $table = 'ciclos_escolares';
    protected $fillable = ['nombre_ciclo', 'fecha_inicio', 'fecha_fin', 'estado'];

    public function expedientes()
    {
        return $this->hasMany(ExpedienteCiclo::class, 'ciclo_id');
    }
}