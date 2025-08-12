<x-layouts.app>
    {{-- Encabezado de la página --}}
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Editar Medicamento</flux:heading>
        <flux:subheading size="lg" class="mb-6">Formulario para editar medicamento</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <div>
        {{-- Botón para regresar al listado de medicamentos --}}
        <div class="mt-6">
            <a href="{{ route('medicines.index') }}"
                class="px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Regresar a Medicamentos
            </a>
        </div>

        {{-- Formulario para editar medicamento existente --}}
        <div class="mt-6">
            <form class="space-y-6" action="{{ route('medicines.update', $medicine) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Campo nombre del medicamento con valor actual --}}
                    <flux:input label="Nombre" type="text" name="name"
                        placeholder="Digite el nombre del medicamento" value="{{ old('name', $medicine->name) }}"
                        required />

                    {{-- Campo nombre genérico con valor actual --}}
                    <flux:input label="Nombre Genérico" type="text" name="generic_name"
                        placeholder="Digite el nombre genérico"
                        value="{{ old('generic_name', $medicine->generic_name) }}" required />

                    {{-- Campo presentación del medicamento con valor actual --}}
                    <flux:input label="Presentación" type="text" name="presentation"
                        placeholder="Ej: Tabletas, Jarabe, Cápsulas"
                        value="{{ old('presentation', $medicine->presentation) }}" required />

                    {{-- Campo concentración del medicamento con valor actual --}}
                    <flux:input label="Concentración" type="text" name="concentration" placeholder="Ej: 500mg, 250ml"
                        value="{{ old('concentration', $medicine->concentration) }}" required />
                </div>
                
                {{-- Botón de actualización --}}
                <div class="flex justify-end mt-6 lg:col-span-2">
                    <flux:button type="submit" variant="primary">Actualizar Medicamento</flux:button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
