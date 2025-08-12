<div>
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Nueva Localidad</flux:heading>
        <flux:subheading size="lg" class="mb-6">Formulario para crear nueva localidad</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <div>
        {{-- Botón para regresar al listado de localidades --}}
        <div class="mt-6">
            <a wire:navigate href="{{ route('localities.index') }}"
                class="px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Regresar a Localidades
            </a>
        </div>

        {{-- Formulario para crear nueva localidad --}}
        <div class="mt-6">
            <form wire:submit="save" class="space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Campo departamento --}}
                    <flux:select label="Departamento" wire:model.live="selectedDepartment" required>
                        <option value="">Seleccionar Departamento</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </flux:select>

                    {{-- Campo municipio --}}
                    <flux:select label="Municipio" wire:model.live="selectedMunicipality" required>
                        <option value="">Seleccionar Municipio</option>
                        @foreach($municipalities as $municipality)
                            <option value="{{ $municipality->id }}">{{ $municipality->name }}</option>
                        @endforeach
                    </flux:select>

                    {{-- Campo nombre de la localidad (ocupa 2 columnas) --}}
                    <flux:input label="Nombre de la Localidad" type="text" wire:model="name" 
                        placeholder="Digite el nombre de la localidad" class="lg:col-span-2" required />
                    
                    {{-- Botón de envío --}}
                    <div class="flex justify-end mt-6 lg:col-span-2">
                        <flux:button type="submit" variant="primary" wire:loading.attr="disabled" wire:target="save">
                            <span wire:loading.remove wire:target="save">Crear Localidad</span>
                            <span wire:loading wire:target="save">Creando...</span>
                        </flux:button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>