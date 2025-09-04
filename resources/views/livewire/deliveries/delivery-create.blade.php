<div>
    {{-- Encabezado de la página --}}
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Nueva Entrega</flux:heading>
        <flux:subheading size="lg" class="mb-6">Crear nueva entrega programada</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    {{-- Formulario para crear nueva entrega --}}
    <div class="max-w-2xl">
        <form wire:submit="save">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Campo nombre de la entrega --}}
                <flux:input label="Nombre de la Entrega" wire:model="name" placeholder="Ej: Entrega Enero 2025"
                    required />
                <flux:input label="Fecha de Inicio" type="date" wire:model="start_date" 
                    min="{{ now()->startOfMonth()->format('Y-m-d') }}" 
                    max="{{ now()->endOfMonth()->format('Y-m-d') }}" required />
                <flux:input label="Fecha de Fin" type="date" wire:model="end_date" 
                    min="{{ now()->startOfMonth()->format('Y-m-d') }}" 
                    max="{{ now()->endOfMonth()->format('Y-m-d') }}" required />
                {{-- Botones de acción --}}
                <div class="flex justify-end gap-3 mt-6">
                    {{-- Botón cancelar --}}
                    <a wire:navigate href="{{ route('deliveries.index') }}"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:outline-none focus:ring-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-700 transition-colors">
                        Cancelar
                    </a>
                    {{-- Botón crear entrega --}}
                    <button type="submit" wire:loading.attr="disabled" wire:target="save" :disabled="$isSubmitting"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 disabled:opacity-50 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        <span wire:loading.remove wire:target="save">Crear Entrega</span>
                        <span wire:loading wire:target="save">Creando...</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
