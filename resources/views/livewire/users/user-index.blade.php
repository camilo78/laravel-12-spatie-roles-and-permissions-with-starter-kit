<div>
    <!-- Sección de encabezado -->
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Usuarios</flux:heading>
        <flux:subheading size="lg" class="mb-6">Gestiona todos tus usuarios</flux:subheading>
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

    <!-- Botones de acción -->
    <div class="flex flex-wrap items-center gap-3 mb-4">
        @can('users.create')
            <a wire:navigate href="{{ route('users.create') }}"
                class="px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Crear Usuario
            </a>
        @endcan
        <button @click="$refs.importModal.showModal()" type="button"
            class="cursor-pointer px-3 py-2 text-xs font-medium text-white bg-purple-700 rounded-lg hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-800 text-center inline-flex items-center justify-center gap-2">
            <flux:icon.arrow-down-tray variant="micro" class="w-4 h-4" />
            Importar Excel
        </button>
        <button wire:click="exportTemplate" wire:loading.attr="disabled" wire:target="exportTemplate"
            class="cursor-pointer px-3 py-2 text-xs font-medium text-white bg-purple-700 rounded-lg hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-800 text-center inline-flex items-center justify-center gap-2 disabled:opacity-50">
                <span wire:loading.remove wire:target="exportTemplate" class="flex items-center gap-2">
                    <flux:icon.document-text variant="micro" class="w-4 h-4" />
                    Descargar Muestra Excel
                </span>
                <span wire:loading wire:target="exportTemplate" class="flex items-center gap-2">
                    <span>Descargando</span><span class="animate-bounce">...</span>
                </span>
        </button>
        <button wire:click="exportAll" wire:loading.attr="disabled" wire:target="exportAll"
            class="cursor-pointer px-3 py-2 text-xs font-medium text-white bg-green-700 rounded-lg hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 text-center inline-flex items-center justify-center gap-2 disabled:opacity-50">
                <span wire:loading.remove wire:target="exportAll" class="flex items-center gap-2">
                    <flux:icon.arrow-up-tray variant="micro" class="w-4 h-4" />
                    Exportar Todo
                </span>
                <span wire:loading wire:target="exportAll" class="flex items-center gap-2">
                    <span>Generando</span><span class="animate-bounce">...</span>
                </span>
        </button>
        <button wire:click="exportFiltered" wire:loading.attr="disabled" wire:target="exportFiltered"
            class="cursor-pointer px-3 py-2 text-xs font-medium text-white bg-orange-600 rounded-lg hover:bg-orange-700 focus:ring-4 focus:outline-none focus:ring-orange-300 dark:bg-orange-600 dark:hover:bg-orange-700 dark:focus:ring-orange-800 text-center inline-flex items-center justify-center gap-2 disabled:opacity-50">
                <span wire:loading.remove wire:target="exportFiltered" class="flex items-center gap-2">
                    <flux:icon.funnel variant="micro" class="w-4 h-4" />
                    Exportar Filtrados
                </span>
                <span wire:loading wire:target="exportFiltered" class="flex items-center gap-2">
                    <span>Generando</span><span class="animate-bounce">...</span>
                </span>
        </button>
    </div>
    
    <!-- Filtros y búsqueda -->
    <div class="flex flex-col lg:flex-row lg:items-center gap-4 mb-6">
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
            <label class="text-sm font-medium text-gray-700 dark:text-gray-300 whitespace-nowrap">Fecha ingreso:</label>
            <input type="date" wire:model.live="startDate" 
                class="h-10 px-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
            <span class="text-gray-400">-</span>
            <input type="date" wire:model.live="endDate" 
                class="h-10 px-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                            <button wire:click="resetFilters" title="Limpiar filtros"
                class="inline-flex items-center justify-center h-10 w-10 text-sm border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:hover:bg-gray-600 dark:text-white transition-colors">
                ✕
            </button>
        </div>
        <div class="flex items-center justify-end gap-2 flex-1">
    <input type="search" wire:model.live.debounce.300ms="search" placeholder="Buscar usuario..."
        class="h-10 px-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400 flex-1 max-w-md" />
</div>

    </div>

    <!-- Sección de tabla -->
    <div class="relative overflow-x-auto rounded-lg shadow-md dark:shadow-none mt-4 w-full">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">ID</th>
                    <th scope="col" class="px-6 py-3">Nombre</th>
                    <th scope="col" class="px-6 py-3">Teléfono</th>
                    <th scope="col" class="px-6 py-3">Género</th>
                    <th scope="col" class="px-6 py-3">Fecha Ingreso</th>
                    <th scope="col" class="px-6 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $key => $user)
                    <tr class="{{ $key % 2 === 0 ? 'bg-white dark:bg-gray-800' : 'bg-gray-50 dark:bg-gray-700' }} border-b dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-600" @if(!$user->status) style="background-color: #fecaca !important;" @endif>
                        <td class="px-6 py-2 font-medium whitespace-nowrap {{ !$user->status ? 'text-gray-900' : 'text-gray-900 dark:text-white' }}" @if(!$user->status) style="background-color: #fecaca !important;" @endif>
                            {{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}
                        </td>
                        <td class="px-6 py-2 font-medium {{ !$user->status ? 'text-gray-900' : 'text-gray-900 dark:text-white' }}" @if(!$user->status) style="background-color: #fecaca !important;" @endif>
                            {{ $user->name }}
                        </td>
                        <td class="px-6 py-2 {{ !$user->status ? 'text-gray-700' : 'text-gray-600 dark:text-gray-300' }}" @if(!$user->status) style="background-color: #fecaca !important;" @endif>
                            {{ $user->phone ?? 'No especificado' }}
                        </td>
                        <td class="px-6 py-2 capitalize {{ !$user->status ? 'text-gray-700' : 'text-gray-600 dark:text-gray-300' }}" @if(!$user->status) style="background-color: #fecaca !important;" @endif>
                            {{ $user->gender ?? 'No especificado' }}
                        </td>
                        <td class="px-6 py-2 {{ !$user->status ? 'text-gray-700' : 'text-gray-600 dark:text-gray-300' }}" @if(!$user->status) style="background-color: #fecaca !important;" @endif>
                            {{ $user->admission_date ? $user->admission_date->format('d/m/Y') : 'No especificado' }}
                        </td>
                        <td class="px-6 py-2 text-center" @if(!$user->status) style="background-color: #fecaca !important;" @endif>
                            <div
                                class="flex flex-col gap-2 w-full sm:flex-row sm:w-auto sm:justify-center lg:flex-row lg:w-auto lg:gap-1 lg:flex-nowrap">
                                @can('users.index')
                                    <a wire:navigate href="{{ route('users.show', $user->id) }}"
                                        class="inline-flex items-center justify-center px-3 py-2 text-xs font-medium bg-white border border-gray-600 rounded-lg hover:bg-red-50 focus:ring-4 focus:outline-none focus:ring-gray-300 dark:bg-gray-700 dark:border-white dark:hover:bg-grey-900 dark:focus:ring-grey-800 flex-grow sm:flex-none"
                                        title="Mostrar Usuario">
                                        <flux:icon.eye variant="micro"
                                            class="text-gray-600 dark:text-gray-300 hover:text-gray-700 dark:hover:text-gray-200" />
                                    </a>
                                @endcan

                                @can('users.edit')
                                    <a wire:navigate href="{{ route('users.edit', $user->id) }}"
                                        class="inline-flex items-center justify-center px-3 py-2 text-xs font-medium bg-white border border-gray-600 rounded-lg hover:bg-red-50 focus:ring-4 focus:outline-none focus:ring-gray-300 dark:bg-gray-700 dark:border-white dark:hover:bg-grey-900 dark:focus:ring-grey-800 flex-grow sm:flex-none"
                                        title="Editar Usuario">
                                        <flux:icon.square-pen variant="micro"
                                            class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300" />
                                    </a>
                                @endcan

                                <a href="{{ route('users.pathologies', $user->id) }}"
                                    class="inline-flex items-center justify-center px-3 py-2 text-xs font-medium bg-white border border-gray-600 rounded-lg hover:bg-red-50 focus:ring-4 focus:outline-none focus:ring-gray-300 dark:bg-gray-700 dark:border-white dark:hover:bg-grey-900 dark:focus:ring-grey-800 flex-grow sm:flex-none"
                                    title="Patologías Usuario">
                                    <flux:icon.clipboard-plus variant="micro"
                                        class="text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300" />
                                </a>

                                <a href="{{ route('users.medicines', $user->id) }}"
                                    class="inline-flex items-center justify-center px-3 py-2 text-xs font-medium bg-white border border-gray-600 rounded-lg hover:bg-red-50 focus:ring-4 focus:outline-none focus:ring-gray-300 dark:bg-gray-700 dark:border-white dark:hover:bg-grey-900 dark:focus:ring-grey-800 flex-grow sm:flex-none"
                                    title="Medicamentos Usuario">
                                    <flux:icon.pill variant="micro"
                                        class="text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300" />
                                </a>

                                @can('users.delete')
                                    @if (!$user->hasRole('administrador') && !$user->hasRole('administrator'))
                                        <button wire:click="deleteUser({{ $user->id }})"
                                            wire:confirm="¿Está seguro de eliminar el usuario {{ $user->name }}?"
                                            class="inline-flex items-center justify-center px-3 py-2 text-xs font-medium bg-white border border-gray-600 rounded-lg hover:bg-red-50 focus:ring-4 focus:outline-none focus:ring-gray-300 dark:bg-gray-700 dark:border-white dark:hover:bg-grey-900 dark:focus:ring-grey-800 flex-grow sm:flex-none"
                                            title="Eliminar Usuario">
                                            <flux:icon.trash-2 variant="micro"
                                                class="text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300" />
                                        </button>
                                    @endif
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr class="bg-white dark:bg-gray-800">
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                            No se encontraron usuarios.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Overlay de carga -->
        <div wire:loading wire:target="search, previousPage, nextPage, gotoPage"
            class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-60 dark:bg-gray-800 dark:bg-opacity-40 z-10">
            <div
                class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern
                    class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
        </div>
    </div>

    <!-- Paginación -->
    <div class="mt-4">
        {{ $users->links('vendor.livewire.tailwind') }}
    </div>

        <!-- Modal de importación -->
    <dialog x-ref="importModal" class="modal rounded-lg">
        <div class="bg-white dark:bg-gray-800 rounded  shadow border border-gray-200 dark:border-gray-700  max-w-lg ">
            <!-- Header del modal -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center space-x-3">
                    <div
                        class="flex-shrink-0 w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Importar Usuarios</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Subir archivo Excel con datos de usuarios
                        </p>
                    </div>
                </div>
                <button type="button" @click="$refs.importModal.close()"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Contenido del modal -->
            <form x-data="{ loading: false }" @submit="loading = true" action="{{ route('users.import') }}"
                method="POST" enctype="multipart/form-data" class="p-6">

                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Seleccionar archivo
                        </label>
                        <input type="file" name="file" accept=".csv,.txt,.xlsx,.xls" required
                            class="w-full p-3 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-300 cursor-pointer transition-all duration-200 hover:bg-blue-50 hover:border-blue-400 dark:hover:bg-gray-600 dark:hover:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-600 file:text-white file:font-medium file:cursor-pointer file:transition-colors hover:file:bg-blue-700 dark:file:bg-blue-500 dark:hover:file:bg-blue-600" />
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                            <span class="font-medium">Formatos permitidos:</span> CSV, TXT, XLSX, XLS (Máx. 10MB)
                        </p>
                    </div>

                    <!-- Información adicional -->
                    <div
                        class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-3 mt-2">
                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-orange-600 dark:text-orange-400 mt-0.5 mr-2 flex-shrink-0"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-circle-alert-icon lucide-circle-alert">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" x2="12" y1="8" y2="12" />
                                <line x1="12" x2="12.01" y1="16" y2="16" />
                            </svg>
                            <div>
                                <h4 class="text-sm font-medium text-blue-800 dark:text-blue-300"> Información
                                    importante</h4>
                                <p class="text-sm text-blue-700 dark:text-blue-400 mt-1">
                                    Asegúrate de que el archivo tenga el formato correcto. Puedes descargar la plantilla
                                    como referencia.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones del modal -->
                <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="button" @click="$refs.importModal.close()"
                        class="mt-6 mr-2 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-600 dark:hover:bg-gray-700 focus:ring-2 focus:ring-gray-800 dark:focus:ring-gray-700 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="mt-6 mr-2 px-4 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 flex-grow sm:flex-grow lg:flex-grow-0"
                        :disabled="loading">
                        <span x-text="loading ? 'Importando...' : 'Importar Archivo'"></span>
                    </button>
                </div>
            </form>
        </div>
    </dialog>
</div>
