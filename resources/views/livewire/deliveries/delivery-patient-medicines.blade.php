<div>
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Medicamentos - {{ $deliveryPatient->user->name }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">
            {{ $deliveryPatient->medicineDelivery->name }}
            @if($deliveryPatient->medicineDelivery->isEditable())
                <span class="text-green-600">(Editable)</span>
            @else
                <span class="text-red-600">(No editable)</span>
            @endif
        </flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <div class="mb-4">
        <a wire:navigate href="{{ route('deliveries.show', $deliveryPatient->medicineDelivery) }}"
            class="px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800">
            ← Volver a Entrega
        </a>
    </div>

    <form wire:submit.prevent="saveChanges">
        <div class="space-y-4">
            @foreach($deliveryPatient->deliveryMedicines as $deliveryMedicine)
                <div class="p-4 border rounded-lg dark:border-gray-600 bg-white dark:bg-gray-800">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex-1">
                            <h4 class="font-medium">{{ $deliveryMedicine->patientMedicine->medicine->name }}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $deliveryMedicine->patientMedicine->medicine->generic_name }}
                            </p>
                            <p class="text-xs text-gray-500">
                                Dosis: {{ $deliveryMedicine->patientMedicine->dosage }} | 
                                Cantidad: {{ $deliveryMedicine->patientMedicine->quantity }}
                            </p>
                        </div>
                        
                        @if($deliveryPatient->medicineDelivery->isEditable())
                            <flux:switch wire:click="toggleMedicineInclusion({{ $deliveryMedicine->id }})" 
                                :checked="$deliveryMedicine->included" />
                        @else
                            <span class="px-2 py-1 text-xs rounded-full {{ $deliveryMedicine->included ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $deliveryMedicine->included ? 'Incluido' : 'Excluido' }}
                            </span>
                        @endif
                    </div>
                    
                    @if(!$deliveryMedicine->included)
                        <div class="mt-3">
                            @if($deliveryPatient->medicineDelivery->isEditable())
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Observaciones
                                </label>
                                <textarea wire:model="observations.{{ $deliveryMedicine->id }}" 
                                    placeholder="Motivo de exclusión (ej: Sin existencia, suspendido temporalmente...)"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    rows="3"></textarea>
                            @else
                                @if($deliveryMedicine->observations)
                                    <div class="p-3 bg-yellow-50 dark:bg-yellow-900 rounded-md">
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
        
        @if($deliveryPatient->medicineDelivery->isEditable())
            <div class="flex justify-end mt-6">
                <button type="submit" wire:loading.attr="disabled" wire:target="saveChanges" :disabled="$isSubmitting"
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 disabled:opacity-50">
                    <span wire:loading.remove wire:target="saveChanges">Guardar Cambios</span>
                    <span wire:loading wire:target="saveChanges">Guardando...</span>
                </button>
            </div>
        @endif
    </form>
</div>