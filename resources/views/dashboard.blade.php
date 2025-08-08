@role('Admin')
    <x-layouts.app :title="__('Dashboard - Admin')">
        <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm">
                    <div class="p-4 border-b border-neutral-200 dark:border-neutral-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Usuarios Registrados</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total: {{ $totalUsers }} usuarios | Activos {{ $activeUsers }}, Inactivos {{ $inactiveUsers }}</p>
                    </div>
                    <div class="p-4 h-64">
                        {!! $chart->render() !!}
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm">
                    <div class="p-4 border-b border-neutral-200 dark:border-neutral-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Distribución por Género</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Masculino: {{ $maleUsers }} | Femenino: {{ $femaleUsers }}</p>
                    </div>
                    <div class="p-4 h-64">
                        {!! $genderChart->render() !!}
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm">
                    <div class="p-4 border-b border-neutral-200 dark:border-neutral-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Usuarios por Municipio</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Distribución completa por municipios</p>
                    </div>
                    <div class="p-4 h-80">
                        {!! $municipalityChart->render() !!}
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm">
                <div class="p-4 border-b border-neutral-200 dark:border-neutral-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Entregas de Medicamentos</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Usuarios por entrega programada</p>
                </div>
                <div class="p-4 h-80">
                    {!! $deliveryChart->render() !!}
                </div>
            </div>
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm">
                <div class="p-4 border-b border-neutral-200 dark:border-neutral-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white"></h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400"></p>
                </div>
                <div class="p-4 h-80">

                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm">
                <div class="p-4 border-b border-neutral-200 dark:border-neutral-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white"></h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400"></p>
                </div>
                <div class="p-4 h-80">

                </div>
            </div>
            </div>
        </div>
    </x-layouts.app>
@else
    <x-layouts.app :title="__('Dashboard - User')">
        <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm">
                    <div class="p-4 border-b border-neutral-200 dark:border-neutral-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Usuarios Registrados</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total: {{ $totalUsers }} usuarios</p>
                    </div>
                    <div class="p-4 h-64">
                        {!! $chart->render() !!}
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm">
                    <div class="p-4 border-b border-neutral-200 dark:border-neutral-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Distribución por Género</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Estadísticas demográficas</p>
                    </div>
                    <div class="p-4 h-64">
                        {!! $genderChart->render() !!}
                    </div>
                </div>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                    <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
                </div>
            </div>
            <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
        </div>
    </x-layouts.app>
@endrole
