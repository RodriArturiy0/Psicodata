@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <!-- Encabezado y Botones Principales -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Directorio de Expedientes Psicopedagógicos</h2>
            <p class="text-sm text-slate-500 mt-1">Ciclo Escolar 2026-2027 • Colegio Paidos Campeche</p>
        </div>
        @if(auth()->user() && auth()->user()->puedeEditarExpedientes())
            <a href="{{ route('alumnos.create') }}" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold shadow-sm transition flex items-center gap-2">
                <i class="fa-solid fa-plus"></i>
                <span>+ Nueva Ficha</span>
            </a>
        @endif
    </div>

    <!-- Pestañas de Estado (Activos / Bajas / Todos) -->
    <div class="flex items-center gap-1 bg-slate-200/70 p-1.5 rounded-2xl w-fit text-xs font-bold text-slate-600">
        <a href="{{ route('alumnos.index', ['estado' => 'ACTIVO', 'buscar' => $buscar, 'prioridad' => $prioridad, 'grado' => $grado, 'orden' => $orden]) }}" 
           class="px-4 py-2 rounded-xl transition flex items-center gap-1.5 {{ $estado === 'ACTIVO' ? 'bg-white text-indigo-700 shadow-sm' : 'hover:text-slate-900' }}">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            <span>Activos ({{ $totalActivos }})</span>
        </a>
        <a href="{{ route('alumnos.index', ['estado' => 'BAJAS', 'buscar' => $buscar, 'prioridad' => $prioridad, 'grado' => $grado, 'orden' => $orden]) }}" 
           class="px-4 py-2 rounded-xl transition flex items-center gap-1.5 {{ $estado === 'BAJAS' ? 'bg-white text-rose-700 shadow-sm' : 'hover:text-slate-900' }}">
            <span class="w-2 h-2 rounded-full bg-rose-500"></span>
            <span>Bajas / Archivo Muerto ({{ $totalBajas }})</span>
        </a>
        <a href="{{ route('alumnos.index', ['estado' => 'TODOS', 'buscar' => $buscar, 'prioridad' => $prioridad, 'grado' => $grado, 'orden' => $orden]) }}" 
           class="px-4 py-2 rounded-xl transition flex items-center gap-1.5 {{ $estado === 'TODOS' ? 'bg-white text-slate-900 shadow-sm' : 'hover:text-slate-900' }}">
            <span>Todos ({{ $totalActivos + $totalBajas }})</span>
        </a>
    </div>

    <!-- Barra de Filtros y Ordenamiento Dinámico -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm space-y-3">
        <form method="GET" action="{{ route('alumnos.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <input type="hidden" name="estado" value="{{ $estado }}">

            <!-- 1. Búsqueda por Nombre o CURP -->
            <div class="relative lg:col-span-1">
                <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Buscar Estudiante</label>
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-3 text-slate-400 text-xs"></i>
                    <input type="text" name="buscar" value="{{ $buscar }}" placeholder="Nombre o CURP..." 
                           class="w-full pl-8 pr-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <!-- 2. Filtro por Nivel de Prioridad -->
            <div>
                <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Nivel de Prioridad</label>
                <select name="prioridad" class="w-full text-xs rounded-xl border border-slate-200 p-2 text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-medium">
                    <option value="">Todas las prioridades</option>
                    <option value="ALTA" {{ $prioridad === 'ALTA' ? 'selected' : '' }}>Alta Prioridad (Mensual)</option>
                    <option value="SEGUIMIENTO" {{ $prioridad === 'SEGUIMIENTO' ? 'selected' : '' }}>Seguimiento Regular (Trimestral)</option>
                </select>
            </div>

            <!-- 3. Filtro por Grado y Grupo -->
            <div>
                <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Grado y Grupo</label>
                <select name="grado" class="w-full text-xs rounded-xl border border-slate-200 p-2 text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-medium">
                    <option value="">Todos los grados</option>
                    @foreach($gradosDisponibles as $g)
                        <option value="{{ $g }}" {{ $grado === $g ? 'selected' : '' }}>{{ $g }}</option>
                    @endforeach
                </select>
            </div>

            <!-- 4. Ordenamiento Dinámico -->
            <div>
                <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Ordenar por</label>
                <select name="orden" class="w-full text-xs rounded-xl border border-slate-200 p-2 text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-medium">
                    <option value="grado_asc" {{ $orden === 'grado_asc' ? 'selected' : '' }}>Grado y Grupo (1° a 6°)</option>
                    <option value="grado_desc" {{ $orden === 'grado_desc' ? 'selected' : '' }}>Grado y Grupo (Descendente)</option>
                    <option value="nombre_asc" {{ $orden === 'nombre_asc' ? 'selected' : '' }}>Nombre Estudiante (A - Z)</option>
                    <option value="nombre_desc" {{ $orden === 'nombre_desc' ? 'selected' : '' }}>Nombre Estudiante (Z - A)</option>
                    <option value="prioridad_alta" {{ $orden === 'prioridad_alta' ? 'selected' : '' }}>Prioridad (Altas primero)</option>
                    <option value="recientes" {{ $orden === 'recientes' ? 'selected' : '' }}>Última Actualización</option>
                </select>
            </div>

            <!-- 5. Botones Aplicar / Limpiar -->
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 py-2 px-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-sm">
                    <i class="fa-solid fa-filter"></i>
                    <span>Filtrar</span>
                </button>
                @if($buscar || $prioridad || $grado || $orden !== 'grado_asc')
                    <a href="{{ route('alumnos.index', ['estado' => $estado]) }}" 
                       class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition" title="Limpiar todos los filtros">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabla Principal de Expedientes -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 text-xs font-bold uppercase text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4">Estudiante</th>
                    <th class="px-6 py-4">Grado / Docente</th>
                    <th class="px-6 py-4">Diagnóstico</th>
                    <th class="px-6 py-4">Prioridad / Estado</th>
                    <th class="px-6 py-4 text-right">Acción Principal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($expedientes as $exp)
                    <tr class="hover:bg-slate-50/80 transition {{ in_array($exp->estado_ciclo, ['ARCHIVO_MUERTO', 'SECUNDARIA']) ? 'bg-slate-50/40 text-slate-400' : '' }}">
                        <td class="px-6 py-4">
                            <span class="font-bold text-slate-800 block">{{ $exp->alumno->nombre_completo }}</span>
                            <div class="flex items-center gap-2 text-[10px] text-slate-400 mt-0.5">
                                @if($exp->alumno->curp)
                                    <span class="font-mono">{{ $exp->alumno->curp }}</span>
                                @endif
                                @if($exp->alumno->tutor_telefono)
                                    <span>• Tel: {{ $exp->alumno->tutor_telefono }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 bg-indigo-50 text-indigo-700 rounded font-bold text-xs">{{ $exp->grado_grupo }}</span>
                            <span class="text-xs text-slate-500 ml-1 block mt-0.5">{{ $exp->docente_titular }}</span>
                        </td>
                        <td class="px-6 py-4 text-xs font-medium">
                            <span class="text-slate-800 font-semibold block">{{ $exp->diagnostico }}</span>
                            @if($exp->area_atencion)
                                <span class="text-[10px] text-slate-400">{{ $exp->area_atencion }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1 items-start">
                                @if($exp->prioridad == 'ALTA')
                                    <span class="px-2 py-0.5 bg-rose-50 text-rose-700 border border-rose-200 text-[11px] font-bold rounded">
                                        Alta Prioridad
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 bg-amber-50 text-amber-700 border border-amber-200 text-[11px] font-bold rounded">
                                        Seguimiento
                                    </span>
                                @endif

                                @if($exp->estado_ciclo === 'ARCHIVO_MUERTO')
                                    <span class="px-2 py-0.5 bg-slate-100 text-slate-600 border border-slate-300 text-[10px] font-semibold rounded">
                                        Archivo Muerto
                                    </span>
                                @elseif($exp->estado_ciclo === 'SECUNDARIA')
                                    <span class="px-2 py-0.5 bg-blue-50 text-blue-700 border border-blue-200 text-[10px] font-semibold rounded">
                                        Egresado Secundaria
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('alumnos.imprimir', $exp->id) }}" title="Imprimir o Exportar PDF" target="_blank"
                                   class="p-2 text-slate-400 hover:text-indigo-600 rounded-lg hover:bg-indigo-50 transition">
                                    <i class="fa-solid fa-print"></i>
                                </a>
                                @if(auth()->user() && auth()->user()->puedeEditarExpedientes())
                                    <a href="{{ route('alumnos.edit', $exp->id) }}" title="Editar Ficha" 
                                       class="p-2 text-slate-400 hover:text-amber-600 rounded-lg hover:bg-amber-50 transition">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                @endif
                                <!-- Nomenclatura unificada: Ver Expediente -->
                                <a href="{{ route('alumnos.show', $exp->id) }}" 
                                   class="px-3.5 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-xs rounded-xl transition flex items-center gap-1.5">
                                    <i class="fa-solid fa-folder-open text-xs"></i>
                                    <span>Ver Expediente</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400 text-xs">
                            <i class="fa-solid fa-folder-open text-3xl mb-2 text-slate-300 block"></i>
                            No se encontraron expedientes con los criterios y filtros seleccionados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
