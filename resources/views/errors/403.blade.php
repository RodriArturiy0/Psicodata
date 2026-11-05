<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Restringido - PsicoData Colegio Paidos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-xl w-full bg-white rounded-3xl shadow-xl border border-rose-100 overflow-hidden">
        <div class="bg-gradient-to-r from-rose-600 to-amber-600 p-8 text-white text-center">
            <div class="w-20 h-20 bg-white/20 backdrop-blur rounded-2xl flex items-center justify-center mx-auto mb-4 border border-white/30">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <span class="inline-block px-3 py-1 bg-white/20 rounded-full text-xs font-semibold tracking-wider uppercase mb-2">
                Código de Seguridad: 403 Prohibido
            </span>
            <h1 class="text-2xl font-bold">Acceso Restringido por Confidencialidad Clínica</h1>
            <p class="text-rose-100 text-sm mt-1">Colegio Paidos Campeche — Departamento de Psicopedagogía</p>
        </div>

        <div class="p-8 space-y-6">
            <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-xl">
                <h3 class="text-rose-900 font-bold text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    Aviso Institucional de Protección de Datos
                </h3>
                <p class="text-rose-800 text-xs mt-1 leading-relaxed">
                    {{ $motivo ?? 'De conformidad con los protocolos de confidencialidad institucional del Colegio Paidos y la normativa de protección de datos de salud física y mental, los expedientes psicopedagógicos, diagnósticos médicos y notas de seguimiento están reservados con exclusividad para la Especialista en Psicopedagogía y Dirección Escolar. La plantilla docente tiene restringido el acceso a esta información.' }}
                </p>
            </div>

            <div class="space-y-3 text-xs text-slate-600 bg-slate-50 p-4 rounded-xl border border-slate-200">
                <p class="font-semibold text-slate-700">Políticas de Privacidad Aplicadas:</p>
                <ul class="list-disc list-inside space-y-1">
                    <li>Protección del expediente médico-pedagógico y farmacológico del alumno.</li>
                    <li>Bloqueo estricto para cuentas con rol docente.</li>
                    <li>Registro de auditoría de intentos de acceso en el servidor local.</li>
                </ul>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 pt-2">
                <a href="{{ route('dashboard') }}" class="flex-1 text-center px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-semibold text-sm transition">
                    ← Volver al Panel
                </a>
                <a href="{{ route('login') }}" class="flex-1 text-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-semibold text-sm transition shadow-md shadow-indigo-100">
                    Cambiar de Usuario
                </a>
            </div>
        </div>
    </div>
</body>
</html>

