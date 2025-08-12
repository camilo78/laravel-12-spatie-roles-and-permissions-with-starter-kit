<div x-data="{ open: @entangle('showDropdown') }" x-on:click.away="$wire.hideDropdown()">
    <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
        Patología *
    </label>
    
    <div class="relative">
        <input 
            type="text" 
            wire:model.live.debounce.300ms="search"
            placeholder="Buscar patología..."
            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white dark:placeholder-gray-400"
        />
        
        <input type="hidden" name="pathology_id" value="{{ $selectedPathologyId }}" required />
        
        @if($showDropdown && count($pathologies) > 0)
            <div class="absolute z-10 w-full bg-white border border-gray-300 rounded-lg shadow-lg mt-1 overflow-y-auto max-h-32 dark:bg-gray-800 dark:border-gray-600">
                @foreach($pathologies as $pathology)
                    <div 
                        wire:click="selectPathology({{ $pathology->id }})"
                        class="p-3 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer border-b border-gray-100 dark:border-gray-600 last:border-b-0"
                    >
                    <div class="text-sm max-h-32 text-gray-600 dark:text-gray-300">{{ $pathology->clave }} - {{ $pathology->descripcion }}</div>
                    </div>
                @endforeach
            </div>
        @endif
        
        @if($showDropdown && strlen($search) >= 2 && count($pathologies) === 0)
            <div class="absolute z-50 w-full bg-white border border-gray-300 rounded-lg shadow-lg mt-1 dark:bg-gray-800 dark:border-gray-600">
                <div class="p-3 text-gray-500 dark:text-gray-400 text-sm">
                    No se encontraron patologías
                </div>
            </div>
        @endif
    </div>
</div>