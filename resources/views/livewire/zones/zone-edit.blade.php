<div>
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Editar Zona') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Actualiza la información de la zona') }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <form wire:submit="update" class="space-y-6">
        <flux:input label="Nombre" wire:model="name" />
        <flux:input label="Descripción" wire:model="description" />
        <flux:select label="Municipio" wire:model="municipality_id">
            <option value="">Seleccione un Municipio</option>
            @foreach($municipalities as $municipality)
                <option value="{{ $municipality->id }}">{{ $municipality->name }}</option>
            @endforeach
        </flux:select>
        
        <div>
            <flux:button type="submit" variant="primary">Actualizar Zona</flux:button>
        </div>
    </form>
</div>