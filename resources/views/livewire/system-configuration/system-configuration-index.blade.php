<div>
    <!-- Encabezado -->
    <div class="mb-6">
        <flux:heading size="xl" level="1">Configuraciones del Sistema</flux:heading>
        <flux:subheading size="lg" class="mb-6">Personaliza la configuración del hospital y períodos de entrega</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <!-- Mensaje de éxito -->
    @session('success')
        <div x-data="{ show: true }" x-show="show" x-transition:leave="transition ease-out duration-500"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="flex items-center p-2 mb-4 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50 dark:bg-green-900 dark:text-green-300 dark:border-green-800"
            role="alert">
            <svg class="flex-shrink-0 w-8 h-8 mr-1 text-green-700 dark:text-green-300" xmlns="http://www.w3.org/2000/svg"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"></path>
            </svg>
            <span class="font-medium flex-1">{{ $value }}</span>
            <button @click="show = false" type="button"
                class="ml-2 text-green-800 hover:text-green-900 dark:text-green-300 dark:hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endsession

    <form wire:submit="save" class="space-y-6">
        <!-- Información General -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Información General</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <flux:field>
                        <flux:label>Nombre del Hospital</flux:label>
                        <flux:input wire:model="hospital_name" placeholder="Nombre del hospital" />
                        <flux:error name="hospital_name" />
                    </flux:field>
                </div>
                
                <div>
                    <flux:field>
                        <flux:label>Nombre del Programa</flux:label>
                        <flux:input wire:model="program_name" placeholder="Nombre del programa" />
                        <flux:error name="program_name" />
                    </flux:field>
                </div>
                
                <div>
                    <flux:field>
                        <flux:label>Encargada del Programa</flux:label>
                        <flux:input wire:model="program_manager" placeholder="Nombre de la encargada" />
                        <flux:error name="program_manager" />
                    </flux:field>
                </div>
            </div>
        </div>

        <!-- Logos -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Logos</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Logo de la Aplicación -->
                <div>
                    <flux:field>
                        <flux:label>Logo de la Secretaría de Salud</flux:label>
                        <input type="file" wire:model="app_logo" accept="image/*"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                        <flux:error name="app_logo" />
                        
                        @if ($current_app_logo)
                            <div class="mt-2">
                                <img src="{{ Storage::url($current_app_logo) }}" alt="Logo actual" class="h-16 w-auto">
                                <p class="text-xs text-gray-500 mt-1">Logo actual</p>
                            </div>
                        @endif
                        
                        @if ($app_logo)
                            <div class="mt-2" wire:loading.remove wire:target="app_logo">
                                <img src="{{ $app_logo->temporaryUrl() }}" alt="Preview" class="h-16 w-auto">
                                <p class="text-xs text-gray-500 mt-1">Vista previa</p>
                            </div>
                        @endif
                        
                        <div wire:loading wire:target="app_logo" class="mt-2">
                            <p class="text-sm text-gray-500">Cargando imagen...</p>
                        </div>
                    </flux:field>
                </div>

                <!-- Logo del Hospital -->
                <div>
                    <flux:field>
                        <flux:label>Logo del Hospital</flux:label>
                        <input type="file" wire:model="hospital_logo" accept="image/*"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                        <flux:error name="hospital_logo" />
                        
                        @if ($current_hospital_logo)
                            <div class="mt-2">
                                <img src="{{ Storage::url($current_hospital_logo) }}" alt="Logo actual" class="h-16 w-auto">
                                <p class="text-xs text-gray-500 mt-1">Logo actual</p>
                            </div>
                        @endif
                        
                        @if ($hospital_logo)
                            <div class="mt-2" wire:loading.remove wire:target="hospital_logo">
                                <img src="{{ $hospital_logo->temporaryUrl() }}" alt="Preview" class="h-16 w-auto">
                                <p class="text-xs text-gray-500 mt-1">Vista previa</p>
                            </div>
                        @endif
                        
                        <div wire:loading wire:target="hospital_logo" class="mt-2">
                            <p class="text-sm text-gray-500">Cargando imagen...</p>
                        </div>
                    </flux:field>
                </div>
            </div>
        </div>

        <!-- Períodos de Entrega -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Períodos de Entrega</h3>
    <p class="text-sm text-red-600 mb-6">
        Los períodos de entrega deben establecerse durante la fase de implementación de la aplicación. 
        Cualquier modificación posterior puede generar inconsistencias en la lógica del sistema y afectar su funcionamiento.
    </p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Primera Entrega -->
        <flux:field>
            <flux:label>Primera Entrega (días)</flux:label>
            <flux:input type="number" wire:model="first_delivery_days" min="1" max="365" />
            <flux:error name="first_delivery_days" />
            <flux:description>Días después del ingreso para la primera entrega</flux:description>
        </flux:field>

        <!-- Entregas Siguientes -->
        <flux:field>
            <flux:label>Entregas Siguientes (días)</flux:label>
            <flux:input type="number" wire:model="subsequent_delivery_days" min="1" max="365" />
            <flux:error name="subsequent_delivery_days" />
            <flux:description>Intervalo entre entregas posteriores</flux:description>
        </flux:field>
    </div>
</div>

        <!-- Botones -->
        <div class="flex justify-end">
            <flux:button type="submit" variant="primary" wire:loading.attr="disabled">
                <span wire:loading.remove>Guardar Configuraciones</span>
                <span wire:loading>Guardando...</span>
            </flux:button>
        </div>
    </form>
</div>