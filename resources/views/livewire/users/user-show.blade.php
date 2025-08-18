<div>
    {{-- Encabezado de la página de perfil --}}
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Perfil del Usuario</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ $user->name }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>
    
    <div>
        {{-- Botón para regresar al listado de usuarios --}}
        <a wire:navigate href="{{ route('users.index') }}"
            class="cursor-pointer px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Regresar a Usuarios
        </a>
        
        <div>
            {{-- Tabla de información personal del usuario --}}
            <div class="overflow-x-auto mt-6">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 border dark:border-gray-700">
                    <tbody>
                        {{-- Primera fila: Información básica --}}
                        <tr class="border-b dark:border-gray-700 bg-white dark:bg-gray-900">
                            <th class="px-6 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">Nombre:</th>
                            <td class="px-6 py-3 border-r dark:border-gray-700 text-gray-600 dark:text-gray-300">
                                {{ $user->name }}
                            </td>
                            <th class="px-6 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">DNI:</th>
                            <td class="px-6 py-3 border-r dark:border-gray-700 text-gray-600 dark:text-gray-300">
                                {{ $user->dni ?? 'No especificado' }}
                            </td>
                            <th class="px-6 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">Correo Electrónico:</th>
                            <td class="px-6 py-3 text-gray-600 dark:text-gray-300">
                                {{ $user->email ?? 'No especificado' }}
                            </td>
                        </tr>
                        {{-- Segunda fila: Información de contacto y ubicación --}}
                        <tr class="border-b dark:border-gray-700 bg-white dark:bg-gray-900">
                            <th class="px-6 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">Teléfonos:</th>
                            <td class="px-6 py-3 border-r dark:border-gray-700 text-gray-600 dark:text-gray-300">
                                {{ $user->phone ?? 'No especificado' }}
                            </td>
                            <th class="px-6 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">Departamento:</th>
                            <td class="px-6 py-3 border-r dark:border-gray-700 text-gray-600 dark:text-gray-300">
                                {{ $user->department->name ?? 'No especificado' }}
                            </td>
                            <th class="px-6 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">Municipio:</th>
                            <td class="px-6 py-3 text-gray-600 dark:text-gray-300">
                                {{ $user->municipality->name ?? 'No especificado' }}
                            </td>
                        </tr>
                        {{-- Tercera fila: Localidad, dirección y roles --}}
                        <tr class="border-b dark:border-gray-700 bg-white dark:bg-gray-900">
                            <th class="px-6 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">Localidad:</th>
                            <td class="px-6 py-3 border-r dark:border-gray-700 text-gray-600 dark:text-gray-300">
                                {{ $user->locality->name ?? 'No especificado' }}
                            </td>
                            <th class="px-6 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">Dirección:</th>
                            <td class="px-6 py-3 border-r dark:border-gray-700 text-gray-600 dark:text-gray-300">
                                {{ $user->address ?? 'No especificado' }}
                            </td>
                            <th class="px-6 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">Roles:</th>
                            <td class="px-6 py-3 text-gray-600 dark:text-gray-300">
                                {{-- Mostrar roles del usuario o mensaje si no tiene --}}
                                @if (!$user->roles || $user->roles->isEmpty())
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-800 bg-gray-200 rounded-full dark:bg-gray-700 dark:text-gray-300">
                                        Sin Roles
                                    </span>
                                @else
                                    @foreach ($user->roles as $role)
                                        <span class="inline-flex items-center px-2 py-1 mr-1 text-xs font-medium text-gray-800 bg-gray-200 rounded-full dark:bg-gray-700 dark:text-gray-300">
                                            {{ $role->name }}
                                        </span>
                                    @endforeach
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            {{-- Grid de 2 columnas para patologías y medicamentos --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                {{-- Card de Patologías --}}
                <div class="bg-white rounded-lg shadow-md dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                    {{-- Header de la card --}}
                    <div class="flex justify-between items-center p-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Patologías</h3>
                        <a href="{{ route('users.pathologies', $user->id) }}"
                            class="px-3 py-2 text-xs font-medium text-white bg-green-700 rounded-lg hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 inline-flex items-center gap-2">
                            <flux:icon.clipboard-plus variant="micro" class="w-4 h-4"/>
                            Gestionar
                        </a>
                    </div>
                    {{-- Contenido de la card --}}
                    <div class="p-4">
                        @if ($user->patientPathologies && $user->patientPathologies->count() > 0)
                            <div class="relative overflow-x-auto rounded-lg shadow-md dark:shadow-none">
                                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">Patología</th>
                                            <th scope="col" class="px-6 py-3">Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($user->patientPathologies as $key => $pathology)
                                            <tr class="{{ $key % 2 === 0 ? 'bg-white dark:bg-gray-800' : 'bg-gray-50 dark:bg-gray-700' }} border-b dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-600">
                                                <td class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                    {{ $pathology->pathology->clave }} - {{ Str::limit($pathology->pathology->descripcion, 40) }}
                                                </td>
                                                <td class="px-6 py-2">
                                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full border {{ $pathology->status === 'active' ? 'text-green-600 border-green-600 dark:text-green-400 dark:border-green-400' : 'text-gray-600 border-gray-600 dark:text-gray-400 dark:border-gray-400' }}">
                                                        {{ $pathology->status === 'active' ? 'Activa' : 'Inactiva' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center text-gray-500 dark:text-gray-400 py-8">
                                No hay patologías asignadas.
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Card de Medicamentos --}}
                <div class="bg-white rounded-lg shadow-md dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                    {{-- Header de la card --}}
                    <div class="flex justify-between items-center p-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Medicamentos</h3>
                        <a href="{{ route('users.medicines', $user->id) }}"
                            class="px-3 py-2 text-xs font-medium text-white bg-green-700 rounded-lg hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 inline-flex items-center gap-2">
                            <flux:icon.pill variant="micro" class="w-4 h-4"/>
                            Gestionar
                        </a>
                    </div>
                    {{-- Contenido de la card --}}
                    <div class="p-4">
                        @php
                            $userMedicines = \App\Models\PatientMedicine::where('user_id', $user->id)
                                ->with('medicine')
                                ->get();
                        @endphp
                        @if ($userMedicines->count() > 0)
                            <div class="relative overflow-x-auto rounded-lg shadow-md dark:shadow-none">
                                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">Medicamento</th>
                                            <th scope="col" class="px-6 py-3">Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($userMedicines as $key => $medicine)
                                            <tr class="{{ $key % 2 === 0 ? 'bg-white dark:bg-gray-800' : 'bg-gray-50 dark:bg-gray-700' }} border-b dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-600">
                                                <td class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                    {{ $medicine->medicine->generic_name }}
                                                </td>
                                                <td class="px-6 py-2">
                                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full border {{ $medicine->status === 'active' ? 'text-green-600 border-green-600 dark:text-green-400 dark:border-green-400' : 'text-gray-600 border-gray-600 dark:text-gray-400 dark:border-gray-400' }}">
                                                        {{ $medicine->status === 'active' ? 'Activo' : 'Inactivo' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center text-gray-500 dark:text-gray-400 py-8">
                                No hay medicamentos asignados.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
