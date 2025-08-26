<div>
    {{-- Encabezado de la página --}}
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Entregas de Medicamentos</flux:heading>
        <flux:subheading size="lg" class="mb-6">Gestiona las entregas programadas</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    {{-- Sección de controles --}}
    <div class="flex justify-between items-center mb-4">
        {{-- Botón para crear nueva entrega --}}
        <a wire:navigate href="{{ route('deliveries.create') }}"
            class="px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Nueva Entrega
        </a>
        {{-- Campo de búsqueda --}}
        <input type="search" wire:model.live.debounce.300ms="search" placeholder="Buscar entrega..."
            class="px-4 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white" />
    </div>

    {{-- Tabla de entregas --}}
    <div class="relative overflow-x-auto rounded-lg shadow-md dark:shadow-none mt-4 w-full">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            {{-- Encabezados de la tabla --}}
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">Nombre</th>
                    <th scope="col" class="px-6 py-3">Fecha Inicio</th>
                    <th scope="col" class="px-6 py-3">Fecha Fin</th>
                    <th scope="col" class="px-6 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                {{-- Iteración sobre las entregas --}}
                @forelse($deliveries as $key => $delivery)
                    <tr class="{{ $key % 2 === 0 ? 'bg-white dark:bg-gray-800' : 'bg-gray-50 dark:bg-gray-700' }} border-b dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-600">
                        {{-- Nombre de la entrega --}}
                        <td class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $delivery->name }}</td>
                        {{-- Fecha de inicio formateada --}}
                        <td class="px-6 py-2 text-gray-600 dark:text-gray-300">{{ $delivery->start_date->translatedFormat('j \d\e F \d\e Y') }}</td>
                        {{-- Fecha de fin formateada --}}
                        <td class="px-6 py-2 text-gray-600 dark:text-gray-300">{{ $delivery->end_date->translatedFormat('j \d\e F \d\e Y') }}</td>                        
                        {{-- Botones de acción --}}
                        <td class="px-6 py-2 text-center">
                            <div class="flex flex-col gap-2 w-full sm:flex-row sm:w-auto sm:justify-center lg:flex-row lg:w-auto lg:gap-1 lg:flex-nowrap">
                                {{-- Botón editar (solo si es editable) --}}
                                @if($delivery->isEditable())
                                    <a wire:navigate href="{{ route('deliveries.edit', $delivery) }}"
                                        class="inline-flex items-center justify-center px-3 py-2 text-xs font-medium bg-white border border-gray-600 rounded-lg hover:bg-red-50 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-gray-700 dark:border-white dark:hover:bg-red-900 dark:focus:ring-red-800 flex-grow sm:flex-none">
                                        <flux:icon.square-pen variant="micro" class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300" />
                                    </a>
                                @endif
                                {{-- Botón ver detalles --}}
                                <a wire:navigate href="{{ route('deliveries.show', $delivery) }}"
                                    class="inline-flex items-center justify-center px-3 py-2 text-xs font-medium bg-white border border-gray-600 rounded-lg hover:bg-red-50 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-gray-700 dark:border-white dark:hover:bg-red-900 dark:focus:ring-red-800 flex-grow sm:flex-none">
                                    <flux:icon.eye variant="micro" class="text-gray-600 dark:text-gray-300 hover:text-gray-700 dark:hover:text-gray-200" />
                                </a>
                                {{-- Botón eliminar (solo si es eliminable) --}}
                                @if($delivery->isDeletable())
                                    <button wire:click="deleteDelivery({{ $delivery->id }})" 
                                        wire:confirm="¿Estás seguro de eliminar esta entrega?"
                                        class="inline-flex items-center justify-center px-3 py-2 text-xs font-medium bg-white border border-gray-600 rounded-lg hover:bg-red-50 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-gray-700 dark:border-white dark:hover:bg-red-900 dark:focus:ring-red-800 flex-grow sm:flex-none">
                                        <flux:icon.trash variant="micro" class="text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300" />
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    {{-- Mensaje cuando no hay entregas --}}
                    <tr class="bg-white dark:bg-gray-800">
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No se encontraron entregas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{-- Paginación --}}
    <div class="mt-4">{{ $deliveries->links() }}</div>
</div>