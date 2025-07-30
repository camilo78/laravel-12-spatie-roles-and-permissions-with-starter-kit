<div>
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ $delivery->name }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">
            {{ $delivery->start_date->format('d/m/Y') }} - {{ $delivery->end_date->format('d/m/Y') }}
            @if($delivery->isEditable())
                <span class="text-green-600">(Editable)</span>
            @else
                <span class="text-red-600">(No editable)</span>
            @endif
        </flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <!-- Buscador -->
    <div class="mb-4">
        <input type="search" wire:model.live.debounce.300ms="search" placeholder="Buscar paciente..."
            class="px-4 py-2 text-sm border border-gray-300 rounded-md dark:bg-gray-800 dark:border-gray-600" />
    </div>

    <!-- Tabla de Pacientes -->
    <div class="overflow-x-auto rounded-lg shadow-md">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-6 py-3">Paciente</th>
                    <th class="px-6 py-3">DUI</th>
                    <th class="px-6 py-3">Medicamentos</th>
                    <th class="px-6 py-3">Estado</th>
                    <th class="px-6 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($deliveryPatients as $deliveryPatient)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-600">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                            {{ $deliveryPatient->user->name }}
                        </td>
                        <td class="px-6 py-4">{{ $deliveryPatient->user->dui }}</td>
                        <td class="px-6 py-4">{{ $deliveryPatient->deliveryMedicines->count() }}</td>
                        <td class="px-6 py-4">
                            @if($delivery->isEditable())
                                <flux:switch wire:click="togglePatientInclusion({{ $deliveryPatient->id }})" 
                                    :checked="$deliveryPatient->included" size="sm" />
                            @else
                                <span class="px-2 py-1 text-xs rounded-full {{ $deliveryPatient->included ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $deliveryPatient->included ? 'Incluido' : 'Excluido' }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button wire:click="selectPatient({{ $deliveryPatient->id }})"
                                class="inline-flex items-center px-3 py-2 text-xs bg-white border border-gray-600 rounded-lg hover:bg-gray-100">
                                <flux:icon.eye variant="micro" class="text-gray-600" />
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No se encontraron pacientes.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div class="mt-4">{{ $deliveryPatients->links() }}</div>

    <!-- Modal de Medicamentos -->
    @if($showMedicines && $selectedPatient)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: @entangle('showMedicines') }" x-show="show">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75" wire:click="closeMedicines"></div>
                
                <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-4xl w-full max-h-96 overflow-y-auto">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium">Medicamentos - {{ $selectedPatient->user->name }}</h3>
                            <button wire:click="closeMedicines" class="text-gray-400 hover:text-gray-600">
                                <flux:icon.x-mark class="w-6 h-6" />
                            </button>
                        </div>
                        
                        <div class="space-y-4">
                            @foreach($selectedPatient->deliveryMedicines as $deliveryMedicine)
                                <div class="flex items-center justify-between p-4 border rounded-lg dark:border-gray-600">
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
                                    
                                    @if($delivery->isEditable())
                                        <flux:switch wire:click="toggleMedicineInclusion({{ $deliveryMedicine->id }})" 
                                            :checked="$deliveryMedicine->included" />
                                    @else
                                        <span class="px-2 py-1 text-xs rounded-full {{ $deliveryMedicine->included ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $deliveryMedicine->included ? 'Incluido' : 'Excluido' }}
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>