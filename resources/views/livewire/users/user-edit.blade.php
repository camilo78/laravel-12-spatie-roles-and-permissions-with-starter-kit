<div>
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Edit User') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Form For Edit User') }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <div>
        <a wire:navigate href="{{ route('users.index') }}" class="cursor-pointer px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Regresar a Usuarios
        </a>

        <div>
            <form class="mt-6 space-y-6" wire:submit="editUser">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <flux:input label="{{ __('Name') }}" type="text" name="name" placeholder="Digite el Nombre" wire:model="name" />
                    <flux:input label="{{ __('Email') }}" type="email" name="email" placeholder="Digite el Correo" wire:model="email" />
                    <flux:input label="{{ __('DNI') }}" type="text" name="dui" placeholder="Digite DNI" wire:model="dui" />
                    <flux:input label="{{ __('Phone') }}" type="text" name="phone" placeholder="Digite el Teléfono" wire:model="phone" />
                    <flux:textarea label="{{ __('Address') }}" type="text" name="address" placeholder="Digite la Dirección" wire:model="address" class="lg:col-span-2" />
                    <flux:select label="{{ __('Gender') }}" name="gender" wire:model="gender">
                        <option value="">Seleccione Genero</option>
                        <option value="Masculino">Masculino</option>
                        <option value="Femenino">Femenino</option>
                    </flux:select>
                    <flux:input label="{{ __('Password') }}" type="password" name="password" placeholder="Digite la Contraseña" wire:model="password" />
                    <flux:input label="{{ __('Confirm Password') }}" type="password" name="confirm_password" placeholder="Digite la Contraseña (Nuevamente)" wire:model="confirm_password" />
                    <div class="lg:col-span-2">
                        <flux:checkbox.group wire:model="roles" label="Roles">
                            @foreach($allRoles as $allRole)
                                <flux:checkbox value="{{ $allRole->name }}" label="{{ $allRole->name }}" />
                            @endforeach
                        </flux:checkbox.group>
                    </div>
                </div>
                <flux:button type="submit" variant="primary">Actualizar Usuario</flux:button>
            </form>
        </div>
    </div>
</div>
