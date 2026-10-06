<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AtencionEvento extends Model
{
    protected $table = 'atenciones_eventos';
    protected $fillable = [
        'expediente_ciclo_id', 'user_id', 'tipo_atencion',
        'fecha_hora', 'participantes', 'motivo', 'observaciones', 'acuerdos'
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
    ];

    public function expediente()
    {
        return $this->belongsTo(ExpedienteCiclo::class, 'expediente_ciclo_id');
    }
}