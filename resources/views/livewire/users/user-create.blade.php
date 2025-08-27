<div>
    {{-- Encabezado de la página --}}
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Crear Usuario</flux:heading>
        <flux:subheading size="lg" class="mb-6">Formulario para Crear Nuevo Usuario</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <div>
        {{-- Botón para regresar al listado de usuarios --}}
        <a wire:navigate href="{{ route('users.index') }}"
            class="cursor-pointer px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Regresar a Usuarios
        </a>

        <div>
            {{-- Formulario principal para crear usuario --}}
            <form class="mt-6 space-y-6" wire:submit="createUser">
                {{-- Grid responsivo de 2 columnas en pantallas grandes --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    
                    {{-- Campo de nombre completo --}}
                    <flux:input 
                        label="Nombre Completo" 
                        type="text" 
                        name="name" 
                        placeholder="Ingrese el nombre completo"
                        wire:model="name" 
                        required 
                        minlength="2" 
                        maxlength="255" 
                        pattern="[a-zA-ZÁÉÍÓÚáéíóúñÑ\s]+" 
                        title="Solo se permiten letras y espacios" />
                    
                    {{-- Campo de correo electrónico --}}
                    <flux:input 
                        label="Correo Electrónico (Opcional)" 
                        type="email" 
                        name="email" 
                        placeholder="ejemplo@correo.com"
                        wire:model="email" 
                        maxlength="255" />
                    
                    {{-- Campo de DNI (Documento Nacional de Identidad) --}}
                    <flux:input 
                        label="DNI" 
                        type="text" 
                        name="dni" 
                        placeholder="0000000000000"
                        wire:model="dni" 
                        required 
                        pattern="[0-9]{13}" 
                        maxlength="13" 
                        title="Formato: 0501197809263" />
                    
                    {{-- Campo de teléfono --}}
                    <flux:input 
                        label="Teléfonos" 
                        type="tel" 
                        name="phone" 
                        placeholder="00000000"
                        wire:model="phone"/>
                    
                    {{-- Selector de departamento --}}
                    <flux:select 
                        label="Departamento" 
                        name="department_id" 
                        wire:model.live="department_id" 
                        required>
                        <option value="">Seleccione el departamento</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </flux:select>

                    {{-- Indicador de carga para municipios --}}
                    <div wire:loading wire:target="department_id" class="text-sm text-gray-500 mt-1">
                        Cargando municipios...
                    </div>
                    
                    {{-- Selector de municipio (depende del departamento seleccionado) --}}
                    <flux:select 
                        label="Municipio" 
                        name="municipality_id" 
                        wire:model.live="municipality_id"
                        :disabled="!$department_id" 
                        required>
                        <option value="">
                            {{ !$department_id ? 'Seleccione un departamento primero' : 'Seleccione el municipio' }}
                        </option>
                        @foreach ($municipalities as $municipality)
                            <option value="{{ $municipality->id }}">{{ $municipality->name }}</option>
                        @endforeach
                    </flux:select>
                    
                    {{-- Indicador de carga para localidades --}}
                    <div wire:loading wire:target="municipality_id" class="text-sm text-gray-500 mt-1">
                        Cargando localidades...
                    </div>
                    
                    {{-- Input searcheable de localidad --}}
                    <div class="relative">
                        <flux:input 
                            label="Localidad" 
                            name="locality_search" 
                            placeholder="{{ !$municipality_id ? 'Seleccione un municipio primero' : 'Buscar localidad...' }}"
                            wire:model.live.debounce.300ms="locality_search"
                            :disabled="!$municipality_id" 
                            autocomplete="off"
                            required />
                        
                        {{-- Lista de resultados --}}
                        @if($municipality_id && $locality_search && count($filtered_localities) > 0)
                            <div class="absolute z-10 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-lg max-h-60 overflow-auto">
                                @foreach($filtered_localities as $locality)
                                    <div class="px-4 py-2 text-gray-900 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer" 
                                         wire:click="selectLocality({{ $locality->id }}, '{{ $locality->name }}')">
                                        {{ $locality->name }}
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        
                        {{-- Campo oculto para el ID --}}
                        <input type="hidden" name="locality_id" wire:model="locality_id" />
                    </div>
                    
                    {{-- Campo de dirección (ocupa 2 columnas en pantallas grandes) --}}
                    <flux:textarea 
                        label="Dirección Completa" 
                        name="address" 
                        placeholder="Ingrese la dirección detallada"
                        wire:model="address" 
                        class="lg:col-span-2" 
                        required 
                        minlength="10" 
                        maxlength="500" 
                        rows="3" />
                    
                    {{-- Selector de género --}}
                    <flux:select 
                        label="Género" 
                        name="gender" 
                        wire:model="gender" 
                        required>
                        <option value="">Seleccione el género</option>
                        <option value="Masculino">Masculino</option>
                        <option value="Femenino">Femenino</option>
                    </flux:select>
                    
                    {{-- Campo de fecha de ingreso --}}
                    <flux:input 
                        label="Fecha de Ingreso" 
                        type="date" 
                        name="admission_date" 
                        wire:model="admission_date" 
                        required />
                    

                    
                    {{-- Sección de roles (ocupa 2 columnas en pantallas grandes) --}}
                    <div class="lg:col-span-2">
                        <flux:checkbox.group wire:model="roles" label="Roles de Usuario">
                            {{-- Itera sobre todos los roles disponibles --}}
                            @foreach ($allRoles as $allRole)
                                <flux:checkbox value="{{ $allRole->name }}" label="{{ __($allRole->name) }}" />
                            @endforeach
                        </flux:checkbox.group>
                    </div>
                </div>
                
                {{-- Sección de botones de acción --}}
                <div class="flex justify-end gap-3">
                    <a wire:navigate href="{{ route('users.index') }}"
                        class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600 transition-colors">
                        Cancelar
                    </a>
                    <flux:button 
                        type="submit" 
                        variant="primary" 
                        :disabled="$isSubmitting">
                        <span wire:loading.remove wire:target="createUser">Crear Usuario</span>
                        <span wire:loading wire:target="createUser">Creando...</span>
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
</div>
