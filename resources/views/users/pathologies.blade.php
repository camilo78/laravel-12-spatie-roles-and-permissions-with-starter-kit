<x-layouts.app>
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Patologías de {{ $user->name }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">Gestiona las patologías del paciente</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <div>
        @session('success')
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                x-transition:leave="transition ease-out duration-500" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="flex items-center p-2 mb-4 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50 dark:bg-green-900 dark:text-green-300 dark:border-green-800"
                role="alert">
                <svg class="flex-shrink-0 w-8 h-8 mr-1 text-green-700 dark:text-green-300"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"></path>
                </svg>
                <span class="font-medium flex-1"> {{ $value }} </span>
                <button @click="show = false" type="button"
                    class="ml-2 text-green-800 hover:text-green-900 dark:text-green-300 dark:hover:text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endsession

        <div class="mt-6">
            <a href="{{ route('users.index') }}"
                class="cursor-pointer px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Regresar a Usuarios
            </a>
        </div>

        <!-- Formulario para asignar nueva patología -->
        <div class="mt-6 space-y-6">
            <form action="{{ route('users.pathologies.assign', $user) }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @livewire('components.pathology-search')

                    <flux:input label="Fecha de Diagnóstico" type="date" name="diagnosed_at" required />

                    <flux:select label="Estado" name="status" required class="lg:col-span-2">
                        <option value="active">Activa</option>
                        <option value="inactive">Inactiva</option>
                        <option value="controlled">Controlada</option>
                    </flux:select>
                    <button type="submit" class="mt-6 w-auto px-4 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 transition-colors">
                        Asignar Patología
                    </button>
                </div>
            </form>
        </div>

        <!-- Lista de patologías asignadas -->
        <div class="relative overflow-x-auto rounded-lg shadow-md dark:shadow-none mt-4 w-full">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Código CIE-10</th>
                        <th scope="col" class="px-6 py-3">Descripción</th>
                        <th scope="col" class="px-6 py-3">Fecha Diagnóstico</th>
                        <th scope="col" class="px-6 py-3">Estado</th>
                        <th scope="col" class="px-6 py-3 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($patientPathologies as $key => $patientPathology)
                        <tr class="{{ $key % 2 === 0 ? 'bg-white dark:bg-gray-800' : 'bg-gray-50 dark:bg-gray-700' }} border-b dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-600">
                            <td class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $patientPathology->pathology->clave }}
                            </td>
                            <td class="px-6 py-2 text-gray-600 dark:text-gray-300">
                                {{ $patientPathology->pathology->descripcion }}
                            </td>
                            <td class="px-6 py-2 text-gray-600 dark:text-gray-300">
                                {{ $patientPathology->diagnosed_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-2 text-gray-600 dark:text-gray-300">
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-800 bg-gray-200 rounded-full dark:bg-gray-700 dark:text-gray-300">
                                    @if($patientPathology->status === 'active') Activa
                                    @elseif($patientPathology->status === 'controlled') Controlada
                                    @else Inactiva @endif
                                </span>
                            </td>
                            <td class="px-6 py-2 text-center">
                                <div class="flex flex-col gap-2 w-full sm:flex-row sm:w-auto sm:justify-center lg:flex-row lg:w-auto lg:gap-1 lg:flex-nowrap">
                                    <a href="{{ route('users.pathologies.edit', [$user, $patientPathology]) }}"
                                        class="inline-flex items-center justify-center px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 flex-grow sm:flex-none">
                                        <flux:icon.square-pen variant="micro" />
                                    </a>
                                    <form action="{{ route('users.pathologies.remove', [$user, $patientPathology]) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" onclick="return confirm('¿Remover patología?')"
                                            class="inline-flex items-center justify-center px-3 py-2 text-xs font-medium text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800 flex-grow sm:flex-none">
                                            <flux:icon.trash-2 variant="micro" />
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="bg-white dark:bg-gray-800">
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                No hay patologías asignadas a este paciente.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>