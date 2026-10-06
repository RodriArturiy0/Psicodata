<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('atenciones_eventos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expediente_ciclo_id')->constrained('expedientes_ciclo')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('tipo_atencion', ['AULA', 'PADRES', 'ALUMNO', 'DOCENTE']);
            $table->dateTime('fecha_hora');
            $table->string('participantes', 200)->nullable();
            $table->text('motivo');
            $table->text('observaciones');
            $table->text('acuerdos')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('atenciones_eventos');
    }
};