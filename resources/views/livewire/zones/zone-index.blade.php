<div>
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Zonas') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Administra todas tus zonas por municipio') }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <div>
        @session('success')
            {{-- Bloque de alerta de éxito --}}
        @endsession

        <div class="mb-6">
            <flux:select wire:model.live="municipality_id" label="Seleccione un Municipio para continuar">
                <option value="">-- Seleccionar --</option>
                @foreach($municipalities as $municipality)
                    <option value="{{ $municipality->id }}">{{ $municipality->name }}</option>
                @endforeach
            </flux:select>
        </div>
        @if($municipality_id)
            <div class="flex flex-col sm:flex-col lg:flex-row sm:items-start lg:items-center justify-between gap-4 mb-4">
                <div class="flex flex-row items-center gap-3">
                    <a wire:navigate href="{{ route('zones.create', ['municipality_id' => $municipality_id]) }}" class="px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800">
                        Crear Zona
                    </a>
                </div>
            </div>

            <div class="relative overflow-x-auto rounded-lg shadow-md dark:shadow-none mt-4 w-full">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">ID</th>
                            <th scope="col" class="px-6 py-3">Nombre</th>
                            <th scope="col" class="px-6 py-3">Descripción</th>
                            <th scope="col" class="px-6 py-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($zones as $zone)
                            <tr class="bg-white ...">
                                <td class="px-6 py-2">{{ $zone->id }}</td>
                                <td class="px-6 py-2 font-medium ...">{{ $zone->name }}</td>
                                <td class="px-6 py-2">{{ $zone->description }}</td>
                                <td class="px-6 py-2 text-center">
                                    <div class="flex justify-center gap-2">
                                        <a wire:navigate href="{{ route('zones.edit', $zone) }}" class="inline-flex ...">
                                            <flux:icon.square-pen variant="micro" />
                                        </a>
                                        <button wire:click="delete({{ $zone->id }})" wire:confirm="¿Estás seguro?" class="inline-flex ...">
                                            <flux:icon.trash-2 variant="micro" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="bg-white ...">
                                <td colspan="4" class="px-6 py-4 text-center ...">
                                    No se encontraron zonas para este municipio.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($zones->count() > 0)
                <div class="mt-4">
                    {{ $zones->links('vendor.livewire.tailwind') }}
                </div>
            @endif
        @else
            <div class="p-4 text-center text-sm text-gray-500 bg-gray-50 dark:bg-gray-800 dark:text-gray-400 rounded-lg">
                Por favor, selecciona un municipio para ver o crear zonas.
            </div>
        @endif
    </div>
</div>