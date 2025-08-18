<div>
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Crear Rol</flux:heading>
        <flux:subheading size="lg" class="mb-6">Formulario para Crear Nuevo Rol</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <div>
        <a wire:navigate href="{{ route('roles.index') }}" class="cursor-pointer px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Regresar
        </a>

        <div>
            <form class="mt-6 space-y-6" wire:submit="createRole">
                <flux:input label="Nombre" type="text" name="name" placeholder="Ingrese el nombre" wire:model="name" />
                <div>
                    <flux:label>Permisos</flux:label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-2">
                        @foreach($allPermissions as $allPermission)
                            <flux:checkbox wire:model="permissions" value="{{ $allPermission->name }}" label="{{ $allPermission->name }}" />
                        @endforeach
                    </div>
                </div>
                <div class="flex justify-end">
                    <flux:button type="submit" variant="primary">Crear Rol</flux:button>
                </div>
            </form>
        </div>
    </div>
</div>
