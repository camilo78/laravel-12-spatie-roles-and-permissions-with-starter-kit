<div>
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Nueva Entrega</flux:heading>
        <flux:subheading size="lg" class="mb-6">Crear nueva entrega programada</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <div class="max-w-2xl">
        <form wire:submit="save">
            <div class="space-y-6">
                <flux:input label="Nombre de la Entrega" wire:model="name" placeholder="Ej: Entrega Enero 2025" required />
                
                <div class="grid grid-cols-2 gap-4">
                    <flux:input label="Fecha de Inicio" type="date" wire:model="start_date" required />
                    <flux:input label="Fecha de Fin" type="date" wire:model="end_date" required />
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <a wire:navigate href="{{ route('deliveries.index') }}"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                    Cancelar
                </a>
                <button type="submit" wire:loading.attr="disabled" wire:target="save" :disabled="$isSubmitting"
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 disabled:opacity-50">
                    <span wire:loading.remove wire:target="save">Crear Entrega</span>
                    <span wire:loading wire:target="save">Creando...</span>
                </button>
            </div>
        </form>
    </div>
</div>