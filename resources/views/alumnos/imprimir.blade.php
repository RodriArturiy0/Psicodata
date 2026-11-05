<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Expediente {{ $expediente->alumno->nombre_completo }} - Colegio Paidos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @page {
            size: letter;
            margin: 15mm 15mm 15mm 15mm;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                color: #0f172a !important;
                padding: 0 !important;
            }
            .page-break-inside-avoid {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 p-6 md:p-10 font-sans">

    <!-- Barra Superior de Acciones (No se imprime) -->
    <div class="no-print max-w-4xl mx-auto mb-6 flex items-center justify-between bg-white p-4 rounded-2xl border border-slate-200 shadow-md">
        <!-- Nomenclatura unificada: Volver al Expediente -->
        <a href="{{ route('alumnos.show', $expediente->id) }}" class="flex items-center gap-2 text-xs font-bold text-slate-600 hover:text-indigo-600 transition">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Volver al Expediente en Sistema</span>
        </a>
        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-sm flex items-center gap-2 transition active:scale-95">
                <i class="fa-solid fa-print"></i>
                <span>Imprimir / Guardar como PDF</span>
            </button>
        </div>
    </div>

    <!-- Hoja Formal de Expediente (Formato Carta) -->
    <div class="max-w-4xl mx-auto bg-white p-8 md:p-12 rounded-2xl shadow-sm border border-slate-200 print:border-none print:shadow-none print:p-0 space-y-6">

        <!-- Encabezado Institucional -->
        <div class="border-b-2 border-indigo-900 pb-4 flex justify-between items-start">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 rounded-2xl bg-indigo-900 flex items-center justify-center text-white text-2xl font-bold shadow">
                    <i class="fa-solid fa-brain"></i>
                </div>
                <div>
                    <h1 class="text-xl font-black text-indigo-950 uppercase tracking-tight">Colegio Paidos Campeche</h1>
                    <p class="text-xs font-bold text-indigo-700 uppercase tracking-wider">Departamento de Psicopedagogía Escolar</p>
                    <p class="text-[11px] text-slate-500">Expediente Clínico-Pedagógico de Seguimiento Continuo</p>
                </div>
            </div>
            <div class="text-right text-xs space-y-0.5">
                <p class="font-extrabold text-slate-900">Ciclo Escolar: <span class="text-indigo-700">{{ $expediente->ciclo->nombre_ciclo ?? '2026-2027' }}</span></p>
                <p class="text-slate-500">Fecha de Emisión: {{ now()->format('d/m/Y') }}</p>
                <p class="text-[10px] text-slate-400 font-mono">Folio Exp: #EXP-{{ str_pad($expediente->id, 5, '0', STR_PAD_LEFT) }}</p>
            </div>
        </div>

        <!-- Ficha de Datos Generales del Estudiante -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-200 text-xs">
            <div class="md:col-span-2">
                <span class="text-[10px] font-bold text-slate-400 uppercase">Nombre Completo del Alumno</span>
                <p class="text-base font-black text-slate-900 mt-0.5">{{ $expediente->alumno->nombre_completo }}</p>
            </div>
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase">CURP</span>
                <p class="text-sm font-mono font-bold text-slate-800 mt-0.5">{{ $expediente->alumno->curp ?? 'No registrada' }}</p>
            </div>
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase">Grado y Grupo</span>
                <p class="text-xs font-bold text-indigo-800 mt-0.5">{{ $expediente->grado_grupo }}</p>
            </div>
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase">Docente Titular</span>
                <p class="text-xs font-bold text-slate-800 mt-0.5">{{ $expediente->docente_titular }}</p>
            </div>
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase">Estatus del Caso</span>
                <p class="text-xs font-bold mt-0.5">
                    @if($expediente->prioridad == 'ALTA')
                        <span class="text-rose-700 font-extrabold">● Alta Prioridad (Mensual)</span>
                    @else
                        <span class="text-amber-700 font-extrabold">● Seguimiento (Trimestral)</span>
                    @endif
                </p>
            </div>
        </div>

        <!-- Rubro Institucional: Tutor y Canalización -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 p-3 bg-slate-50 rounded-xl border border-slate-200 text-xs">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase">Tutor Responsable:</span>
                <p class="font-bold text-slate-800 mt-0.5">{{ $expediente->alumno->tutor_nombre ?? 'No registrado' }}</p>
            </div>
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase">Teléfono de Contacto:</span>
                <p class="font-bold text-slate-800 mt-0.5">{{ $expediente->alumno->tutor_telefono ?? 'No registrado' }}</p>
            </div>
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase">Canalizado por:</span>
                <p class="font-bold text-indigo-800 mt-0.5">{{ $expediente->canalizado_por ?? 'Docente titular' }}</p>
            </div>
        </div>

        <!-- Diagnóstico y Condición Psicopedagógica -->
        <div class="border border-slate-200 rounded-xl p-4 space-y-3 text-xs">
            <h2 class="text-xs font-black uppercase tracking-wider text-indigo-900 border-b border-slate-100 pb-1.5 flex items-center gap-1.5">
                <i class="fa-solid fa-notes-medical text-indigo-600"></i>
                <span>Pauta Diagnóstica y Adecuaciones Curriculares</span>
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase">Diagnóstico Clínico / Educativo:</span>
                    <p class="font-bold text-slate-900 mt-0.5">{{ $expediente->diagnostico }}</p>
                    @if($expediente->area_atencion)
                        <span class="text-[10px] text-indigo-700 font-semibold block mt-0.5">Área: {{ $expediente->area_atencion }}</span>
                    @endif
                </div>
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase">Especialista Externo Tratante:</span>
                    <p class="font-bold text-slate-800 mt-0.5">{{ $expediente->especialista_externo ?? 'Sin especialista externo reportado' }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-2 pt-1 border-t border-slate-100">
                <p class="text-slate-600">
                    <strong>Dictamen Físico:</strong> 
                    {{ $expediente->tiene_valoracion_fisica ? '✓ Entregado' : '✗ Pendiente' }}
                </p>
                <p class="text-slate-600">
                    <strong>Tratamiento Fármaco:</strong> 
                    {{ $expediente->tratamiento_farmacologico ? '✓ Sí' : '✗ No' }}
                </p>
                <p class="text-slate-600">
                    <strong>Adecuaciones en Aula:</strong> 
                    {{ $expediente->requiere_adecuaciones ? '✓ Requiere' : '✗ No requeridas' }}
                </p>
            </div>

            @if($expediente->tratamiento_farmacologico && $expediente->detalle_farmaco)
                <div class="bg-amber-50/70 p-2.5 rounded-lg border border-amber-200/60">
                    <span class="font-bold text-amber-900">Detalle Farmacológico:</span>
                    <span class="text-amber-800 ml-1">{{ $expediente->detalle_farmaco }}</span>
                </div>
            @endif

            @if($expediente->requiere_adecuaciones && $expediente->detalle_adecuaciones)
                <div class="bg-indigo-50/70 p-2.5 rounded-lg border border-indigo-200/60">
                    <span class="font-bold text-indigo-900">Adecuaciones Curriculares Específicas:</span>
                    <span class="text-indigo-800 ml-1">{{ $expediente->detalle_adecuaciones }}</span>
                </div>
            @endif

            @if($expediente->compromisos_familia)
                <div class="bg-slate-50 p-2.5 rounded-lg border border-slate-200">
                    <span class="font-bold text-slate-800">Compromisos de la Familia / Tutores:</span>
                    <span class="text-slate-700 ml-1">{{ $expediente->compromisos_familia }}</span>
                </div>
            @endif
        </div>

        <!-- Bitácora Cronológica de Intervenciones -->
        <div class="space-y-3">
            <h2 class="text-xs font-black uppercase tracking-wider text-indigo-900 border-b border-slate-200 pb-1.5 flex items-center justify-between">
                <span class="flex items-center gap-1.5">
                    <i class="fa-solid fa-timeline text-indigo-600"></i>
                    <span>Bitácora de Intervenciones y Acuerdos</span>
                </span>
                <span class="text-[10px] font-normal text-slate-500">Total: {{ $expediente->atenciones->count() }} intervenciones registradas</span>
            </h2>

            @if($expediente->atenciones->count() > 0)
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-100 text-[10px] font-bold uppercase text-slate-600 border border-slate-200">
                            <th class="p-2 border-r border-slate-200 w-24">Fecha</th>
                            <th class="p-2 border-r border-slate-200 w-28">Ámbito</th>
                            <th class="p-2 border-r border-slate-200">Motivo y Observaciones</th>
                            <th class="p-2">Acuerdos / Compromisos</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 border border-slate-200">
                        @foreach($expediente->atenciones as $a)
                            <tr class="page-break-inside-avoid">
                                <td class="p-2 align-top font-bold text-slate-700 border-r border-slate-200">
                                    {{ $a->fecha_hora->format('d/m/Y') }}
                                    <span class="block text-[10px] font-normal text-slate-400">
                                        {{ $a->fecha_hora->format('H:i') }} hrs
                                        @if($a->duracion_minutos) ({{ $a->duracion_minutos }} min) @endif
                                    </span>
                                </td>
                                <td class="p-2 align-top border-r border-slate-200">
                                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold
                                        @if($a->tipo_atencion == 'AULA') bg-amber-50 text-amber-700 border border-amber-200
                                        @elseif($a->tipo_atencion == 'PADRES') bg-blue-50 text-blue-700 border border-blue-200
                                        @elseif($a->tipo_atencion == 'ALUMNO') bg-emerald-50 text-emerald-700 border border-emerald-200
                                        @else bg-purple-50 text-purple-700 border border-purple-200 @endif">
                                        {{ $a->tipo_atencion }}
                                    </span>
                                </td>
                                <td class="p-2 align-top border-r border-slate-200">
                                    <p class="font-bold text-slate-900">{{ $a->motivo }}</p>
                                    <p class="text-[11px] text-slate-600 mt-1 leading-snug">{{ $a->observaciones }}</p>
                                    @if($a->participantes)
                                        <p class="text-[10px] text-slate-400 mt-0.5 italic">Part: {{ $a->participantes }}</p>
                                    @endif
                                </td>
                                <td class="p-2 align-top bg-slate-50/50">
                                    @if($a->acuerdos)
                                        <p class="text-[11px] font-medium text-indigo-900 leading-snug">{{ $a->acuerdos }}</p>
                                    @else
                                        <span class="text-[10px] text-slate-400 italic">Sin acuerdos reportados</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="p-6 text-center border border-dashed border-slate-300 rounded-xl text-slate-400 text-xs">
                    No se registran intervenciones en este ciclo escolar.
                </div>
            @endif
        </div>

        <!-- Bloque de Firmas Oficiales de Validación -->
        <div class="pt-8 page-break-inside-avoid border-t border-slate-200">
            <p class="text-[10px] font-bold uppercase text-slate-400 text-center mb-8">Validación y Firmas de Conformidad</p>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center text-xs">
                <div>
                    <div class="border-b border-slate-400 h-14 mb-2"></div>
                    <p class="font-bold text-slate-800">{{ auth()->user() && auth()->user()->esPsicopedagogia() ? auth()->user()->name : 'Mercedes Segovia Mayo / Greyci Salas Cahuich' }}</p>
                    <p class="text-[10px] text-slate-500">Psicología de Primaria (Colegio Paidos)</p>
                </div>
                <div>
                    <div class="border-b border-slate-400 h-14 mb-2"></div>
                    <p class="font-bold text-slate-800">Damaris Morales Bastarrachea</p>
                    <p class="text-[10px] text-slate-500">Directora de Primaria (Visto Bueno)</p>
                </div>
                <div>
                    <div class="border-b border-slate-400 h-14 mb-2"></div>
                    <p class="font-bold text-slate-800">{{ $expediente->docente_titular }}</p>
                    <p class="text-[10px] text-slate-500">Docente Titular ({{ $expediente->grado_grupo }})</p>
                </div>
                <div>
                    <div class="border-b border-slate-400 h-14 mb-2"></div>
                    <p class="font-bold text-slate-800">Padre / Madre o Tutor</p>
                    <p class="text-[10px] text-slate-500">Enterado y en Conformidad</p>
                </div>
            </div>
        </div>

        <!-- Leyenda Legal de Confidencialidad -->
        <div class="pt-4 border-t border-slate-100 text-[9px] text-slate-400 text-center leading-relaxed">
            Este documento contiene información psicopedagógica y médica de carácter estrictamente confidencial bajo custodia del Colegio Paidos Campeche, protegida conforme a la Ley General de Protección de Datos Personales en Posesión de Sujetos Obligados y la normatividad educativa vigente. Su divulgación, copia o distribución no autorizada queda prohibida.
        </div>

    </div>

</body>
</html>
