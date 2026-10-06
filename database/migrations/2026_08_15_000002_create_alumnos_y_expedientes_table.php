<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alumnos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_completo', 150);
            $table->string('curp', 18)->nullable()->unique();
            $table->enum('estatus_general', ['ACTIVO', 'EGRESADO_SECUNDARIA', 'ARCHIVO_MUERTO'])->default('ACTIVO');
            $table->timestamps();
        });

        Schema::create('expedientes_ciclo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumno_id')->constrained('alumnos')->onDelete('cascade');
            $table->foreignId('ciclo_id')->constrained('ciclos_escolares')->onDelete('cascade');
            $table->string('grado_grupo', 10); // Ej: 2-A
            $table->string('docente_titular', 120);
            $table->string('diagnostico', 150); // TEA, TDAH, etc.
            $table->boolean('tiene_valoracion_fisica')->default(true);
            $table->boolean('tratamiento_farmacologico')->default(false);
            $table->string('detalle_farmaco', 150)->nullable();
            $table->boolean('requiere_adecuaciones')->default(false);
            $table->text('detalle_adecuaciones')->nullable();
            $table->string('especialista_externo', 150)->nullable();
            $table->enum('prioridad', ['ALTA', 'SEGUIMIENTO'])->default('SEGUIMIENTO');
            $table->enum('estado_ciclo', ['ACTIVO', 'PROMOVIDO', 'REPETIDOR', 'SECUNDARIA', 'ARCHIVO_MUERTO'])->default('ACTIVO');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expedientes_ciclo');
        Schema::dropIfExists('alumnos');
    }
};
