<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Modo Aula 📱 - PsicoData Paidos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        input, select, textarea, button {
            touch-action: manipulation;
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 min-h-screen pb-20">

    <!-- Header Móvil Fijo -->
    <header class="sticky top-0 z-30 bg-indigo-900 text-white shadow-md border-b border-indigo-950">
        <div class="max-w-xl mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <a href="{{ route('dashboard') }}" class="w-9 h-9 rounded-xl bg-indigo-800 flex items-center justify-center text-indigo-200 hover:text-white transition">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-base font-bold flex items-center gap-1.5">
                        <i class="fa-solid fa-chalkboard-user text-amber-400"></i>
                        <span>Observación en Aula</span>
                    </h1>
                    <p class="text-[11px] text-indigo-300">Captura rápida en vivo • Paidos</p>
                </div>
            </div>
            <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 flex items-center gap-1">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Móvil
            </span>
        </div>
    </header>

    <!-- Contenedor Principal -->
    <main class="max-w-xl mx-auto p-4 space-y-4">

        @if(session('success'))
            <div class="p-3.5 bg-emerald-500 text-white rounded-2xl shadow-sm text-xs font-semibold flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-circle-check text-lg"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-100 hover:text-white">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        @if($errors->any())
            <div class="p-3.5 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-xs space-y-1">
                <p class="font-bold flex items-center gap-1.5 text-rose-700">
                    <i class="fa-solid fa-triangle-exclamation"></i> Por favor revisa los campos:
                </p>
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('atenciones.movil.store') }}" id="formMovilAula" class="space-y-4">
            @csrf

            <!-- Selector de Alumno con Filtro Rápido -->
            <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm space-y-3">
                <label class="block text-xs font-bold text-slate-700">
                    <i class="fa-solid fa-user-graduate text-indigo-600 mr-1"></i> Estudiante Observado *
                </label>
                
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-3 text-slate-400 text-xs"></i>
                    <input type="text" id="filtroRapidoAlumnos" placeholder="Filtrar por nombre o grupo..." 
                           class="w-full pl-8 pr-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50">
                </div>

                <select name="expediente_ciclo_id" id="selectExpediente" required size="4"
                        class="w-full text-xs font-medium rounded-xl border border-slate-200 p-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                    @foreach($expedientes as $exp)
                        <option value="{{ $exp->id }}" {{ (old('expediente_ciclo_id', $expedienteId) == $exp->id) ? 'selected' : '' }} class="p-2 border-b border-slate-100 last:border-none">
                            [{{ $exp->grado_grupo }}] {{ $exp->alumno->nombre_completo }} — {{ $exp->diagnostico }}
                        </option>
                    @endforeach
                </select>
                <p class="text-[11px] text-slate-400">Toca el estudiante sobre el que harás la anotación.</p>
            </div>

            <!-- Chips de Motivo Rápido -->
            <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm space-y-2.5">
                <div class="flex items-center justify-between">
                    <label class="block text-xs font-bold text-slate-700">
                        <i class="fa-solid fa-bolt text-amber-500 mr-1"></i> Motivo de la Observación *
                    </label>
                    <span class="text-[10px] text-slate-400">Toca un atajo o escribe</span>
                </div>

                <!-- Chips táctiles -->
                <div class="flex flex-wrap gap-1.5">
                    @foreach($chipsConducta as $chip)
                        <button type="button" 
                                onclick="seleccionarMotivo('{{ addslashes($chip) }}')"
                                class="chip-motivo text-[11px] font-medium px-2.5 py-1.5 rounded-xl bg-slate-100 text-slate-700 hover:bg-indigo-100 hover:text-indigo-800 transition active:scale-95 text-left border border-slate-200/60">
                            {{ $chip }}
                        </button>
                    @endforeach
                </div>

                <input type="text" name="motivo" id="inputMotivo" required 
                       value="{{ old('motivo') }}"
                       placeholder="Ej. Dificultad para mantener la atención en clase..."
                       class="w-full text-sm rounded-xl border border-slate-200 p-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <!-- Observaciones Clínico-Pedagógicas -->
            <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm space-y-2">
                <div class="flex items-center justify-between">
                    <label class="block text-xs font-bold text-slate-700">
                        <i class="fa-solid fa-notes-medical text-indigo-600 mr-1"></i> Detalle de lo Observado en Aula *
                    </label>
                    <button type="button" onclick="insertarHoraEnNota()" class="text-[11px] text-indigo-600 font-semibold hover:underline">
                        + Hora actual
                    </button>
                </div>
                <textarea name="observaciones" id="inputObservaciones" rows="4" required
                          placeholder="Describe brevemente la conducta, detonantes, respuesta a la instrucción o apoyo brindado..."
                          class="w-full text-sm rounded-xl border border-slate-200 p-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 leading-relaxed">{{ old('observaciones') }}</textarea>
                <p class="text-[10px] text-slate-400">Consejo: Puedes usar el dictado por voz del teclado de tu celular para mayor rapidez.</p>
            </div>

            <!-- Acuerdos o Intervención Inmediata -->
            <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm space-y-2">
                <label class="block text-xs font-bold text-slate-700">
                    <i class="fa-solid fa-handshake text-emerald-600 mr-1"></i> Ajuste en el Aula / Acuerdos con Docente
                </label>
                <textarea name="acuerdos" rows="2"
                          placeholder="Ej. Se le otorgó pausa activa de 2 min; se le recordó apoyo visual al maestro..."
                          class="w-full text-sm rounded-xl border border-slate-200 p-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('acuerdos') }}</textarea>
            </div>

            <!-- Fecha, Hora y Duración -->
            <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Fecha y Hora</label>
                    <input type="datetime-local" name="fecha_hora" id="inputFechaHora" 
                           value="{{ old('fecha_hora', now()->format('Y-m-d\TH:i')) }}" required
                           class="w-full text-xs rounded-xl border border-slate-200 p-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Duración (minutos)</label>
                    <input type="number" name="duracion_minutos" min="5" max="240" 
                           value="{{ old('duracion_minutos', 20) }}"
                           class="w-full text-xs rounded-xl border border-slate-200 p-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <!-- Acción posterior al guardar (Nomenclatura unificada: Ver Expediente) -->
            <div class="bg-white p-3 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between text-xs text-slate-700 font-medium">
                <span>Al guardar:</span>
                <div class="flex items-center gap-3">
                    <label class="flex items-center gap-1.5 cursor-pointer">
                        <input type="radio" name="accion_siguiente" value="seguir_aula" checked class="text-indigo-600">
                        <span>Seguir en aula</span>
                    </label>
                    <label class="flex items-center gap-1.5 cursor-pointer">
                        <input type="radio" name="accion_siguiente" value="ver_expediente" class="text-indigo-600">
                        <span>Ver Expediente</span>
                    </label>
                </div>
            </div>

            <!-- Botón de Envío Prominente -->
            <div class="pt-2">
                <button type="submit" 
                        class="w-full py-3.5 px-4 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white rounded-2xl font-bold text-sm shadow-lg shadow-indigo-600/30 flex items-center justify-center space-x-2 transition">
                    <i class="fa-solid fa-floppy-disk text-base"></i>
                    <span>Guardar Observación en Aula</span>
                </button>
            </div>
        </form>
    </main>

    <!-- Barra de Navegación Móvil Inferior -->
    <nav class="fixed bottom-0 left-0 right-0 z-30 bg-white/95 backdrop-blur border-t border-slate-200 px-2 py-2 flex items-center justify-around shadow-2xl">
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center py-1 px-2 text-slate-500 hover:text-indigo-600 text-xs">
            <i class="fa-solid fa-chart-pie text-base"></i>
            <span class="text-[10px] mt-0.5">Métricas</span>
        </a>
        <a href="{{ route('alumnos.index') }}" class="flex flex-col items-center py-1 px-2 text-slate-500 hover:text-indigo-600 text-xs">
            <i class="fa-solid fa-folder-open text-base"></i>
            <span class="text-[10px] mt-0.5">Expedientes</span>
        </a>
        <a href="{{ route('atenciones.movil.aula') }}" class="flex flex-col items-center text-amber-600 font-bold text-xs -mt-5">
            <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-amber-500 to-amber-400 text-white flex items-center justify-center shadow-lg shadow-amber-500/30 border-2 border-white transition active:scale-95">
                <i class="fa-solid fa-mobile-screen-button text-lg"></i>
            </div>
            <span class="text-[10px] mt-1 text-slate-700 font-semibold">Modo Aula</span>
        </a>
        <a href="{{ route('atenciones.create') }}" class="flex flex-col items-center py-1 px-2 text-slate-500 hover:text-indigo-600 text-xs">
            <i class="fa-solid fa-notes-medical text-base"></i>
            <span class="text-[10px] mt-0.5">+ Atención</span>
        </a>
        <a href="{{ route('ciclos.index') }}" class="flex flex-col items-center py-1 px-2 text-slate-500 hover:text-indigo-600 text-xs">
            <i class="fa-solid fa-calendar-days text-base"></i>
            <span class="text-[10px] mt-0.5">Ciclos</span>
        </a>
    </nav>

    <script>
        function seleccionarMotivo(texto) {
            const input = document.getElementById('inputMotivo');
            input.value = texto;
            input.focus();
            
            document.querySelectorAll('.chip-motivo').forEach(btn => {
                if (btn.innerText.trim() === texto.trim()) {
                    btn.classList.add('bg-indigo-600', 'text-white', 'border-indigo-600');
                    btn.classList.remove('bg-slate-100', 'text-slate-700');
                } else {
                    btn.classList.remove('bg-indigo-600', 'text-white', 'border-indigo-600');
                    btn.classList.add('bg-slate-100', 'text-slate-700');
                }
            });
        }

        function insertarHoraEnNota() {
            const textarea = document.getElementById('inputObservaciones');
            const ahora = new Date();
            const horaStr = `[${String(ahora.getHours()).padStart(2, '0')}:${String(ahora.getMinutes()).padStart(2, '0')}] `;
            textarea.value = (textarea.value ? textarea.value + "\n" : "") + horaStr;
            textarea.focus();
        }

        document.getElementById('filtroRapidoAlumnos').addEventListener('input', function(e) {
            const filtro = e.target.value.toLowerCase();
            const select = document.getElementById('selectExpediente');
            const options = select.options;
            
            for (let i = 0; i < options.length; i++) {
                const texto = options[i].text.toLowerCase();
                if (texto.includes(filtro)) {
                    options[i].style.display = '';
                } else {
                    options[i].style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>
