<div>
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Detalle de Medicamento</flux:heading>
        <flux:subheading size="lg" class="mb-6">Información completa del medicamento</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <div>
        <a wire:navigate href="{{ route('medicines.index') }}"
            class="cursor-pointer px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Regresar a Medicamentos
        </a>

        <div class="mt-6 space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <flux:label class="text-sm font-medium text-gray-700 dark:text-gray-300">Nombre</flux:label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $medicine->name }}</p>
                    </div>

                    <div>
                        <flux:label class="text-sm font-medium text-gray-700 dark:text-gray-300">Nombre Genérico</flux:label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $medicine->generic_name }}</p>
                    </div>

                    <div>
                        <flux:label class="text-sm font-medium text-gray-700 dark:text-gray-300">Presentación</flux:label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $medicine->presentation }}</p>
                    </div>

                    <div>
                        <flux:label class="text-sm font-medium text-gray-700 dark:text-gray-300">Concentración</flux:label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $medicine->concentration }}</p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a wire:navigate href="{{ route('medicines.index') }}"
                    class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600 transition-colors">
                    Volver
                </a>
                <a wire:navigate href="{{ route('medicines.edit', $medicine) }}"
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Editar
                </a>
            </div>
        </div>
    </div>
</div>