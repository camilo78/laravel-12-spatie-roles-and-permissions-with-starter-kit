<div>
    {{-- Encabezado de la página --}}
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Entregas Programadas</flux:heading>
        <flux:subheading size="lg" class="mb-6">
            @if($this->startDate && $this->endDate)
                Pacientes del {{ \Carbon\Carbon::parse($this->startDate)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($this->endDate)->format('d/m/Y') }}
            @else
                Pacientes con entregas programadas del {{ $weekStart }} al {{ $weekEnd }}
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

    {{-- Tabla de pacientes --}}
    <div class="relative overflow-x-auto rounded-lg shadow-md dark:shadow-none mt-4 w-full">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">#</th>
                    <th scope="col" class="px-6 py-3">Paciente</th>
                    <th scope="col" class="px-6 py-3">DNI</th>
                    <th scope="col" class="px-6 py-3">Teléfono</th>
                    <th scope="col" class="px-6 py-3">Ubicación</th>
                    <th scope="col" class="px-6 py-3">Fecha Ingreso</th>
                    <th scope="col" class="px-6 py-3">Próxima Entrega</th>
                </tr>
            </thead>
            <tbody>
                @forelse($patients as $key => $patient)
                    <tr class="{{ $key % 2 === 0 ? 'bg-white dark:bg-gray-800' : 'bg-gray-50 dark:bg-gray-700' }} border-b dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-600">
                        <td class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $loop->iteration + ($patients->currentPage() - 1) * $patients->perPage() }}
                        </td>
                        <td class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $patient->name }}
                        </td>
                        <td class="px-6 py-2 text-gray-600 dark:text-gray-300">
                            {{ $patient->dni }}
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
                            @php
                                $nextDelivery = \Carbon\Carbon::parse($patient->next_delivery_date);
                            @endphp
                            <flux:badge color="green" size="sm">
                                📅 {{ $nextDelivery->format('d/m/Y') }}
                            </flux:badge>
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
    <div class="mt-4">{{ $patients->links() }}</div>
</div>