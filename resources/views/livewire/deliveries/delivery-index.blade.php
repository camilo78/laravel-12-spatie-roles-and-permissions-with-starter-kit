<div>
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Entregas de Medicamentos</flux:heading>
        <flux:subheading size="lg" class="mb-6">Gestiona las entregas programadas</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <div class="flex justify-between items-center mb-4">
        <a wire:navigate href="{{ route('deliveries.create') }}"
            class="px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800">
            Nueva Entrega
        </a>
        <input type="search" wire:model.live.debounce.300ms="search" placeholder="Buscar entrega..."
            class="px-4 py-2 text-sm border border-gray-300 rounded-md dark:bg-gray-800 dark:border-gray-600" />
    </div>

    <div class="overflow-x-auto rounded-lg shadow-md">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-6 py-3">Nombre</th>
                    <th class="px-6 py-3">Fecha Inicio</th>
                    <th class="px-6 py-3">Fecha Fin</th>
                    <th class="px-6 py-3">Estado</th>
                    <th class="px-6 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($deliveries as $delivery)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-600">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $delivery->name }}</td>
                        <td class="px-6 py-4">{{ $delivery->start_date->format('d/m/Y') }}</td>
                        <td class="px-6 py-4">{{ $delivery->end_date->format('d/m/Y') }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full 
                                {{ $delivery->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($delivery->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">
                                {{ ucfirst($delivery->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-1">
                                @if($delivery->isEditable())
                                    <a wire:navigate href="{{ route('deliveries.edit', $delivery) }}"
                                        class="inline-flex items-center px-3 py-2 text-xs bg-white border border-green-600 rounded-lg hover:bg-green-100">
                                        <flux:icon.square-pen variant="micro" class="text-green-600" />
                                    </a>
                                @endif
                                <a wire:navigate href="{{ route('deliveries.show', $delivery) }}"
                                    class="inline-flex items-center px-3 py-2 text-xs bg-white border border-gray-600 rounded-lg hover:bg-gray-100">
                                    <flux:icon.eye variant="micro" class="text-gray-600" />
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No se encontraron entregas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">{{ $deliveries->links() }}</div>
</div>