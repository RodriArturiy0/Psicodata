<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Mensual {{ $nombreMes }} {{ $anio }} - Colegio Paidos</title>
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

    <!-- Barra de Acciones (No imprimible) -->
    <div class="no-print max-w-4xl mx-auto mb-6 flex items-center justify-between bg-white p-4 rounded-2xl border border-slate-200 shadow-md">
        <a href="{{ route('dashboard', ['mes' => $mes, 'anio' => $anio]) }}" class="flex items-center gap-2 text-xs font-bold text-slate-600 hover:text-indigo-600 transition">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Volver al Dashboard</span>
        </a>
        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-sm flex items-center gap-2 transition active:scale-95">
                <i class="fa-solid fa-print"></i>
                <span>Imprimir Informe para Dirección (PDF)</span>
            </button>
        </div>
    </div>

    <!-- Hoja Formal de Reporte Mensual -->
    <div class="max-w-4xl mx-auto bg-white p-8 md:p-12 rounded-2xl shadow-sm border border-slate-200 print:border-none print:shadow-none print:p-0 space-y-6">

        <!-- Encabezado Institucional -->
        <div class="border-b-2 border-indigo-900 pb-4 flex justify-between items-start">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 rounded-2xl bg-indigo-900 flex items-center justify-center text-white text-2xl font-bold shadow">
                    <i class="fa-solid fa-chart-pie"></i>
                </div>
                <div>
                    <h1 class="text-xl font-black text-indigo-950 uppercase tracking-tight">Colegio Paidos Campeche</h1>
                    <p class="text-xs font-bold text-indigo-700 uppercase tracking-wider">Departamento de Psicopedagogía</p>
                    <p class="text-[11px] text-slate-500">Informe Mensual de Actividades e Intervenciones Escolares</p>
                </div>
            </div>
            <div class="text-right text-xs space-y-0.5">
                <p class="font-extrabold text-slate-900">Periodo Reportado: <span class="text-indigo-700 text-sm">{{ $nombreMes }} {{ $anio }}</span></p>
                <p class="text-slate-500">Fecha de Corte: {{ now()->format('d/m/Y') }}</p>
                <p class="text-[10px] text-slate-400">Entrega Oficial a Dirección</p>
            </div>
        </div>

        <!-- Métricas Resumen Cuantitativo -->
        <div>
            <h2 class="text-xs font-black uppercase tracking-wider text-indigo-900 mb-3 flex items-center gap-1.5">
                <i class="fa-solid fa-calculator text-indigo-600"></i>
                <span>1. Resumen Estadístico de Atenciones del Mes</span>
            </h2>

            <div class="grid grid-cols-4 gap-3 text-center">
                <div class="p-4 rounded-xl border border-slate-200 bg-slate-50">
                    <span class="text-[10px] font-bold text-slate-400 uppercase">En Aula</span>
                    <p class="text-2xl font-black text-slate-900 mt-1">{{ $totales['AULA'] }}</p>
                    <p class="text-[10px] text-slate-500">Observaciones</p>
                </div>
                <div class="p-4 rounded-xl border border-slate-200 bg-slate-50">
                    <span class="text-[10px] font-bold text-slate-400 uppercase">Padres de Familia</span>
                    <p class="text-2xl font-black text-slate-900 mt-1">{{ $totales['PADRES'] }}</p>
                    <p class="text-[10px] text-slate-500">Entrevistas</p>
                </div>
                <div class="p-4 rounded-xl border border-slate-200 bg-slate-50">
                    <span class="text-[10px] font-bold text-slate-400 uppercase">Alumnos</span>
                    <p class="text-2xl font-black text-slate-900 mt-1">{{ $totales['ALUMNO'] }}</p>
                    <p class="text-[10px] text-slate-500">Sesiones individuales</p>
                </div>
                <div class="p-4 rounded-xl border border-slate-200 bg-slate-50">
                    <span class="text-[10px] font-bold text-slate-400 uppercase">Docentes</span>
                    <p class="text-2xl font-black text-slate-900 mt-1">{{ $totales['DOCENTE'] }}</p>
                    <p class="text-[10px] text-slate-500">Acuerdos pedagógicos</p>
                </div>
            </div>

            <div class="mt-3 p-3 bg-indigo-50 border border-indigo-200 rounded-xl flex justify-between items-center text-xs">
                <span class="font-bold text-indigo-950 uppercase">Total Consolidado de Intervenciones en {{ $nombreMes }}:</span>
                <span class="text-base font-black text-indigo-700">{{ $totalMes }} eventos registrados</span>
            </div>
        </div>

        <!-- Casos de Alta Prioridad Atendidos -->
        <div class="space-y-3">
            <h2 class="text-xs font-black uppercase tracking-wider text-indigo-900 border-b border-slate-200 pb-1.5 flex items-center justify-between">
                <span class="flex items-center gap-1.5">
                    <i class="fa-solid fa-triangle-exclamation text-rose-500"></i>
                    <span>2. Estado de Casos de Alta Prioridad (Seguimiento Mensual Activo)</span>
                </span>
                <span class="text-[10px] font-normal text-slate-500">{{ $casosAltaPrioridad->count() }} casos activos</span>
            </h2>

            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-100 text-[10px] font-bold uppercase text-slate-600 border border-slate-200">
                        <th class="p-2 border-r border-slate-200">Estudiante</th>
                        <th class="p-2 border-r border-slate-200">Grado / Docente</th>
                        <th class="p-2 border-r border-slate-200">Diagnóstico</th>
                        <th class="p-2 text-right">Atenciones en {{ $nombreMes }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 border border-slate-200">
                    @forelse($casosAltaPrioridad as $caso)
                        <tr class="page-break-inside-avoid">
                            <td class="p-2 font-bold text-slate-900 border-r border-slate-200">{{ $caso->alumno->nombre_completo }}</td>
                            <td class="p-2 border-r border-slate-200">
                                <span class="font-bold text-indigo-800">{{ $caso->grado_grupo }}</span>
                                <span class="text-[11px] text-slate-500 block">{{ $caso->docente_titular }}</span>
                            </td>
                            <td class="p-2 text-[11px] text-slate-700 border-r border-slate-200">{{ $caso->diagnostico }}</td>
                            <td class="p-2 text-right">
                                @php $cant = $caso->atenciones->count(); @endphp
                                @if($cant > 0)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        ✓ {{ $cant }} atenciones
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        ⚠ Sin atención en mes
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-4 text-center text-slate-400 text-xs">No hay casos clasificados como Alta Prioridad en este ciclo.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Desglose de Intervenciones del Mes -->
        <div class="space-y-3">
            <h2 class="text-xs font-black uppercase tracking-wider text-indigo-900 border-b border-slate-200 pb-1.5 flex items-center justify-between">
                <span class="flex items-center gap-1.5">
                    <i class="fa-solid fa-list-check text-indigo-600"></i>
                    <span>3. Detalle de Intervenciones Realizadas</span>
                </span>
                <span class="text-[10px] font-normal text-slate-500">Bitácora mensual de actividades</span>
            </h2>

            @if($intervencionesMes->count() > 0)
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-100 text-[10px] font-bold uppercase text-slate-600 border border-slate-200">
                            <th class="p-2 border-r border-slate-200 w-24">Fecha</th>
                            <th class="p-2 border-r border-slate-200 w-24">Tipo</th>
                            <th class="p-2 border-r border-slate-200">Estudiante / Grupo</th>
                            <th class="p-2 border-r border-slate-200">Motivo / Resumen</th>
                            <th class="p-2">Acuerdos Clave</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 border border-slate-200">
                        @foreach($intervencionesMes as $item)
                            <tr class="page-break-inside-avoid">
                                <td class="p-2 align-top text-slate-700 border-r border-slate-200 font-bold">
                                    {{ $item->fecha_hora->format('d/m/Y') }}
                                </td>
                                <td class="p-2 align-top border-r border-slate-200">
                                    <span class="text-[10px] font-bold">{{ $item->tipo_atencion }}</span>
                                </td>
                                <td class="p-2 align-top border-r border-slate-200">
                                    <span class="font-bold text-slate-900">{{ $item->expediente->alumno->nombre_completo ?? 'N/A' }}</span>
                                    <span class="text-[10px] text-slate-500 block">({{ $item->expediente->grado_grupo ?? '' }})</span>
                                </td>
                                <td class="p-2 align-top border-r border-slate-200">
                                    <p class="font-semibold text-slate-800">{{ $item->motivo }}</p>
                                    <p class="text-[10px] text-slate-500 truncate max-w-xs">{{ $item->observaciones }}</p>
                                </td>
                                <td class="p-2 align-top bg-slate-50/50">
                                    <p class="text-[10px] text-slate-700">{{ $item->acuerdos ?? 'Sin acuerdos reportados' }}</p>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="p-6 text-center border border-dashed border-slate-300 rounded-xl text-slate-400 text-xs">
                    No se registran intervenciones capturadas en el mes de {{ $nombreMes }} de {{ $anio }}.
                </div>
            @endif
        </div>

        <!-- Firmas Oficiales de Entrega y Recepción -->
        <div class="pt-8 page-break-inside-avoid border-t border-slate-200">
            <div class="grid grid-cols-2 gap-12 text-center text-xs">
                <div>
                    <div class="border-b border-slate-400 h-16 mb-2"></div>
                    <p class="font-bold text-slate-900">{{ auth()->user() && auth()->user()->esPsicopedagogia() ? auth()->user()->name : 'Mercedes Segovia Mayo / Greyci Salas Cahuich' }}</p>
                    <p class="text-[10px] text-slate-500">Psicología de Primaria (Emite Reporte)</p>
                </div>
                <div>
                    <div class="border-b border-slate-400 h-16 mb-2"></div>
                    <p class="font-bold text-slate-900">Damaris Morales Bastarrachea</p>
                    <p class="text-[10px] text-slate-500">Directora de Primaria (Recibe y Valida)</p>
                </div>
            </div>
        </div>

    </div>

</body>
</html>

