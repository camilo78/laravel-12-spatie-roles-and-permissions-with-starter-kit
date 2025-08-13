<div>
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Patologías de {{ $user->name }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">Gestiona las patologías asignadas al usuario</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <div>
        <a wire:navigate href="{{ route('users.index') }}"
            class="cursor-pointer px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Regresar a Usuarios
        </a>

        @session('success')
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                x-transition:leave="transition ease-out duration-500" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="flex items-center p-2 mb-4 mt-4 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50 dark:bg-green-900 dark:text-green-300 dark:border-green-800"
                role="alert">
                <svg class="flex-shrink-0 w-8 h-8 mr-1 text-green-700 dark:text-green-300"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"></path>
                </svg>
                <span class="font-medium flex-1"> {{ $value }} </span>
            </div>
        @endsession

        <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Asignar Nueva Patología</h3>
            
            <form wire:submit="assignPathology" class="space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <flux:select 
                        label="Patología" 
                        wire:model="pathology_id" 
                        required>
                        <option value="">Seleccionar patología</option>
                        @foreach($pathologies as $pathology)
                            <option value="{{ $pathology->id }}">{{ $pathology->clave }} - {{ $pathology->descripcion }}</option>
                        @endforeach
                    </flux:select>

                    <flux:input 
                        label="Fecha de Diagnóstico" 
                        type="date" 
                        wire:model="diagnosed_at" 
                        required />

                    <flux:select 
                        label="Estado" 
                        wire:model="status" 
                        required>
                        <option value="">Seleccionar estado</option>
                        <option value="active">Activo</option>
                        <option value="inactive">Inactivo</option>
                        <option value="controlled">Controlado</option>
                    </flux:select>
                </div>
                
                <div class="flex justify-end">
                    <flux:button type="submit" variant="primary">
                        <span wire:loading.remove wire:target="assignPathology">Asignar Patología</span>
                        <span wire:loading wire:target="assignPathology">Asignando...</span>
                    </flux:button>
                </div>
            </form>
        </div>

        <div class="mt-6 relative overflow-x-auto rounded-lg shadow-md dark:shadow-none">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Código</th>
                        <th scope="col" class="px-6 py-3">Descripción</th>
                        <th scope="col" class="px-6 py-3">Fecha Diagnóstico</th>
                        <th scope="col" class="px-6 py-3">Estado</th>
                        <th scope="col" class="px-6 py-3 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($patientPathologies as $key => $patientPathology)
                        <tr class="{{ $key % 2 === 0 ? 'bg-white dark:bg-gray-800' : 'bg-gray-50 dark:bg-gray-700' }} border-b dark:border-gray-600">
                            <td class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $patientPathology->pathology->clave }}
                            </td>
                            <td class="px-6 py-2 text-gray-600 dark:text-gray-300">
                                {{ $patientPathology->pathology->descripcion }}
                            </td>
                            <td class="px-6 py-2 text-gray-600 dark:text-gray-300">
                                {{ \Carbon\Carbon::parse($patientPathology->diagnosed_at)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-2">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    {{ $patientPathology->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : '' }}
                                    {{ $patientPathology->status === 'inactive' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' : '' }}
                                    {{ $patientPathology->status === 'controlled' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' : '' }}">
                                    {{ ucfirst($patientPathology->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-2 text-center">
                                <button wire:click="removePathology({{ $patientPathology->id }})" wire:confirm="¿Eliminar patología?"
                                    class="inline-flex items-center justify-center px-3 py-2 text-xs font-medium bg-white border border-gray-600 rounded-lg hover:bg-red-50 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-gray-700 dark:border-white dark:hover:bg-red-900 dark:focus:ring-red-800" title="Eliminar Patología">
                                    <flux:icon.trash-2 variant="micro" class="text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300"/>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr class="bg-white dark:bg-gray-800">
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                No hay patologías asignadas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>