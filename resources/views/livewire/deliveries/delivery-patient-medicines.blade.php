<div>
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Medicamentos - {{ $deliveryPatient->user->name }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">
            Entrega: {{ $deliveryPatient->medicineDelivery->name }}
            @if($deliveryPatient->medicineDelivery->isEditable())
                <span class="text-green-600">(Editable)</span>
            @else
                <span class="text-red-600">(No editable)</span>
            @endif
        </flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <div class="mt-6">
        <a wire:navigate href="{{ route('deliveries.show', $deliveryPatient->medicineDelivery) }}"
            class="cursor-pointer px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Regresar a Entrega
        </a>
    </div>

    <div class="mt-6 space-y-4">
        @foreach($deliveryPatient->deliveryMedicines as $deliveryMedicine)
            <div class="flex items-center justify-between p-4 border rounded-lg dark:border-gray-600 bg-white dark:bg-gray-800">
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
                
                <div class="flex items-center">
                    @if($deliveryPatient->medicineDelivery->isEditable())
                        <flux:switch wire:click="toggleMedicineInclusion({{ $deliveryMedicine->id }})" 
                            :checked="$deliveryMedicine->included" />
                    @else
                        <span class="px-2 py-1 text-xs rounded-full {{ $deliveryMedicine->included ? 'bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-300' }}">
                            {{ $deliveryMedicine->included ? 'Incluido' : 'Excluido' }}
                        </span>
                    @endif
                </div>
            </div>
        @endforeach

        @if($deliveryPatient->deliveryMedicines->isEmpty())
            <div class="p-6 text-center text-gray-500 dark:text-gray-400 border rounded-lg dark:border-gray-600">
                No hay medicamentos asignados para este paciente en esta entrega.
            </div>
        @endif
    </div>
</div>