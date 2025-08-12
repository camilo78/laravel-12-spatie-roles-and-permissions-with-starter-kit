<div>
    {{-- Encabezado de la página con nombre de la localidad --}}
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ $locality->name }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">Detalles de la localidad</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <div>
        {{-- Botones de acción --}}
        <div class="mt-6">
            <div class="flex gap-2 mb-6">
                {{-- Botón para editar la localidad --}}
                <a wire:navigate href="{{ route('localities.edit', $locality->id) }}"
                    class="px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Editar
                </a>
                {{-- Botón para volver al listado --}}
                <a wire:navigate href="{{ route('localities.index') }}"
                    class="px-3 py-2 text-xs font-medium text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                    Volver
                </a>
            </div>
        </div>

        {{-- Tabla con detalles de la localidad --}}
        <div class="overflow-x-auto mt-6">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 border dark:border-gray-700">
                <tbody>
                    {{-- Fila con nombre y municipio --}}
                    <tr class="border-b dark:border-gray-700 bg-white dark:bg-gray-900">
                        <th class="px-6 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">Nombre:</th>
                        <td class="px-6 py-3 border-r dark:border-gray-700 text-gray-600 dark:text-gray-300">
                            {{ $locality->name }}
                        </td>
                        <th class="px-6 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">Municipio:</th>
                        <td class="px-6 py-3 text-gray-600 dark:text-gray-300">
                            {{ $locality->municipality->name }}
                        </td>
                    </tr>
                    {{-- Fila con departamento y fechas --}}
                    <tr class="bg-white dark:bg-gray-900">
                        <th class="px-6 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">Departamento:</th>
                        <td class="px-6 py-3 border-r dark:border-gray-700 text-gray-600 dark:text-gray-300">
                            {{ $locality->municipality->department->name }}
                        </td>
                        <th class="px-6 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">Creada:</th>
                        <td class="px-6 py-3 text-gray-600 dark:text-gray-300">
                            {{ $locality->created_at?->format('d/m/Y H:i') ?? 'N/A' }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>