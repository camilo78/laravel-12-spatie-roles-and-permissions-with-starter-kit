<div>
    {{-- Encabezado de la página --}}
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Entregas Programadas</flux:heading>
        <flux:subheading size="lg" class="mb-6">
            @if($this->startDate && $this->endDate)
                Pacientes del {{ \Carbon\Carbon::parse($this->startDate)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($this->endDate)->format('d/m/Y') }}
            @else
                Pacientes del {{ $this->weekStart }} al {{ $this->weekEnd }}
            @endif
        </flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    {{-- Filtros --}}
    <div class="flex flex-col sm:flex-row gap-4 mb-4">
        <div class="flex gap-2">
            <input type="date" wire:model.live="startDate" placeholder="Fecha inicio"
                min="{{ now()->startOfMonth()->format('Y-m-d') }}" 
                max="{{ now()->endOfMonth()->format('Y-m-d') }}"
                class="px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white" />
            <input type="date" wire:model.live="endDate" placeholder="Fecha fin"
                min="{{ now()->startOfMonth()->format('Y-m-d') }}" 
                max="{{ now()->endOfMonth()->format('Y-m-d') }}"
                class="px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white" />
            @if($this->startDate && $this->endDate)
                <button wire:click="exportExcel" wire:loading.attr="disabled" wire:target="exportExcel"
                    class="px-4 py-2 text-sm font-medium text-white bg-green-700 rounded-lg hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span wire:loading.remove wire:target="exportExcel">Exportar Excel</span>
                    <span wire:loading wire:target="exportExcel" class="flex items-center justify-center">
                        <svg class="animate-spin h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                </button>
            @endif
        </div>
        <div class="flex-1">
            <input type="search" wire:model.live.debounce.300ms="search" placeholder="Buscar paciente..."
                class="w-full px-4 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white" />
        </div>
    </div>

    {{-- Tabla de pacientes --}}
    <div class="relative overflow-x-auto rounded-lg shadow-md dark:shadow-none mt-4 w-full">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">#</th>
                    <th scope="col" class="px-6 py-3">Paciente</th>
                    <th scope="col" class="px-6 py-3">Teléfono</th>
                    <th scope="col" class="px-6 py-3">Ubicación</th>
                    <th scope="col" class="px-6 py-3">Fecha Ingreso</th>
                    <th scope="col" class="px-6 py-3">Estado</th>
                    <th scope="col" class="px-6 py-3">Próxima Entrega</th>
                </tr>
            </thead>
            <tbody>
                @forelse($patients as $key => $patient)
                    <tr class="{{ $key % 2 === 0 ? 'bg-white dark:bg-gray-800' : 'bg-gray-50 dark:bg-gray-700' }} border-b dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-600">
                        <td class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $loop->iteration + ($patients->currentPage() - 1) * $patients->perPage() }}
                        </td>
                        <td class="px-6 py-2 text-gray-600 dark:text-gray-300">
                            {{ $patient->name }}
                        </td>
                        <td class="px-6 py-2 text-gray-600 dark:text-gray-300">
                            {{ $patient->phone ?? 'No especificado' }}
                        </td>
                        <td class="px-6 py-2 text-gray-600 dark:text-gray-300">
                            {{ $patient->municipality->name ?? 'N/A' }}, {{ $patient->locality->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-2 text-gray-600 dark:text-gray-300">
                            {{ $patient->admission_date ? $patient->admission_date->format('d/m/Y') : 'No especificado' }}
                        </td>
                        <td class="px-6 py-2">
                            @php $deliveryPatient = $patient->deliveryPatients->first(); @endphp
                            @if($deliveryPatient)
                                <div x-data="{ state: '{{ $deliveryPatient->state }}', notes: '{{ $deliveryPatient->delivery_notes }}' }">
                                    <select x-model="state" wire:change="updatePatientState({{ $deliveryPatient->id }}, $event.target.value)" 
                                        class="px-2 py-1 text-xs border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        <option value="programada">Programada</option>
                                        <option value="en_proceso">En Proceso</option>
                                        <option value="entregada">Entregada</option>
                                        <option value="no_entregada">No Entregada</option>
                                    </select>
                                    
                                    <div x-show="state === 'no_entregada'" class="mt-2 flex gap-1">
                                        <input type="text" x-model="notes" placeholder="Motivo" 
                                            class="flex-1 px-2 py-1 text-xs border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                                        <button @click="$wire.updatePatientNotes({{ $deliveryPatient->id }}, notes)" 
                                            class="px-2 py-1 text-xs bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                            💾
                                        </button>
                                    </div>
                                    
                                    @if($deliveryPatient->delivery_notes)
                                        <div class="text-xs text-gray-500 mt-1">{{ $deliveryPatient->delivery_notes }}</div>
                                    @endif
                                </div>
                            @else
                                <span class="text-xs text-gray-500">Sin entrega</span>
                            @endif
                        </td>
                        <td class="px-6 py-2">
                            @php
                                $nextDelivery = \Carbon\Carbon::parse($patient->next_delivery_date);
                                $deliveryPatient = $patient->deliveryPatients->first();
                                
                                // Ajustar al último día del mes si el día no existe
                                $originalDay = $patient->admission_date->day;
                                $daysInCurrentMonth = $nextDelivery->daysInMonth;
                                
                                if ($originalDay > $daysInCurrentMonth) {
                                    $nextDelivery = \Carbon\Carbon::create($nextDelivery->year, $nextDelivery->month, $daysInCurrentMonth);
                                }
                                
                                if ($deliveryPatient && in_array($deliveryPatient->state, ['entregada', 'no_entregada'])) {
                                    $nextDelivery = $nextDelivery->copy()->addMonth();
                                    $badgeColor = $deliveryPatient->state === 'entregada' ? 'green' : 'red';
                                } else {
                                    $badgeColor = 'blue';
                                }
                            @endphp
                            <flux:badge color="{{ $badgeColor }}" size="sm">
                                📅 {{ $nextDelivery->format('d/m/Y') }}
                            </flux:badge>
                        </td>
                    </tr>
                @empty
                    <tr class="bg-white dark:bg-gray-800">
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                            No hay pacientes con entregas programadas para este período.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    <div class="mt-4">{{ $patients->links() }}</div>
</div>