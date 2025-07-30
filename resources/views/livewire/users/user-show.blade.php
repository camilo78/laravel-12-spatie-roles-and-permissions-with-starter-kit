<div>
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Perfil del Usuario</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ $user->name }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <div>
        <a wire:navigate href="{{ route('users.index') }}"
            class="cursor-pointer px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Regresar a Usuarios
        </a>

        <div>
            <div class="overflow-x-auto mt-6">
                <table
                    class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 border dark:border-gray-700">
                    <tbody>
                        <tr class="border-b dark:border-gray-700 bg-white dark:bg-gray-900">
                            <th class="px-6 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">Nombre
                            </th>
                            <td class="px-6 py-3 text-gray-600 dark:text-gray-300">{{ $user->name }}</td>
                            <th class="px-6 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">DNI</th>
                            <td class="px-6 py-3 text-gray-600 dark:text-gray-300">{{ $user->dui ?? 'N/A' }}</td>
                        </tr>
                         <tr class="border-b dark:border-gray-700 bg-white dark:bg-gray-900">
                            <th class="px-6 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">Correo
                                Electrónico</th>
                            <td class="px-6 py-3 text-gray-600 dark:text-gray-300">{{ $user->email }}</td>
                            <th class="px-6 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">Phone</th>
                            <td class="px-6 py-3 text-gray-600 dark:text-gray-300">{{ $user->phone }}</td>
                        </tr>

                        <tr class="border-b dark:border-gray-700 bg-white dark:bg-gray-900">
                            <th class="px-6 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                Departamento</th>
                            <td class="px-6 py-3 text-gray-600 dark:text-gray-300">{{ $user->departmentName }}</td>
                            <th class="px-6 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">Dirección
                            </th>
                            <td class="px-6 py-3 text-gray-600 dark:text-gray-300">{{ $user->address }}</td>
                        </tr>
                        <tr>
                            <th class="px-6 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">Roles</th>

                            <td class="px-6 py-3 text-gray-600 dark:text-gray-300">
                                @if ($user->roles->isEmpty())
                                    <span
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-800 bg-gray-200 rounded-full dark:bg-gray-700 dark:text-gray-300">
                                        Sin Roles
                                    </span>
                                @else
                                    @foreach ($user->roles as $role)
                                        <span
                                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-800 bg-gray-200 rounded-full dark:bg-gray-700 dark:text-gray-300">
                                            {{ $role->name }}
                                        </span>
                                    @endforeach
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Sección de Patologías -->
            <div class="mt-8">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Patologías</h3>
                    <a href="{{ route('users.pathologies', $user->id) }}"
                        class="px-3 py-2 text-xs font-medium text-white bg-purple-700 rounded-lg hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-800">
                        Gestionar Patologías
                    </a>
                </div>
                <div class="bg-white rounded-lg shadow dark:bg-gray-800">
                    @if($user->patientPathologies->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">Patología</th>
                                        <th scope="col" class="px-6 py-3">Código</th>
                                        <th scope="col" class="px-6 py-3">Fecha Diagnóstico</th>
                                        <th scope="col" class="px-6 py-3">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->patientPathologies as $key => $pathology)
                                        <tr class="{{ $key % 2 === 0 ? 'bg-white dark:bg-gray-800' : 'bg-gray-50 dark:bg-gray-700' }} border-b dark:border-gray-600">
                                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                {{ $pathology->pathology->name }}
                                            </td>
                                            <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                                {{ $pathology->pathology->code }}
                                            </td>
                                            <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                                {{ $pathology->diagnosed_at->format('d/m/Y') }}
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full {{ $pathology->status === 'active' ? 'text-green-800 bg-green-200 dark:bg-green-700 dark:text-green-300' : 'text-gray-800 bg-gray-200 dark:bg-gray-700 dark:text-gray-300' }}">
                                                    {{ ucfirst($pathology->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                            No hay patologías asignadas.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sección de Medicamentos -->
            <div class="mt-8">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Medicamentos</h3>
                    <a href="{{ route('users.medicines', $user->id) }}"
                        class="px-3 py-2 text-xs font-medium text-white bg-green-700 rounded-lg hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                        Gestionar Medicamentos
                    </a>
                </div>
                <div class="bg-white rounded-lg shadow dark:bg-gray-800">
                    @php
                        $userMedicines = \App\Models\PatientMedicine::whereHas('patientPathology', function($query) use ($user) {
                            $query->where('user_id', $user->id);
                        })->with(['medicine', 'patientPathology.pathology'])->get();
                    @endphp
                    @if($userMedicines->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">Medicamento</th>
                                        <th scope="col" class="px-6 py-3">Patología</th>
                                        <th scope="col" class="px-6 py-3">Dosis</th>
                                        <th scope="col" class="px-6 py-3">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($userMedicines as $key => $medicine)
                                        <tr class="{{ $key % 2 === 0 ? 'bg-white dark:bg-gray-800' : 'bg-gray-50 dark:bg-gray-700' }} border-b dark:border-gray-600">
                                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                {{ $medicine->medicine->name }}
                                            </td>
                                            <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                                {{ $medicine->patientPathology->pathology->name }}
                                            </td>
                                            <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                                {{ $medicine->dosage }}
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full {{ $medicine->status === 'active' ? 'text-green-800 bg-green-200 dark:bg-green-700 dark:text-green-300' : 'text-gray-800 bg-gray-200 dark:bg-gray-700 dark:text-gray-300' }}">
                                                    {{ ucfirst($medicine->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                            No hay medicamentos asignados.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
