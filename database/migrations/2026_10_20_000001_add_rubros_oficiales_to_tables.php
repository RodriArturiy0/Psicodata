<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('alumnos', function (Blueprint $table) {
            if (!Schema::hasColumn('alumnos', 'fecha_nacimiento')) {
                $table->date('fecha_nacimiento')->nullable()->after('curp');
            }
            if (!Schema::hasColumn('alumnos', 'tutor_nombre')) {
                $table->string('tutor_nombre', 150)->nullable()->after('fecha_nacimiento');
            }
            if (!Schema::hasColumn('alumnos', 'tutor_telefono')) {
                $table->string('tutor_telefono', 30)->nullable()->after('tutor_nombre');
            }
        });

        Schema::table('expedientes_ciclo', function (Blueprint $table) {
            if (!Schema::hasColumn('expedientes_ciclo', 'canalizado_por')) {
                $table->string('canalizado_por', 100)->nullable()->after('docente_titular');
            }
            if (!Schema::hasColumn('expedientes_ciclo', 'fecha_ingreso_programa')) {
                $table->date('fecha_ingreso_programa')->nullable()->after('canalizado_por');
            }
            if (!Schema::hasColumn('expedientes_ciclo', 'area_atencion')) {
                $table->string('area_atencion', 100)->nullable()->after('diagnostico');
            }
            if (!Schema::hasColumn('expedientes_ciclo', 'compromisos_familia')) {
                $table->text('compromisos_familia')->nullable()->after('detalle_adecuaciones');
            }
        });

        Schema::table('atenciones_eventos', function (Blueprint $table) {
            if (!Schema::hasColumn('atenciones_eventos', 'duracion_minutos')) {
                $table->unsignedSmallInteger('duracion_minutos')->nullable()->after('tipo_atencion');
            }
            if (!Schema::hasColumn('atenciones_eventos', 'estatus_compromiso')) {
                $table->string('estatus_compromiso', 50)->nullable()->default('EN_PROCESO')->after('acuerdos');
            }
        });
    }

    public function down(): void
    {
        Schema::table('alumnos', function (Blueprint $table) {
            $table->dropColumn(['fecha_nacimiento', 'tutor_nombre', 'tutor_telefono']);
        });

        Schema::table('expedientes_ciclo', function (Blueprint $table) {
            $table->dropColumn(['canalizado_por', 'fecha_ingreso_programa', 'area_atencion', 'compromisos_familia']);
        });

        Schema::table('atenciones_eventos', function (Blueprint $table) {
            $table->dropColumn(['duracion_minutos', 'estatus_compromiso']);
        });
    }
};

