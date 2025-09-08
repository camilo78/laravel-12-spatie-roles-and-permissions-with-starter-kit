<div>
  {{-- Encabezado de la página con nombre del paciente --}}
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Medicamentos - {{ $deliveryPatient->user->name }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">
            {{ $deliveryPatient->medicineDelivery->name }}
            {{-- Indicador de estado editable --}}
            @if($deliveryPatient->medicineDelivery->isEditable())
                <span class="text-green-600 dark:text-green-400">(Editable)</span>
            @else
                <span class="text-red-600 dark:text-red-400">(No editable)</span>
            @endif
        </flux:subheading>
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

    <!-- Mensaje de error -->
    @session('error')
        <div x-data="{ show: true }" x-show="show" x-transition:leave="transition ease-out duration-500"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="flex flex-wrap items-center p-2 mb-4 text-sm text-red-800 border border-red-300 rounded-lg bg-white dark:bg-white dark:text-red-300 dark:border-red-800"
            role="alert">

            <svg class="flex-shrink-0 w-8 h-8 mr-1 text-red-700 dark:text-red-300" xmlns="http://www.w3.org/2000/svg"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>

            <div class="flex-1">
                <pre class="whitespace-pre-wrap font-medium">{{ $value }}</pre>
            </div>

            <button @click="show = false" type="button"
                class="ml-2 text-red-800 hover:text-red-900 dark:text-red-300 dark:hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endsession
    {{-- Botón para regresar a la página anterior --}}
    <div class="mb-4">
        <button onclick="history.back()"
            class="px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            ← Volver
        </button>
    </div>

    {{-- Formulario para gestionar medicamentos del paciente --}}
    <form class="mt-6 space-y-6" wire:submit.prevent="saveChanges">
            {{-- Iteración sobre los medicamentos del paciente --}}
            @foreach($deliveryPatient->deliveryMedicines as $deliveryMedicine)
                <div class="p-4 border rounded-lg border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800">
                    {{-- Información del medicamento y control de inclusión --}}
                    <div class="flex items-center justify-between mb-3">
                        {{-- Detalles del medicamento --}}
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900 dark:text-white">{{ $deliveryMedicine->patientMedicine->medicine->name }}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $deliveryMedicine->patientMedicine->medicine->generic_name }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Dosis: {{ $deliveryMedicine->patientMedicine->dosage }} | 
                                Cantidad: {{ $deliveryMedicine->patientMedicine->quantity }}
                            </p>
                        </div>
                        
                        {{-- Control de inclusión del medicamento --}}
                        @if($deliveryPatient->medicineDelivery->isEditable() && $deliveryPatient->canEditMedicines())
                            {{-- Switch para incluir/excluir medicamento --}}
                            <flux:switch wire:click="toggleMedicineInclusion({{ $deliveryMedicine->id }})" 
                                :checked="$deliveryMedicine->included" />
                        @else
                            {{-- Badge de estado (solo lectura) --}}
                            <span class="px-2 py-1 text-xs rounded-full {{ $deliveryMedicine->included ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                {{ $deliveryMedicine->included ? 'Incluido' : 'Excluido' }}
                            </span>
                        @endif
                    </div>
                    
                    {{-- Sección de observaciones (solo para medicamentos excluidos) --}}
                    @if(!$deliveryMedicine->included)
                        <div class="mt-3">
                            @if($deliveryPatient->medicineDelivery->isEditable())
                                {{-- Campo de observaciones editable --}}
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Observaciones
                                </label>
                                <textarea wire:model="observations.{{ $deliveryMedicine->id }}" 
                                    placeholder="Motivo de exclusión (ej: Sin existencia, suspendido temporalmente...)"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    rows="3" required></textarea>
                                <p class="text-xs text-red-600 mt-1">* Campo obligatorio para medicamentos no entregados</p>
                            @else
                                {{-- Observaciones en modo solo lectura --}}
                                @if($deliveryMedicine->observations)
                                    <div class="p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-md">
                                        <p class="text-sm text-yellow-800 dark:text-yellow-200">
                                            <strong>Observaciones:</strong> {{ $deliveryMedicine->observations }}
                                        </p>
                                    </div>
                                @endif
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach

        
        {{-- Botón para guardar cambios (solo si es editable y paciente permite edición) --}}
        @if($deliveryPatient->medicineDelivery->isEditable() && $deliveryPatient->canEditMedicines())
            <div class="flex justify-end gap-3 mt-6">
                <flux:button class="px-4 py-2" type="submit" variant="primary"> Guardar Cambios</flux:button>
            </div>
        @endif
    </form>
</div>