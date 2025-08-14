<div>
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Roles</flux:heading>
        <flux:subheading size="lg" class="mb-6">Gestiona todos los roles del sistema</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <div>
    <!-- Mensaje de éxito -->
    @session('success')
        <div x-data="{ show: true }" x-show="show"
            x-transition:leave="transition ease-out duration-500" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
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
        <div x-data="{ show: true }" x-show="show"
            x-transition:leave="transition ease-out duration-500" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="flex items-center p-2 mb-4 text-sm text-red-800 border border-red-300 rounded-lg bg-white dark:bg-white dark:text-red-300 dark:border-red-800"
            role="alert">

            <svg class="flex-shrink-0 w-8 h-8 mr-1 text-red-700 dark:text-red-300" xmlns="http://www.w3.org/2000/svg"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>

            <span class="font-medium flex-1">{{ $value }}</span>

            <button @click="show = false" type="button"
                class="ml-2 text-red-800 hover:text-red-900 dark:text-red-300 dark:hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endsession

        @can('roles.create')
            <a wire:navigate href="{{ route('roles.create') }}"
                class="cursor-pointer px-3 py-2 text-xs font-medium text-white bg-green-700 rounded-lg hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                Crear Rol
            </a>
        @endcan

        <div class="overflow-x-auto mt-4">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">ID</th>
                        <th scope="col" class="px-6 py-3">Nombre</th>
                        <th scope="col" class="px-6 py-3">Permisos</th>
                        <th scope="col" class="px-6 py-3 w-80">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                        <tr
                            class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200">
                            <td class="px-6 py-2 font-medium text-gray-900 dark:text-white">{{ $loop->iteration }}</td>
                            <td class="px-6 py-2 text-gray-600 dark:text-gray-300">{{ $role->name }}</td>
                            <td class="px-6 py-2 text-gray-600 dark:text-gray-300">
                                @if ($role->permissions->isNotEmpty())
                                    <ul class="list-disc">
                                        @foreach ($role->permissions as $permission)
                                            <flux:badge class="mt-2 mr-2">{{ $permission->name }}</flux:badge>
                                        @endforeach
                                    </ul>
                                @endif
                            </td>
                            <td class="px-6 py-2">
                                {{-- Contenedor de los botones de acción para roles --}}
                                {{-- Por defecto (móvil): flex-col (apilados), w-full (ancho completo). --}}
                                {{-- En sm (tablet): sm:flex-row (en fila), sm:w-full (ancho completo). --}}
                                {{-- En lg (escritorio): lg:w-auto (ancho autoajustado al contenido), lg:flex-nowrap (asegura que no se envuelvan). --}}
                                <div
                                    class="flex flex-col gap-2 w-full
                sm:flex-row sm:w-full sm:gap-1
                lg:flex-row lg:w-auto lg:gap-1 lg:flex-nowrap">

                                    @can('roles.show')
                                        <a wire:navigate href="{{ route('roles.show', $role->id) }}"
                                            class="cursor-pointer px-3 py-2 text-xs font-medium text-white bg-gray-700 rounded-lg hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800
                       text-center flex-grow
                       sm:flex-grow
                       lg:flex-grow-0">
                                            Ver
                                        </a>
                                    @endcan

                                    @can('roles.edit')
                                        <a wire:navigate href="{{ route('roles.edit', $role->id) }}"
                                            class="cursor-pointer px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800
                       text-center flex-grow
                       sm:flex-grow
                       lg:flex-grow-0">
                                            Editar
                                        </a>
                                    @endcan

                                    @can('roles.delete')
                                        @if(!in_array(strtolower($role->name), ['administrador', 'administrator']) && $role->users()->count() === 0)
                                            <button wire:navigate wire:click="deleteRole({{ $role->id }})"
                                                wire:confirm="¿Está seguro de eliminar este rol?"
                                                class="cursor-pointer px-3 py-2 text-xs font-medium text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800
                           text-center flex-grow
                           sm:flex-grow
                           lg:flex-grow-0">
                                                Eliminar
                                            </button>
                                        @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
