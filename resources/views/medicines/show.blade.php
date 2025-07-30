<x-layouts.app>
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ $medicine->name }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">Detalles del medicamento</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <div>
        <div class="mt-6">
            <div class="flex gap-2 mb-6">
                <a href="{{ route('medicines.edit', $medicine) }}"
                    class="cursor-pointer px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Editar
                </a>
                <a href="{{ route('medicines.index') }}"
                    class="cursor-pointer px-3 py-2 text-xs font-medium text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                    Volver
                </a>
            </div>
        </div>

        <div class="overflow-x-auto mt-6">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 border dark:border-gray-700">
                <tbody>
                    <tr class="border-b dark:border-gray-700 bg-white dark:bg-gray-900">
                        <th class="px-6 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">Nombre</th>
                        <td class="px-6 py-3 text-gray-600 dark:text-gray-300">{{ $medicine->name }}</td>
                        <th class="px-6 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">Nombre Comercial</th>
                        <td class="px-6 py-3 text-gray-600 dark:text-gray-300">{{ $medicine->commercial_name ?: 'No especificado' }}</td>
                    </tr>
                    <tr>
                        <th class="px-6 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">Nombre Genérico</th>
                        <td class="px-6 py-3 text-gray-600 dark:text-gray-300">{{ $medicine->generic_name }}</td>
                        <th class="px-6 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">Presentación</th>
                        <td class="px-6 py-3 text-gray-600 dark:text-gray-300">{{ $medicine->presentation }}</td>
                    </tr>
                    <tr class="border-b dark:border-gray-700 bg-white dark:bg-gray-900">
                        <th class="px-6 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">Concentración</th>
                        <td class="px-6 py-3 text-gray-600 dark:text-gray-300" colspan="3">{{ $medicine->concentration }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>