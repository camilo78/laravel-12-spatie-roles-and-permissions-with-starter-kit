<x-layouts.app>
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Nueva Patología</flux:heading>
        <flux:subheading size="lg" class="mb-6">Formulario para crear nueva patología</flux:subheading>
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
            <form class="mt-6 space-y-6" action="{{ route('pathologies.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <flux:input label="Nombre" type="text" name="name" placeholder="Digite el nombre de la patología"
                        value="{{ old('name') }}" required />
                    <flux:input label="Código" type="text" name="code" placeholder="Digite el código (ej: CIE-10)"
                        value="{{ old('code') }}" required />
                    <flux:textarea label="Descripción" name="description" placeholder="Digite la descripción de la patología"
                        class="lg:col-span-2">{{ old('description') }}</flux:textarea>
                </div>
                <flux:button type="submit" variant="primary">Crear Patología</flux:button>
            </form>
        </div>
    </div>
</x-layouts.app>