<div>
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Locality Details') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('View locality information') }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <div class="max-w-2xl">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre</label>
                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $locality->name }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Municipio</label>
                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $locality->municipality->name }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Departamento</label>
                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $locality->municipality->department->name }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha de Creación</label>
                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $locality->created_at?->format('d/m/Y H:i') ?? 'N/A' }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Última Actualización</label>
                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $locality->updated_at?->format('d/m/Y H:i') ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-6">
            <a wire:navigate href="{{ route('localities.index') }}"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                Volver
            </a>
            <a wire:navigate href="{{ route('localities.edit', $locality->id) }}"
                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Editar
            </a>
        </div>
    </div>
</div>