<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpedienteCiclo extends Model
{
    protected $table = 'expedientes_ciclo';
    protected $fillable = [
        'alumno_id', 'ciclo_id', 'grado_grupo', 'docente_titular',
        'diagnostico', 'tiene_valoracion_fisica', 'tratamiento_farmacologico',
        'detalle_farmaco', 'requiere_adecuaciones', 'detalle_adecuaciones',
        'especialista_externo', 'prioridad', 'estado_ciclo'
    ];

    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'alumno_id');
    }

    public function ciclo()
    {
        return $this->belongsTo(CicloEscolar::class, 'ciclo_id');
    }

    public function atenciones()
    {
        return $this->hasMany(AtencionEvento::class, 'expediente_ciclo_id')->orderBy('fecha_hora', 'desc');
    }
}