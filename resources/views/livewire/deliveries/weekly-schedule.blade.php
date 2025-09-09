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
    {{-- Filtros --}}
    <div class="flex flex-col sm:flex-row lg:flex-row gap-4 mb-4">
        <div class="flex flex-col sm:flex-row gap-2">
            <input type="date" wire:model.live="startDate" placeholder="Fecha inicio"
                class="px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white" />
            <input type="date" wire:model.live="endDate" placeholder="Fecha fin"
                class="px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white" />
            
            <!-- Toggle para entrega departamental -->
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 whitespace-nowrap">Entrega Depto.:</label>
                <div class="flex items-center gap-1">
                    <button wire:click="$set('departmentalDeliveryFilter', false)" 
                        class="px-2 py-1 text-xs rounded {{ $departmentalDeliveryFilter === false ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }} transition-colors">
                        No
                    </button>
                    <button wire:click="$set('departmentalDeliveryFilter', null)" 
                        class="px-2 py-1 text-xs rounded {{ $departmentalDeliveryFilter === null ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }} transition-colors">
                        Todos
                    </button>
                    <button wire:click="$set('departmentalDeliveryFilter', true)" 
                        class="px-2 py-1 text-xs rounded {{ $departmentalDeliveryFilter === true ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }} transition-colors">
                        Sí
                    </button>
                </div>
            </div>
        </div>
        <div class="flex gap-2">
            <button wire:click="exportWeeklySchedule" wire:loading.attr="disabled" wire:target="exportWeeklySchedule"
                class="px-4 py-2 text-sm font-medium text-white bg-green-700 rounded-lg hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 disabled:opacity-50 disabled:cursor-not-allowed whitespace-nowrap">
                <span wire:loading.remove wire:target="exportWeeklySchedule">Exportar Excel</span>
                <span wire:loading wire:target="exportWeeklySchedule" class="flex items-center gap-2">
                    <span>Generando</span><span class="animate-bounce">...</span>
                </span>
            </button>
            <button wire:click="exportPatientsPDF" wire:loading.attr="disabled" wire:target="exportPatientsPDF"
                class="px-4 py-2 text-sm font-medium text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 disabled:opacity-50 disabled:cursor-not-allowed whitespace-nowrap">
                <span wire:loading.remove wire:target="exportPatientsPDF">Exportar PDF</span>
                <span wire:loading wire:target="exportPatientsPDF" class="flex items-center gap-2">
                    <span>Generando</span><span class="animate-bounce">...</span>
                </span>
            </button>
        </div>
                <div class="flex-1">
            <input type="search" wire:model.live.debounce.300ms="search" placeholder="Buscar paciente..."
                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white" />
        </div>
    </div>

    {{-- Tabla --}}
    <div class="relative overflow-x-auto rounded-lg shadow-md dark:shadow-none mt-4 w-full">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">#</th>
                    <th scope="col" class="px-6 py-3">Información del Paciente</th>
                    <th scope="col" class="px-6 py-3">Entrega Depto.</th>
                    <th scope="col" class="px-6 py-3">Estado</th>
                    <th scope="col" class="px-6 py-3">Entregas del Mes</th>
                    <th scope="col" class="px-6 py-3">Medicamentos</th>
                    <th scope="col" class="px-6 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($patients as $key => $patient)
                    <tr class="{{ $key % 2 === 0 ? 'bg-white dark:bg-gray-800' : 'bg-gray-50 dark:bg-gray-700' }} border-b dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-600">
                        <td class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $loop->iteration + ($patients->currentPage() - 1) * $patients->perPage() }}
                        </td>
                        <td class="px-6 py-2 text-gray-600 dark:text-gray-300">
                            <div class="text-sm text-gray-900 dark:text-white">{{ $patient->name }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-200">📞 {{ $patient->phone ?? 'No especificado' }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-200">📍 {{ $patient->municipality->name ?? 'N/A' }}, {{ $patient->locality->name ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-200"> 📅 {{ $patient->admission_date->format('d/m/Y') }}</div>
                        </td>
                        <td class="px-6 py-2 text-center">
                            @if($patient->departmental_delivery)
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full dark:bg-green-900 dark:text-green-300">
                                    Sí
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-800 bg-gray-100 rounded-full dark:bg-gray-700 dark:text-gray-300">
                                    No
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-2">
                            @php $deliveryPatient = $patient->deliveryPatients->first(); @endphp
                            @if($deliveryPatient)
                                <div x-data="{ selectedState: '{{ $deliveryPatient->state }}', notes: '{{ $deliveryPatient->delivery_notes }}' }" wire:key="delivery-{{ $deliveryPatient->id }}">
                                    {{-- Select para cambiar estado del paciente --}}
                                    <select x-model="selectedState" 
                                        @change="if (selectedState !== 'no_entregada') notes = ''; $wire.updatePatientState({{ $deliveryPatient->id }}, selectedState)" 
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
                                            <br> {{ ucfirst(str_replace('_', ' ', $deliveryPatient->state)) }}
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="text-xs text-gray-500">
                                    Sin entrega este mes
                                </div>
                            @endif
                        </td>
                        {{-- Contador de medicamentos incluidos vs total --}}
                        <td class="px-6 py-2">
                            @php $deliveryPatient = $patient->deliveryPatients->first(); @endphp
                            @if($deliveryPatient)
                                @if ($deliveryPatient->deliveryMedicines->where('included', true)->count() == $deliveryPatient->deliveryMedicines->count())
                                    <flux:badge color="green" size="sm">
                                        {{ $deliveryPatient->deliveryMedicines->count() }} medicamento{{ $deliveryPatient->deliveryMedicines->count() > 1 ? 's' : '' }} 
                                    </flux:badge>
                                @else
                                    <flux:badge color="yellow" size="sm">
                                        {{ $deliveryPatient->deliveryMedicines->where('included', true)->count() }} de
                                        {{ $deliveryPatient->deliveryMedicines->count() }} medicamentos
                                    </flux:badge>
                                @endif
                            @else
                                <span class="text-xs text-gray-500">Sin medicamentos</span>
                            @endif
                        </td>
                        {{-- Botón para ver medicamentos del paciente --}}
                        <td class="px-6 py-2 text-center">
                            @php $deliveryPatient = $patient->deliveryPatients->first(); @endphp
                            @if($deliveryPatient)
                                <div class="flex flex-col gap-2 w-full sm:flex-row sm:w-auto sm:justify-center lg:flex-row lg:w-auto lg:gap-1 lg:flex-nowrap">
                                    <a wire:navigate href="{{ route('deliveries.patient.medicines', [$deliveryPatient->medicineDelivery, $deliveryPatient]) }}"
                                        class="inline-flex items-center justify-center px-3 py-2 text-xs font-medium bg-white border border-gray-600 rounded-lg hover:bg-red-50 focus:ring-4 focus:outline-none focus:ring-gray-300 dark:bg-gray-700 dark:border-white dark:hover:bg-grey-900 dark:focus:ring-grey-800 flex-grow sm:flex-none">
                                        <flux:icon.eye variant="micro" class="text-gray-600 dark:text-gray-300 hover:text-gray-700 dark:hover:text-gray-200" />
                                    </a>
                                </div>
                            @else
                                <span class="text-xs text-gray-500">-</span>
                            @endif
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
    @if($patients->hasPages())
        <div class="mt-4">{{ $patients->links() }}</div>
    @endif
</div>