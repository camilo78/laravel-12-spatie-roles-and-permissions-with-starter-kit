<div>
    {{-- Encabezado --}}
    <div class="mb-6">
        <flux:heading size="xl" level="1">Entregas Programadas</flux:heading>
        <flux:subheading size="lg" class="mb-6">
            @if($this->startDate && $this->endDate)
                Pacientes del {{ \Carbon\Carbon::parse($this->startDate)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($this->endDate)->format('d/m/Y') }}
            @else
                Pacientes del {{ $weekStart }} al {{ $weekEnd }}
            @endif
        </flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    {{-- Filtros --}}
    <div class="flex flex-col sm:flex-row gap-4 mb-4">
        <div class="flex gap-2">
            <input type="date" wire:model.live="startDate" placeholder="Fecha inicio"
                class="px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white" />
            <input type="date" wire:model.live="endDate" placeholder="Fecha fin"
                class="px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white" />
        </div>
        <div class="flex-1">
            <input type="search" wire:model.live.debounce.300ms="search" placeholder="Buscar paciente..."
                class="w-full px-4 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white" />
        </div>
    </div>

    {{-- Tabla --}}
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
                    <th scope="col" class="px-6 py-3">Entregas del Mes</th>
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
                            {{ $patient->admission_date->format('d/m/Y') }}
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
                                $admission = \Carbon\Carbon::parse($patient->admission_date);
                                $today = \Carbon\Carbon::today();
                                $currentMonth = $today->month;
                                $currentYear = $today->year;
                                
                                // Primera entrega: 30 días después de admission_date
                                $firstDelivery = $admission->copy()->addDays(30);
                                // Ajustar al viernes si cae en fin de semana
                                if ($firstDelivery->isSaturday()) {
                                    $firstDelivery->subDay();
                                } elseif ($firstDelivery->isSunday()) {
                                    $firstDelivery->subDays(2);
                                }
                                
                                // Determinar qué entrega corresponde al mes actual
                                $deliveryForCurrentMonth = null;
                                $isFirstDelivery = false;
                                
                                // Si la primera entrega es en el mes actual
                                if ($firstDelivery->month == $currentMonth && $firstDelivery->year == $currentYear) {
                                    $deliveryForCurrentMonth = $firstDelivery;
                                    $isFirstDelivery = true;
                                } else {
                                    // Buscar qué entrega (cada 120 días) cae en el mes actual
                                    $deliveryDate = $firstDelivery->copy();
                                    $deliveryCount = 1;
                                    
                                    while ($deliveryDate->year <= $currentYear + 1) {
                                        if ($deliveryDate->month == $currentMonth && $deliveryDate->year == $currentYear) {
                                            $deliveryForCurrentMonth = $deliveryDate;
                                            break;
                                        }
                                        $deliveryDate->addDays(120);
                                        // Ajustar al viernes si cae en fin de semana
                                        if ($deliveryDate->isSaturday()) {
                                            $deliveryDate->subDay();
                                        } elseif ($deliveryDate->isSunday()) {
                                            $deliveryDate->subDays(2);
                                        }
                                        $deliveryCount++;
                                        
                                        if ($deliveryCount > 20) break; // Evitar loop infinito
                                    }
                                }
                                
                                // Determinar color basado en el estado del delivery patient
                                $deliveryPatient = $patient->deliveryPatients->first();
                                $colorClass = 'text-blue-600'; // Por defecto azul
                                
                                if ($deliveryPatient) {
                                    switch ($deliveryPatient->state) {
                                        case 'programada':
                                        case 'en_proceso':
                                            $colorClass = 'text-blue-600';
                                            break;
                                        case 'entregada':
                                            $colorClass = 'text-green-600';
                                            break;
                                        case 'no_entregada':
                                            $colorClass = 'dark:text-red-400 text-red-600';
                                            break;
                                    }
                                }
                            @endphp
                            
                            @if($deliveryForCurrentMonth)
                                <div class="text-xs">
                                    <div class="font-semibold {{ $colorClass }}">
                                        📅 {{ $deliveryForCurrentMonth->locale('es')->isoFormat('D MMM YYYY') }}
                                    </div>
                                    <div class="text-gray-500">
                                        {{ $isFirstDelivery ? 'Primera entrega' : 'Entrega programada' }}
                                        @if($deliveryPatient && $deliveryPatient->state)
                                            - {{ ucfirst(str_replace('_', ' ', $deliveryPatient->state)) }}
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="text-xs text-gray-500">
                                    Sin entrega este mes
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-2">
                            <div class="text-xs">
                                <div class="font-semibold text-blue-600">
                                    📅 {{ $patient->next_delivery_date->locale('es')->isoFormat('D MMM YYYY') }}
                                </div>
                                <div class="text-gray-500">
                                    Siguiente entrega
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr class="bg-white dark:bg-gray-800">
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                            No hay pacientes con entregas programadas para este período.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    @if($patients->hasPages())
        <div class="mt-4">{{ $patients->links() }}</div>
    @endif
</div>