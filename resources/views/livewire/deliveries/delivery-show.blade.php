<div>
    {{-- Encabezado de la página con nombre de la entrega --}}
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ $delivery->name }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">
            {{ $delivery->start_date->format('d/m/Y') }} - {{ $delivery->end_date->format('d/m/Y') }}
            {{-- Indicador de estado editable --}}
            @if ($delivery->isEditable())
                <span class="text-green-600 dark:text-green-400">(Editable)</span>
            @else
                <span class="text-red-600 dark:text-red-400">(No editable)</span>
            @endif
        </flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    {{-- Campo de búsqueda de pacientes --}}
    <div class="flex justify-end mb-4">
        <input type="search" wire:model.live.debounce.300ms="search" placeholder="Buscar paciente..."
            class="px-4 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white" />
    </div>

    {{-- Tabla de pacientes en la entrega --}}
    <div class="relative overflow-x-auto rounded-lg shadow-md dark:shadow-none mt-4 w-full">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            {{-- Encabezados de la tabla --}}
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">Paciente</th>
                    <th scope="col" class="px-6 py-3">DNI</th>
                    <th scope="col" class="px-6 py-3">Teléfonos</th>
                    <th scope="col" class="px-6 py-3">Medicamentos (Incluidos/Total Receta)</th>
                    <th scope="col" class="px-6 py-3">Estado</th>
                    <th scope="col" class="px-6 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                {{-- Iteración sobre los pacientes de la entrega --}}
                @forelse($deliveryPatients as $key => $deliveryPatient)
                    <tr class="{{ $key % 2 === 0 ? 'bg-white dark:bg-gray-800' : 'bg-gray-50 dark:bg-gray-700' }} border-b dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-600">
                        {{-- Nombre del paciente --}}
                        <td class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $deliveryPatient->user->name }}
                        </td>
                        {{-- DNI del paciente --}}
                        <td class="px-6 py-2 text-gray-600 dark:text-gray-300">{{ $deliveryPatient->user->dni }}</td>
                        {{-- Teléfono del paciente --}}
                        <td class="px-6 py-2 text-gray-600 dark:text-gray-300">
                            {{ $deliveryPatient->user->phone }}
                        </td>
                        {{-- Contador de medicamentos incluidos vs total --}}
                        <td class="px-6 py-2">
                            @if ($deliveryPatient->deliveryMedicines->where('included', true)->count() == $deliveryPatient->deliveryMedicines->count())
                                <flux:badge color="green" size="sm">
                                    El total de {{ $deliveryPatient->deliveryMedicines->count() }} medicamentos recetados
                                </flux:badge>
                            @else
                                <flux:badge color="yellow" size="sm">
                                    {{ $deliveryPatient->deliveryMedicines->where('included', true)->count() }} de
                                    {{ $deliveryPatient->deliveryMedicines->count() }} medicamentos
                                </flux:badge>
                            @endif
                        </td>
                        {{-- Estado del paciente --}}
                        <td class="px-6 py-2">
                            @if ($delivery->isEditable())
                                <div x-data="{ selectedState: '{{ $deliveryPatient->state }}', notes: '{{ $deliveryPatient->delivery_notes }}' }">
                                    {{-- Select para cambiar estado del paciente --}}
                                    <select x-model="selectedState" wire:change="updatePatientState({{ $deliveryPatient->id }}, $event.target.value)" 
                                        class="px-2 py-1 text-xs border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        <option value="programada">Programada</option>
                                        <option value="en_proceso">En Proceso</option>
                                        <option value="entregada">Entregada</option>
                                        <option value="no_entregada">No Entregada</option>
                                    </select>
                                    
                                    {{-- Input y botón para notas (solo si es no_entregada) --}}
                                    <div x-show="selectedState === 'no_entregada'" x-transition class="mt-2 flex gap-1">
                                        <input type="text" x-model="notes" placeholder="Motivo de no entrega" 
                                            class="flex-1 px-2 py-1 text-xs border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                                        <button @click="$wire.updatePatientNotes({{ $deliveryPatient->id }}, notes)" 
                                            class="px-2 py-1 text-xs bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">
                                            💾
                                        </button>
                                    </div>
                                </div>
                            @else
                                {{-- Badge de estado (solo lectura) --}}
                                @php
                                    $stateColors = [
                                        'programada' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
                                        'en_proceso' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                        'entregada' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                        'no_entregada' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'
                                    ];
                                    $stateLabels = [
                                        'programada' => 'Programada',
                                        'en_proceso' => 'En Proceso',
                                        'entregada' => 'Entregada',
                                        'no_entregada' => 'No Entregada'
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full {{ $stateColors[$deliveryPatient->state] }}">
                                    {{ $stateLabels[$deliveryPatient->state] }}
                                </span>
                            @endif
                            @if($deliveryPatient->delivery_notes)
                                <div class="text-xs text-gray-500 mt-1">{{ $deliveryPatient->delivery_notes }}</div>
                            @endif
                        </td>
                        {{-- Botón para ver medicamentos del paciente --}}
                        <td class="px-6 py-2 text-center">
                            <div class="flex flex-col gap-2 w-full sm:flex-row sm:w-auto sm:justify-center lg:flex-row lg:w-auto lg:gap-1 lg:flex-nowrap">
                                <a wire:navigate href="{{ route('deliveries.patient.medicines', [$delivery, $deliveryPatient]) }}"
                                    class="inline-flex items-center justify-center px-3 py-2 text-xs font-medium bg-white border border-gray-600 rounded-lg hover:bg-red-50 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-gray-700 dark:border-white dark:hover:bg-red-900 dark:focus:ring-red-800 flex-grow sm:flex-none">
                                    <flux:icon.eye variant="micro" class="text-gray-600 dark:text-gray-300 hover:text-gray-700 dark:hover:text-gray-200" />
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    {{-- Mensaje cuando no hay pacientes --}}
                    <tr class="bg-white dark:bg-gray-800">
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No se encontraron pacientes.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    <div class="mt-4">{{ $deliveryPatients->links() }}</div>
</div>
