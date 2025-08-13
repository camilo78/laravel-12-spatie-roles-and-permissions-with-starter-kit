<div>
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Medicamentos</flux:heading>
        <flux:subheading size="lg" class="mb-6">Gestiona todos los medicamentos del sistema</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <div>
        @session('success')
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                x-transition:leave="transition ease-out duration-500" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="flex items-center p-2 mb-4 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50 dark:bg-green-900 dark:text-green-300 dark:border-green-800"
                role="alert">
                <svg class="flex-shrink-0 w-8 h-8 mr-1 text-green-700 dark:text-green-300"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"></path>
                </svg>
                <span class="font-medium flex-1"> {{ $value }} </span>
                <button @click="show = false" type="button"
                    class="ml-2 text-green-800 hover:text-green-900 dark:text-green-300 dark:hover:text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endsession

        <div class="flex flex-col sm:flex-col lg:flex-row sm:items-start lg:items-center justify-between gap-4 mb-4">
            <div class="flex flex-row items-center gap-3 justify-between order-1 sm:justify-between sm:w-full lg:w-auto lg:justify-start lg:order-1">
                <a wire:navigate href="{{ route('medicines.create') }}"
                    class="px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 flex-grow sm:flex-grow lg:flex-grow-0">
                    Crear Medicamento
                </a>
            </div>
            <div class="relative order-2 sm:w-full lg:w-auto lg:order-2 ml-auto">
                <input type="search" wire:model.live.debounce.300ms="search" placeholder="Buscar medicamento..."
                    class="w-full px-4 py-2 text-sm text-gray-900 placeholder-gray-500 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 dark:bg-gray-800 dark:text-white dark:placeholder-gray-400 dark:border-gray-700" />
            </div>
        </div>

        <div class="relative overflow-x-auto rounded-lg shadow-md dark:shadow-none mt-4 w-full">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Nombre</th>
                        <th scope="col" class="px-6 py-3">Nombre Genérico</th>
                        <th scope="col" class="px-6 py-3">Presentación</th>
                        <th scope="col" class="px-6 py-3">Concentración</th>
                        <th scope="col" class="px-6 py-3 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($medicines as $key => $medicine)
                        <tr class="{{ $key % 2 === 0 ? 'bg-white dark:bg-gray-800' : 'bg-gray-50 dark:bg-gray-700' }} border-b dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-600">
                            <td class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $medicine->name }}
                            </td>
                            <td class="px-6 py-2 text-gray-600 dark:text-gray-300">
                                {{ $medicine->generic_name }}
                            </td>
                            <td class="px-6 py-2 text-gray-600 dark:text-gray-300">
                                {{ $medicine->presentation }}
                            </td>
                            <td class="px-6 py-2 text-gray-600 dark:text-gray-300">
                                {{ $medicine->concentration }}
                            </td>
                            <td class="px-6 py-2 text-center">
                                <div class="flex flex-col gap-2 w-full sm:flex-row sm:w-auto sm:justify-center lg:flex-row lg:w-auto lg:gap-1 lg:flex-nowrap">
                                    <a wire:navigate href="{{ route('medicines.show', $medicine) }}"
                                        class="inline-flex items-center justify-center px-3 py-2 text-xs font-medium bg-white border border-gray-600 rounded-lg hover:bg-red-50 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-gray-700 dark:border-white dark:hover:bg-red-900 dark:focus:ring-red-800 flex-grow sm:flex-none" title="Ver Medicamento">
                                        <flux:icon.eye variant="micro" class="text-gray-600 dark:text-gray-300 hover:text-gray-700 dark:hover:text-gray-200"/>
                                    </a>
                                    <a wire:navigate href="{{ route('medicines.edit', $medicine) }}"
                                        class="inline-flex items-center justify-center px-3 py-2 text-xs font-medium bg-white border border-gray-600 rounded-lg hover:bg-red-50 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-gray-700 dark:border-white dark:hover:bg-red-900 dark:focus:ring-red-800 flex-grow sm:flex-none" title="Editar Medicamento">
                                        <flux:icon.square-pen variant="micro" class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300"/>
                                    </a>
                                    <button wire:click="delete({{ $medicine->id }})" wire:confirm="¿Eliminar medicamento?"
                                        class="inline-flex items-center justify-center px-3 py-2 text-xs font-medium bg-white border border-gray-600 rounded-lg hover:bg-red-50 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-gray-700 dark:border-white dark:hover:bg-red-900 dark:focus:ring-red-800 flex-grow sm:flex-none" title="Eliminar Medicamento">
                                        <flux:icon.trash-2 variant="micro" class="text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300"/>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="bg-white dark:bg-gray-800">
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                No se encontraron medicamentos.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $medicines->links('vendor.livewire.tailwind') }}
        </div>
    </div>
</div>