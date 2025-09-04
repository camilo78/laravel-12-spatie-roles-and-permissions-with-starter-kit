<div>
    {{-- Encabezado de la página --}}
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Editar Entrega</flux:heading>
        <flux:subheading size="lg" class="mb-6">Modificar información de la entrega</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <div>
        {{-- Botón para regresar al listado de entregas --}}
        <a wire:navigate href="{{ route('deliveries.index') }}"
            class="cursor-pointer px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Regresar a Entregas
        </a>

        <div>
            {{-- Formulario principal para editar entrega --}}
            <form class="mt-6 space-y-6" wire:submit="save">
                {{-- Grid responsivo de 2 columnas en pantallas grandes --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Campo nombre de la entrega --}}
                    <flux:input label="Nombre de la Entrega" wire:model="name" placeholder="Ej: Entrega Enero 2025"
                        required />
                        
                    {{-- Fecha de inicio y fin --}}
                    <flux:input label="Fecha de Inicio" type="date" wire:model="start_date" required />
                    <flux:input label="Fecha de Fin" type="date" wire:model="end_date" required />
                </div>
                
                {{-- Sección de botones de acción --}}
                <div class="flex justify-end gap-3">
                    <a wire:navigate href="{{ route('deliveries.index') }}"
                        class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600 transition-colors">
                        Cancelar
                    </a>
                    <flux:button 
                        type="submit" 
                        variant="primary" 
                        :disabled="$isSubmitting">
                        <span wire:loading.remove wire:target="save">Actualizar Entrega</span>
                        <span wire:loading wire:target="save">Actualizando...</span>
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
</div>