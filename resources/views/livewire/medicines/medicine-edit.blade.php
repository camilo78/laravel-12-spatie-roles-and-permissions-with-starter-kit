<div>
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Editar Medicamento</flux:heading>
        <flux:subheading size="lg" class="mb-6">Formulario para Editar Medicamento</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <div>
        <a wire:navigate href="{{ route('medicines.index') }}"
            class="cursor-pointer px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Regresar a Medicamentos
        </a>

        <div>
            <form class="mt-6 space-y-6" wire:submit="update">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    <flux:input 
                        label="Nombre Genérico" 
                        type="text" 
                        name="generic_name" 
                        placeholder="Ingrese el nombre genérico"
                        wire:model="generic_name" 
                        required 
                        maxlength="255" />
                    
                    <flux:input 
                        label="Presentación" 
                        type="text" 
                        name="presentation" 
                        placeholder="Ingrese la presentación"
                        wire:model="presentation" 
                        required 
                        maxlength="255" />
                </div>
                
                <div class="flex justify-end gap-3">
                    <a wire:navigate href="{{ route('medicines.index') }}"
                        class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600 transition-colors">
                        Cancelar
                    </a>
                    <flux:button 
                        type="submit" 
                        variant="primary">
                        <span wire:loading.remove wire:target="update">Actualizar</span>
                        <span wire:loading wire:target="update">Actualizando...</span>
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
</div>