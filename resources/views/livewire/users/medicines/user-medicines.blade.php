<div>
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Medicamentos de {{ $user->name }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">Gestiona los medicamentos asignados al usuario</flux:subheading>
        <flux:separator variant="subtle" />
    </div>
    <!-- Mensaje de éxito -->
    @session('success')
        <div x-data="{ show: true }" x-show="show"
            x-transition:leave="transition ease-out duration-500" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
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

    <!-- Mensaje de error -->
    @session('error')
        <div x-data="{ show: true }" x-show="show"
            x-transition:leave="transition ease-out duration-500" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="flex items-center p-2 mb-4 text-sm text-red-800 border border-red-300 rounded-lg bg-white dark:bg-white dark:text-red-300 dark:border-red-800"
            role="alert">

            <svg class="flex-shrink-0 w-8 h-8 mr-1 text-red-700 dark:text-red-300" xmlns="http://www.w3.org/2000/svg"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>

            <span class="font-medium flex-1">{{ $value }}</span>

            <button @click="show = false" type="button"
                class="ml-2 text-red-800 hover:text-red-900 dark:text-red-300 dark:hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endsession

    <div>
        <a wire:navigate href="{{ route('users.index') }}"
            class="cursor-pointer px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Regresar a Usuarios
        </a>
    </div>
    <div class="mt-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Asignar Nuevo Medicamento</h3>

        <form wire:submit.prevent="saveMedicine" class="space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Select searcheable de medicamento --}}
                <div class="relative" x-data="{ open: false }">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Medicamento</label>
                    
                    <div class="relative">
                        <input 
                            type="text" 
                            @click="open = true"
                            @input="open = true"
                            @keydown.escape="open = false"
                            wire:model.live="medicine_search"
                            placeholder="Seleccionar medicamento..."
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            autocomplete="off"
                            required>
                        
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                    
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         @click.away="open = false"
                         class="absolute z-10 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-lg max-h-60 overflow-auto">
                        
                        @if($medicine_search && strlen($medicine_search) >= 2 && count($filtered_medicines) > 0)
                            @foreach($filtered_medicines as $medicine)
                                <div class="px-4 py-2 text-gray-900 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer" 
                                     wire:click="selectMedicine({{ $medicine->id }}, '{{ $medicine->generic_name }} - {{ $medicine->presentation }}')"
                                     @click="open = false">
                                    <div class="font-medium text-gray-900 dark:text-gray-100">{{ $medicine->generic_name }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $medicine->presentation }}</div>
                                </div>
                            @endforeach
                        @elseif($medicine_search && strlen($medicine_search) >= 2 && count($filtered_medicines) === 0)
                            <div class="px-4 py-2 text-gray-500 dark:text-gray-400">No se encontraron medicamentos</div>

                        @else
                            <div class="px-4 py-2 text-gray-500 dark:text-gray-400">Escriba al menos 2 caracteres para buscar</div>
                        @endif
                    </div>
                    
                    <input type="hidden" name="medicine_id" wire:model="medicine_id" />
                </div>

                <flux:input label="Dosis" type="text" wire:model="dosage" placeholder="Ej: 500mg cada 8 horas"
                    required />

                <flux:input label="Cantidad" type="number" wire:model="quantity" min="1" required />

                <flux:input label="Fecha de Inicio" type="date" wire:model="start_date" required />

                <flux:input label="Fecha de Fin (Opcional)" type="date" wire:model="end_date" />

                <flux:select label="Estado" wire:model="status" required>
                    <option value="">Seleccionar estado</option>
                    <option value="active">Activo</option>
                    <option value="suspended">Suspendido</option>
                    <option value="completed">Completado</option>
                </flux:select>
            </div>

            <div class="flex justify-end">
                <flux:button type="submit" variant="primary">
                    @if ($editingId)
                        <span wire:loading.remove wire:target="saveMedicine">Editar Medicamento</span>
                        <span wire:loading wire:target="saveMedicine">Editando...</span>
                    @else
                        <span wire:loading.remove wire:target="saveMedicine">Asignar Medicamento</span>
                        <span wire:loading wire:target="saveMedicine">Asignando...</span>
                    @endif
                </flux:button>
            </div>
        </form>
    </div>
    <hr class="border-0 [print-color-adjust:exact] bg-zinc-800/5 dark:bg-white/10 h-px w-full my-6">

    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Medicamentos Asignados</h3>
    <div class="relative overflow-x-auto rounded-lg shadow-md dark:shadow-none mt-4 w-full">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">Medicamento</th>
                    <th scope="col" class="px-6 py-3">Dosis</th>
                    <th scope="col" class="px-6 py-3">Cantidad</th>
                    <th scope="col" class="px-6 py-3">Fecha Inicio</th>
                    <th scope="col" class="px-6 py-3">Estado</th>
                    <th scope="col" class="px-6 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($userMedicines as $key => $userMedicine)
                    <tr
                        class="{{ $key % 2 === 0 ? 'bg-white dark:bg-gray-800' : 'bg-gray-50 dark:bg-gray-700' }} border-b dark:border-gray-600">
                        <td class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $userMedicine->medicine->generic_name }}
                        </td>
                        <td class="px-6 py-2 text-gray-600 dark:text-gray-300">
                            {{ $userMedicine->dosage }}
                        </td>
                        <td class="px-6 py-2 text-gray-600 dark:text-gray-300">
                            {{ $userMedicine->quantity }}
                        </td>
                        <td class="px-6 py-2 text-gray-600 dark:text-gray-300">
                            {{ \Carbon\Carbon::parse($userMedicine->start_date)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-2">
                            <span
                                class="px-2 py-1 text-xs rounded-full 
                                    {{ $userMedicine->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : '' }}
                                    {{ $userMedicine->status === 'suspended' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' : '' }}
                                    {{ $userMedicine->status === 'completed' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' : '' }}">
                                {{ ucfirst($userMedicine->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-2 text-center flex justify-center gap-2">
                            {{-- Botón Editar --}}
                            <button wire:click="loadMedicine({{ $userMedicine->id }})"
                                class="inline-flex items-center justify-center px-3 py-2 text-xs font-medium bg-white border border-gray-600 rounded-lg hover:bg-blue-50 focus:ring-4 focus:outline-none focus:ring-gray-300 dark:bg-gray-700 dark:border-white dark:hover:bg-grey-900 dark:focus:ring-grey-800"
                                title="Editar Medicamento">
                                <flux:icon.square-pen variant="micro"
                                    class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300" />
                            </button>

                            {{-- Botón Eliminar --}}
                            <button wire:click="removeMedicine({{ $userMedicine->id }})"
                                wire:confirm="¿Eliminar medicamento?"
                                class="inline-flex items-center justify-center px-3 py-2 text-xs font-medium bg-white border border-gray-600 rounded-lg hover:bg-red-50 focus:ring-4 focus:outline-none focus:ring-gray-300 dark:bg-gray-700 dark:border-white dark:hover:bg-grey-900 dark:focus:ring-grey-800"
                                title="Eliminar Medicamento">
                                <flux:icon.trash-2 variant="micro"
                                    class="text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300" />
                            </button>
                        </td>

                    </tr>
                @empty
                    <tr class="bg-white dark:bg-gray-800">
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                            No hay medicamentos asignados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</div>
