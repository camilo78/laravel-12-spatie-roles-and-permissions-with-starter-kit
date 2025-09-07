<div class="bg-white dark:bg-gray-800 rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm">
    <div class="p-4 border-b border-neutral-200 dark:border-neutral-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Entregas de Medicamentos</h3>
        <p class="text-sm text-gray-600 dark:text-gray-400">Listado completo de entregas realizadas</p>
    </div>
    <div class="p-4 overflow-x-auto">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">Nombre</th>
                    <th scope="col" class="px-6 py-3">Fecha Inicio</th>
                    <th scope="col" class="px-6 py-3">Fecha Fin</th>
                    <th scope="col" class="px-6 py-3">Pacientes</th>
                    <th scope="col" class="px-6 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($deliveries as $delivery)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $delivery->name }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $delivery->start_date ? \Carbon\Carbon::parse($delivery->start_date)->format('d/m/Y') : 'N/A' }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $delivery->end_date ? \Carbon\Carbon::parse($delivery->end_date)->format('d/m/Y') : 'N/A' }}
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $totalPatients = $delivery->deliveryPatients->count();
                                $patientsEntregados = $delivery->deliveryPatients->where('state', 'entregada')->count();
                                $patientsEnProceso = $delivery->deliveryPatients->where('state', 'en_proceso')->count();
                                $patientsNoEntregados = $delivery->deliveryPatients->where('state', 'no_entregada')->count();
                                $patientsProgramados = $delivery->deliveryPatients->where('state', 'programada')->count();
                            @endphp
                            <div class="text-sm">
                                <div class="text-green-600 dark:text-green-400">✓ {{ $patientsEntregados }} entregados</div>
                                @if($patientsEnProceso > 0)
                                    <div class="text-blue-600 dark:text-blue-400">⏳ {{ $patientsEnProceso }} en proceso</div>
                                @endif
                                @if($patientsNoEntregados > 0)
                                    <div class="text-red-600 dark:text-red-400">✗ {{ $patientsNoEntregados }} no entregados</div>
                                @endif
                                @if($patientsProgramados > 0)
                                    <div class="text-gray-600 dark:text-gray-400">📋 {{ $patientsProgramados }} programados</div>
                                @endif
                                <div class="text-gray-500 dark:text-gray-400 text-xs mt-1">Total: {{ $totalPatients }}</div>
                            </div>
                        </td>

                        <td class="px-6 py-2 pb-2">
                            <button onclick="generateDeliveryReport({{ $delivery->id }})" id="reportBtn-{{ $delivery->id }}"
                                class="inline-flex items-center justify-center px-3 py-2 text-xs font-medium bg-white border border-gray-600 rounded-lg hover:bg-red-50 focus:ring-4 focus:outline-none focus:ring-gray-300 dark:bg-gray-700 dark:border-white dark:hover:bg-grey-900 dark:focus:ring-grey-800 flex-grow sm:flex-none disabled:opacity-50 disabled:cursor-not-allowed">
                                <span id="reportIcon-{{ $delivery->id }}">
                                    <svg width="24" height="24" viewBox="0 0 64 64" version="1.1">
                                        <path style="fill:#ffffff;fill-opacity:1;stroke:#757575;stroke-width:2.5;stroke-dasharray:none;stroke-opacity:1" d="m 19.041105,4.8376851 26.418327,0.13931 15.499307,15.4726749 c 0,0 0.06,22.479177 0.0075,34.093609 -0.02658,5.874048 -6.03271,5.951522 -6.03271,5.951522 L 19.269434,60.363442 c 0,0 -6.156372,0.04178 -6.298335,-5.827797 -0.141962,-5.869576 0.06959,-43.835527 0.06959,-43.835527 0.0095,-5.9765828 6.21917,-6.0030579 6.21917,-6.0030579 z" id="path6" sodipodi:nodetypes="cccscczscc" />
                                        <rect x="1.5840042" y="28.708817" width="47.119408" height="23.559704" rx="3.9266174" ry="3.9266174" fill="#d32f2f" id="rect2" style="stroke-width:1.96331" />
                                        <text x="25.143705" y="46.37859" font-family="Arial, sans-serif" font-size="15.7065px" font-weight="bold" fill="#ffffff" text-anchor="middle" id="text2" style="stroke-width:1.96331">PDF</text>
                                        <polygon points="60,20 44,20 44,4 " fill="#bababa" id="polygon1" transform="translate(1.8239921,0.0566784)" />
                                    </svg>
                                </span>
                                <span id="reportLoader-{{ $delivery->id }}" class="hidden">
                                    <svg class="animate-spin h-5 w-5 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </span>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                            No hay entregas registradas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Paginación -->
    <div class="px-4 pb-4">
        {{ $deliveries->links() }}
    </div>
</div>