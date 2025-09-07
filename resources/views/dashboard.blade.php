@role('Admin')
    <x-layouts.app title="Panel de Control - Admin">
        <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
            <!-- Primera fila: 3 gráficos principales -->
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <!-- Usuarios Registrados -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm">
                    <div class="p-4 border-b border-neutral-200 dark:border-neutral-700 flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Usuarios Registrados</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Total: {{ $totalUsers }} usuarios | Activos
                                {{ $activeUsers }}, Inactivos {{ $inactiveUsers }}</p>
                        </div>
                        <button onclick="downloadChart('usersChart', 'usuarios-registrados')"
                            class="px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">
                            Descargar
                        </button>
                    </div>
                    <div class="p-4 h-64">
                        {!! $chart->render() !!}
                    </div>
                </div>

                <!-- Distribución por Género -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm">
                    <div class="p-4 border-b border-neutral-200 dark:border-neutral-700 flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Distribución por Género</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Masculino: {{ $maleUsers }} | Femenino:
                                {{ $femaleUsers }}</p>
                        </div>
                        <button onclick="downloadChart('genderChart', 'distribucion-genero')"
                            class="px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">
                            Descargar
                        </button>
                    </div>
                    <div class="p-4 h-64">
                        {!! $genderChart->render() !!}
                    </div>
                </div>

                <!-- Entregas de Medicamentos -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm">
                    <div class="p-4 border-b border-neutral-200 dark:border-neutral-700 flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Usuarios por Municipio</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Distribución completa por municipios</p>
                        </div>
                        <button onclick="downloadChart('municipalityChart', 'usuarios-municipio')"
                            class="px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">
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
                    <button onclick="downloadChart('deliveryChart', 'entregas-medicamentos')"
                        class="px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">
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
                <div
                    class="flex-1 bg-white dark:bg-gray-800 rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm">
                    <div class="p-4 border-b border-neutral-200 dark:border-neutral-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Top 5 Medicamentos</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Medicamentos más utilizados en entregas</p>
                    </div>
                    <div class="p-4 h-80 overflow-y-auto">
                        <div class="overflow-x-auto">
                            <table class="w-full table-auto">
                                <thead>
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <th
                                            class="px-2 sm:px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider bg-gray-50 dark:bg-gray-800 rounded-tl-lg w-12">
                                            #</th>
                                        <th
                                            class="px-2 sm:px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider bg-gray-50 dark:bg-gray-800">
                                            Medicamento</th>
                                        <th
                                            class="px-2 sm:px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider bg-gray-50 dark:bg-gray-800 rounded-tr-lg w-20">
                                            Usos</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                    @forelse($topMedicines as $index => $medicine)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                            <td
                                                class="px-2 sm:px-4 py-3 text-sm font-medium text-blue-600 dark:text-blue-400">
                                                {{ $index + 1 }}</td>
                                            <td class="px-2 sm:px-4 py-3 text-sm font-medium text-gray-900 dark:text-white truncate max-w-0"
                                                title="{{ $medicine->generic_name }}">
                                                {{ str($medicine->generic_name)->limit(45) }}</td>
                                            <div id="tooltip-default" role="tooltip"
                                                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-xs opacity-0 tooltip dark:bg-gray-700">
                                                Tooltip content
                                                <div class="tooltip-arrow" data-popper-arrow></div>
                                            </div>
                                            <td
                                                class="px-2 sm:px-4 py-3 text-sm text-gray-600 dark:text-gray-300 text-center">
                                                {{ $medicine->usage_count }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3"
                                                class="px-2 sm:px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                                <div class="flex flex-col items-center">
                                                    <svg class="w-6 h-6 sm:w-8 sm:h-8 mb-2 text-gray-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                        </path>
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
                <div
                    class="flex-1 bg-white dark:bg-gray-800 rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm">
                    <div class="p-4 border-b border-neutral-200 dark:border-neutral-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Top 5 Patologías</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Patologías más frecuentes en pacientes</p>
                    </div>
                    <div class="p-4 h-80 overflow-y-auto">
                        <div class="overflow-x-auto">
                            <table class="w-full table-auto">
                                <thead>
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <th
                                            class="px-2 sm:px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider bg-gray-50 dark:bg-gray-800 rounded-tl-lg w-12">
                                            #</th>
                                        <th
                                            class="px-2 sm:px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider bg-gray-50 dark:bg-gray-800 w-20">
                                            CIE-10</th>
                                        <th
                                            class="px-2 sm:px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider bg-gray-50 dark:bg-gray-800">
                                            Patología</th>
                                        <th
                                            class="px-2 sm:px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider bg-gray-50 dark:bg-gray-800 rounded-tr-lg w-20">
                                            Casos</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                    @forelse($topPathologies as $index => $pathology)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                            <td
                                                class="px-2 sm:px-4 py-3 text-sm font-medium text-blue-600 dark:text-blue-400">
                                                {{ $index + 1 }}</td>
                                            <td class="px-2 sm:px-4 py-3 text-sm font-mono text-gray-900 dark:text-white">
                                                {{ $pathology->pathology->clave ?? 'N/A' }}</td>
                                            <td class="px-2 sm:px-4 py-3 text-sm font-medium text-gray-900 dark:text-white truncate max-w-0"
                                                title="{{ $pathology->pathology->descripcion }}">
                                                {{ Str::limit($pathology->pathology->descripcion ?? 'Sin descripción', 45) }}
                                            </td>
                                            <td
                                                class="px-2 sm:px-4 py-3 text-sm text-gray-600 dark:text-gray-300 text-center">
                                                {{ $pathology->usage_count }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4"
                                                class="px-2 sm:px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                                <div class="flex flex-col items-center">
                                                    <svg class="w-6 h-6 sm:w-8 sm:h-8 mb-2 text-gray-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                        </path>
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

            <!-- Tabla de Entregas -->
            <livewire:dashboard.deliveries-table />
        </div>

        <!-- Script para descarga de gráficos y reportes -->
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
            
            function generateDeliveryReport(deliveryId) {
                const btn = document.getElementById(`reportBtn-${deliveryId}`);
                const icon = document.getElementById(`reportIcon-${deliveryId}`);
                const loader = document.getElementById(`reportLoader-${deliveryId}`);
                
                // Mostrar loader
                btn.disabled = true;
                icon.classList.add('hidden');
                loader.classList.remove('hidden');
                
                // Hacer petición AJAX
                fetch(`/delivery/${deliveryId}/report`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Crear y descargar el PDF
                            const link = document.createElement('a');
                            link.href = 'data:application/pdf;base64,' + data.pdf;
                            link.download = data.filename;
                            document.body.appendChild(link);
                            link.click();
                            document.body.removeChild(link);
                        } else {
                            alert('Error: ' + (data.message || 'Error desconocido'));
                        }
                    })
                    .catch(error => {
                        console.error('Error completo:', error);
                        alert('Error al generar el reporte: ' + error.message);
                    })
                    .finally(() => {
                        // Ocultar loader
                        btn.disabled = false;
                        icon.classList.remove('hidden');
                        loader.classList.add('hidden');
                    });
            }
        </script>
    </x-layouts.app>
@else
    <!-- Panel de Usuario -->
    <x-layouts.app title="Mi Panel de Control">
        <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
            <!-- Título del Programa -->
            <div class="mb-2">
                <h1 class="text-lg font-semibold text-gray-900 dark:text-white">Programa de Medicamento en Casa</h1>
                <p class="font-semibold text-gray-600 dark:text-gray-400 mt-2">
                    Bienvenid{{ auth()->user()->gender === 'Femenino' ? 'a' : 'o' }}, {{ auth()->user()->name }}</p>
            </div>
            <hr class="h-px bg-gray-200 border-0 dark:bg-gray-700">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Mi Información Personal</h3>
                <flux:button variant="primary" size="sm" :href="route('settings.profile')" wire:navigate>
                    Editar Perfil
                </flux:button>
            </div>

            <!-- Información Personal -->
            <div class="overflow-x-auto">
                <table
                    class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 border dark:border-gray-700">
                    <tbody>
                        <!-- Primera fila: Información básica -->
                        <tr class="border-b dark:border-gray-700 bg-white dark:bg-gray-900">
                            <th class="px-6 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">Nombre:</th>
                            <td class="px-6 py-3 border-r dark:border-gray-700 text-gray-600 dark:text-gray-300">
                                {{ auth()->user()->name }}
                            </td>
                            <th class="px-6 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">DNI:</th>
                            <td class="px-6 py-3 border-r dark:border-gray-700 text-gray-600 dark:text-gray-300">
                                {{ auth()->user()->dni ?? 'No especificado' }}
                            </td>
                            <th class="px-6 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">Correo
                                Electrónico:</th>
                            <td class="px-6 py-3 text-gray-600 dark:text-gray-300">
                                {{ auth()->user()->email ?? 'No especificado' }}
                            </td>
                        </tr>
                        <!-- Segunda fila: Información de contacto y ubicación -->
                        <tr class="border-b dark:border-gray-700 bg-white dark:bg-gray-900">
                            <th class="px-6 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">Teléfonos:
                            </th>
                            <td class="px-6 py-3 border-r dark:border-gray-700 text-gray-600 dark:text-gray-300">
                                {{ auth()->user()->phone ?? 'No especificado' }}
                            </td>
                            <th class="px-6 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">Departamento:
                            </th>
                            <td class="px-6 py-3 border-r dark:border-gray-700 text-gray-600 dark:text-gray-300">
                                {{ auth()->user()->department->name ?? 'No especificado' }}
                            </td>
                            <th class="px-6 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">Municipio:
                            </th>
                            <td class="px-6 py-3 text-gray-600 dark:text-gray-300">
                                {{ auth()->user()->locality?->municipality->name ?? 'No especificado' }}
                            </td>
                        </tr>
                        <!-- Tercera fila: Localidad, dirección y género -->
                        <tr class="border-b dark:border-gray-700 bg-white dark:bg-gray-900">
                            <th class="px-6 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">Localidad:
                            </th>
                            <td class="px-6 py-3 border-r dark:border-gray-700 text-gray-600 dark:text-gray-300">
                                {{ auth()->user()->locality->name ?? 'No especificado' }}
                            </td>
                            <th class="px-6 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">Dirección:
                            </th>
                            <td class="px-6 py-3 border-r dark:border-gray-700 text-gray-600 dark:text-gray-300">
                                {{ auth()->user()->address ?? 'No especificado' }}
                            </td>
                            <th class="px-6 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">Género:</th>
                            <td class="px-6 py-3 text-gray-600 dark:text-gray-300">
                                {{ auth()->user()->gender ?? 'No especificado' }}
                            </td>
                        </tr>
                        <!-- Cuarta fila: Fecha de ingreso y próxima entrega -->
                        <tr class="border-b dark:border-gray-700 bg-white dark:bg-gray-900">
                            <th class="px-6 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">Fecha de Ingreso:</th>
                            <td class="px-6 py-3 border-r dark:border-gray-700 text-gray-600 dark:text-gray-300">
                                {{ auth()->user()->admission_date ? auth()->user()->admission_date->format('d/m/Y') : 'No especificado' }}
                            </td>
                            <th class="px-6 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">Próxima Entrega:</th>
                            <td class="px-6 py-3 text-gray-600 dark:text-gray-300">
                                {{ auth()->user()->getNextDeliveryDate()?->format('d/m/Y') ?? 'No programada' }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Grid de 2 columnas para patologías y medicamentos -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-2">
                <!-- Card de Patologías -->
                <div class="bg-white rounded-lg shadow-md dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                    <!-- Header de la card -->
                    <div class="flex justify-between items-center p-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Mis Patologías</h3>
                    </div>
                    <!-- Contenido de la card -->
                    <div class="p-4">
                        @if (auth()->user()->patientPathologies && auth()->user()->patientPathologies->count() > 0)
                            <div class="relative overflow-x-auto rounded-lg shadow-md dark:shadow-none">
                                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    <thead
                                        class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">Patología</th>
                                            <th scope="col" class="px-6 py-3">Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (auth()->user()->patientPathologies as $key => $pathology)
                                            <tr
                                                class="{{ $key % 2 === 0 ? 'bg-white dark:bg-gray-800' : 'bg-gray-50 dark:bg-gray-700' }} border-b dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-600">
                                                <td
                                                    class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                    {{ $pathology->pathology->clave }} -
                                                    {{ Str::limit($pathology->pathology->descripcion, 40) }}
                                                </td>
                                                <td class="px-6 py-2">
                                                    <span
                                                        class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full border {{ $pathology->status === 'active' ? 'text-green-600 border-green-600 dark:text-green-400 dark:border-green-400' : 'text-gray-600 border-gray-600 dark:text-gray-400 dark:border-gray-400' }}">
                                                        {{ $pathology->status === 'active' ? 'Activa' : 'Inactiva' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center text-gray-500 dark:text-gray-400 py-8">
                                No hay patologías asignadas.
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Card de Medicamentos -->
                <div class="bg-white rounded-lg shadow-md dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                    <!-- Header de la card -->
                    <div class="flex justify-between items-center p-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Mis Medicamentos</h3>
                    </div>
                    <!-- Contenido de la card -->
                    <div class="p-4">
                        @php
                            $userMedicines = \App\Models\PatientMedicine::where('user_id', auth()->user()->id)
                                ->with('medicine')
                                ->get();
                        @endphp
                        @if ($userMedicines->count() > 0)
                            <div class="relative overflow-x-auto rounded-lg shadow-md dark:shadow-none">
                                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    <thead
                                        class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">Medicamento</th>
                                            <th scope="col" class="px-6 py-3">Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($userMedicines as $key => $medicine)
                                            <tr
                                                class="{{ $key % 2 === 0 ? 'bg-white dark:bg-gray-800' : 'bg-gray-50 dark:bg-gray-700' }} border-b dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-600">
                                                <td
                                                    class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                    {{ $medicine->medicine->generic_name }}
                                                </td>
                                                <td class="px-6 py-2">
                                                    <span
                                                        class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full border {{ $medicine->status === 'active' ? 'text-green-600 border-green-600 dark:text-green-400 dark:border-green-400' : 'text-gray-600 border-gray-600 dark:text-gray-400 dark:border-gray-400' }}">
                                                        {{ $medicine->status === 'active' ? 'Activo' : 'Inactivo' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center text-gray-500 dark:text-gray-400 py-8">
                                No hay medicamentos asignados.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </x-layouts.app>
@endrole
