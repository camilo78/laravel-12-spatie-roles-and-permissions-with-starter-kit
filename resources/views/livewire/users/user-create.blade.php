<div>
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Create User') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Form For Create New User') }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <div>
        <a wire:navigate href="{{ route('users.index') }}"
            class="cursor-pointer px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Regresar a Usuarios
        </a>

        <div>
            <form class="mt-6 space-y-6" wire:submit="createUser">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <flux:input label="Nombre" type="text" name="name" placeholder="Digite el Nombre"
                        wire:model="name" />
                    <flux:input label="Correo Electrónico" type="email" name="email" placeholder="Digite el Correo"
                        wire:model="email" />
                    <flux:input label="DNI" type="text" name="dui" placeholder="Enter DUI" wire:model="dui" />
                    <flux:input label="Teléfono" type="text" name="phone" placeholder="Digite el teléfono"
                        wire:model="phone" />
                    {{-- 📍 Departamento --}}
                    <flux:select label="Departamento" name="department_id" wire:model.live="department_id">
                        <option value="">Seleccione el Departamento</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </flux:select>

                    <div wire:loading wire:target="department_id" class="text-sm text-gray-500 mt-1">
                        Cargando municipios...
                    </div>
                    {{-- 🏙️ Municipio --}}
                    <flux:select label="Municipio" name="municipality_id" wire:model.live="municipality_id"
                        :disabled="!$department_id">
                        <option value="">
                            @if (!$department_id)
                                Seleccione un departamento primero
                            @else
                                Seleccione el Municipio
                            @endif
                        </option>
                        @foreach ($municipalities as $municipality)
                            <option value="{{ $municipality->id }}">{{ $municipality->name }}</option>
                        @endforeach
                    </flux:select>
                    <div wire:loading wire:target="municipality_id" class="text-sm text-gray-500 mt-1">
                        Cargando localidades...
                    </div>
                    {{-- 🧭 Localidad --}}
                    <flux:select label="Localidad" name="locality_id" wire:model.live="locality_id"
                        :disabled="!$municipality_id">
                        <option value="">
                            @if (!$municipality_id)
                                Seleccione un municipio primero
                            @else
                                Seleccione la Localidad
                            @endif
                        </option>
                        @foreach ($localities as $locality)
                            <option value="{{ $locality->id }}">{{ $locality->name }}</option>
                        @endforeach
                    </flux:select>
                    <flux:textarea label="Dirección" type="text" name="address" placeholder="Digite la Dirección"
                        wire:model="address" class="lg:col-span-2" />
                    <flux:select label="Género" name="gender" wire:model="gender">
                        <option value="">Selecione el Género</option>
                        <option value="Masculino">Masculino</option>
                        <option value="Femenino">Femenino</option>
                    </flux:select>
                    <flux:input label="Contraseña" type="password" name="password" placeholder="Digite la Contraseña"
                        wire:model="password" />
                    <flux:input label="Confirma Contraseña" type="password" name="confirm_password"
                        placeholder="Digite la Contraseña (Nuevamente)" wire:model="confirm_password" />
                    <div class="lg:col-span-2">
                        <flux:checkbox.group wire:model="roles" label="Roles de Usuario">
                            @foreach ($allRoles as $allRole)
                                <flux:checkbox value="{{ $allRole->name }}" label="{{ __($allRole->name) }}" />
                            @endforeach
                        </flux:checkbox.group>
                    </div>
                </div>
                <flux:button type="submit" variant="primary">{{ __('Create User') }}</flux:button>
            </form>
        </div>
    </div>
</div>
