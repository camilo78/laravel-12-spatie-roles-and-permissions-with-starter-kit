<x-layouts.app>
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Editar Patología</flux:heading>
        <flux:subheading size="lg" class="mb-6">Formulario para editar patología</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <div>
        <div class="mt-6">
            <a href="{{ route('pathologies.index') }}"
                class="cursor-pointer px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Regresar a Patologías
            </a>
        </div>

        <div>
            <form class="mt-6 space-y-6" action="{{ route('pathologies.update', $pathology) }}" method="POST">
                @csrf @method('PUT')
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <flux:input label="Nombre" type="text" name="name" placeholder="Digite el nombre de la patología"
                        value="{{ old('name', $pathology->name) }}" required />
                    <flux:input label="Código" type="text" name="code" placeholder="Digite el código (ej: CIE-10)"
                        value="{{ old('code', $pathology->code) }}" required />
                    <flux:textarea label="Descripción" name="description" placeholder="Digite la descripción de la patología"
                        class="lg:col-span-2">{{ old('description', $pathology->description) }}</flux:textarea>
                </div>
                <flux:button type="submit" variant="primary">Actualizar Patología</flux:button>
            </form>
        </div>
    </div>
</x-layouts.app>