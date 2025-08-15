@role('Admin')
<x-layouts.app title="Panel de Control - Admin">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <!-- Primera fila: 3 gráficos principales -->
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <!-- Usuarios Registrados -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm">
                <div class="p-4 border-b border-neutral-200 dark:border-neutral-700 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Usuarios Registrados</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total: {{ $totalUsers }} usuarios | Activos {{ $activeUsers }}, Inactivos {{ $inactiveUsers }}</p>
                    </div>
                    <button onclick="downloadChart('usersChart', 'usuarios-registrados')" class="px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">
                        Descargar
                    </button>
                </div>
                <div class="p-4 h-64">
                    {!! $chart->render() !!}
                </div>
            </div>

            <!-- Distribución por Género -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm">
                <div class="p-4 border-b border-neutral-200 dark:border-neutral-700 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Distribución por Género</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Masculino: {{ $maleUsers }} | Femenino: {{ $femaleUsers }}</p>
                    </div>
                    <button onclick="downloadChart('genderChart', 'distribucion-genero')" class="px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">
                        Descargar
                    </button>
                </div>
                <div class="p-4 h-64">
                    {!! $genderChart->render() !!}
                </div>
            </div>

            <!-- Entregas de Medicamentos -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm">
                <div class="p-4 border-b border-neutral-200 dark:border-neutral-700 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Usuarios por Municipio</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Distribución completa por municipios</p>
                    </div>
                    <button onclick="downloadChart('municipalityChart', 'usuarios-municipio')" class="px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">
                        Descargar
                    </button>
                </div>
                <div class="p-4 h-64">
                    {!! $municipalityChart->render() !!}
                </div>
            </div>
        </div>

        <!-- Segunda fila: Usuarios por Municipio -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm">
            <div class="p-4 border-b border-neutral-200 dark:border-neutral-700 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Entregas de Medicamentos</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Usuarios por entrega programada</p>
                </div>
                <button onclick="downloadChart('deliveryChart', 'entregas-medicamentos')" class="px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">
                    Descargar
                </button>
            </div>
            <div class="p-4 h-80">
                {!! $deliveryChart->render() !!}
            </div>
        </div>

        <!-- Tercera fila: Tablas en 2 columnas -->
        <div class="flex flex-row gap-4">
            <!-- Top 5 Medicamentos -->
            <div class="flex-1 bg-white dark:bg-gray-800 rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm">
                <div class="p-4 border-b border-neutral-200 dark:border-neutral-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Top 5 Medicamentos</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Medicamentos más utilizados en entregas</p>
                </div>
                <div class="p-4 h-80 overflow-y-auto">
                    <div class="overflow-x-auto">
                        <table class="w-full table-auto">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                    <th class="px-2 sm:px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider bg-gray-50 dark:bg-gray-800 rounded-tl-lg w-12">#</th>
                                    <th class="px-2 sm:px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider bg-gray-50 dark:bg-gray-800">Medicamento</th>
                                    <th class="px-2 sm:px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider bg-gray-50 dark:bg-gray-800 rounded-tr-lg w-20">Usos</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @forelse($topMedicines as $index => $medicine)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <td class="px-2 sm:px-4 py-3 text-sm font-medium text-blue-600 dark:text-blue-400">{{ $index + 1 }}</td>
                                    <td class="px-2 sm:px-4 py-3 text-sm font-medium text-gray-900 dark:text-white truncate max-w-0" title="{{ $medicine->generic_name }}">{{ str($medicine->generic_name)->limit(45) }}</td>
                                    <div id="tooltip-default" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-xs opacity-0 tooltip dark:bg-gray-700">
    Tooltip content
    <div class="tooltip-arrow" data-popper-arrow></div>
</div>
                                    <td class="px-2 sm:px-4 py-3 text-sm text-gray-600 dark:text-gray-300 text-center">
                                            {{ $medicine->usage_count }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-2 sm:px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-6 h-6 sm:w-8 sm:h-8 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <span class="text-xs sm:text-sm">No hay datos disponibles</span>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Top 5 Patologías -->
            <div class="flex-1 bg-white dark:bg-gray-800 rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm">
                <div class="p-4 border-b border-neutral-200 dark:border-neutral-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Top 5 Patologías</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Patologías más frecuentes en pacientes</p>
                </div>
                <div class="p-4 h-80 overflow-y-auto">
                    <div class="overflow-x-auto">
                        <table class="w-full table-auto">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                    <th class="px-2 sm:px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider bg-gray-50 dark:bg-gray-800 rounded-tl-lg w-12">#</th>
                                    <th class="px-2 sm:px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider bg-gray-50 dark:bg-gray-800 w-20">CIE-10</th>
                                    <th class="px-2 sm:px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider bg-gray-50 dark:bg-gray-800">Patología</th>
                                    <th class="px-2 sm:px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider bg-gray-50 dark:bg-gray-800 rounded-tr-lg w-20">Casos</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @forelse($topPathologies as $index => $pathology)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <td class="px-2 sm:px-4 py-3 text-sm font-medium text-blue-600 dark:text-blue-400">{{ $index + 1 }}</td>
                                    <td class="px-2 sm:px-4 py-3 text-sm font-mono text-gray-900 dark:text-white">{{ $pathology->pathology->clave ?? 'N/A' }}</td>
                                    <td class="px-2 sm:px-4 py-3 text-sm font-medium text-gray-900 dark:text-white truncate max-w-0" title="{{ $pathology->pathology->descripcion }}">{{ Str::limit($pathology->pathology->descripcion ?? 'Sin descripción', 45) }}</td>
                                    <td class="px-2 sm:px-4 py-3 text-sm text-gray-600 dark:text-gray-300 text-center">
                                            {{ $pathology->usage_count }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-2 sm:px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-6 h-6 sm:w-8 sm:h-8 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <span class="text-xs sm:text-sm">No hay datos disponibles</span>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Script para descarga de gráficos -->
    <script>
    function downloadChart(chartId, filename) {
        const canvas = document.getElementById(chartId);
        if (canvas) {
            const link = document.createElement('a');
            link.download = filename + '.png';
            link.href = canvas.toDataURL();
            link.click();
        }
    }
    </script>
</x-layouts.app>
@else
<x-layouts.app title="Panel de Control - Usuario">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <!-- Usuarios Registrados -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm">
                <div class="p-4 border-b border-neutral-200 dark:border-neutral-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Usuarios Registrados</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total: {{ $totalUsers }} usuarios</p>
                </div>
                <div class="p-4 h-64">
                    {!! $chart->render() !!}
                </div>
            </div>

            <!-- Distribución por Género -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm">
                <div class="p-4 border-b border-neutral-200 dark:border-neutral-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Distribución por Género</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Estadísticas demográficas</p>
                </div>
                <div class="p-4 h-64">
                    {!! $genderChart->render() !!}
                </div>
            </div>

            <!-- Placeholder -->
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
        </div>

        <!-- Placeholder inferior -->
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
        </div>
    </div>
</x-layouts.app>
@endrole