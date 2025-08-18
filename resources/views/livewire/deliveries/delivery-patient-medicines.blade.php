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

    {{-- Botón para regresar a la entrega --}}
    <div class="mb-4">
        <a wire:navigate href="{{ route('deliveries.show', $deliveryPatient->medicineDelivery) }}"
            class="px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            ← Volver a Entrega
        </a>
    </div>

    {{-- Formulario para gestionar medicamentos del paciente --}}
    <form wire:submit.prevent="saveChanges">
        <div class="space-y-4">
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
                        @if($deliveryPatient->medicineDelivery->isEditable())
                            {{-- Switch para incluir/excluir medicamento (solo si es editable) --}}
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
                                    rows="3"></textarea>
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
        </div>
        
        {{-- Botón para guardar cambios (solo si es editable) --}}
        @if($deliveryPatient->medicineDelivery->isEditable())
            <div class="flex justify-end mt-6">
                <flux:button type="submit" variant="primary" wire:loading.attr="disabled" wire:target="saveChanges">
                    <span wire:loading.remove wire:target="saveChanges">Guardar Cambios</span>
                    <span wire:loading wire:target="saveChanges">Guardando...</span>
                </flux:button>
            </div>
        @endif
    </form>
</div>