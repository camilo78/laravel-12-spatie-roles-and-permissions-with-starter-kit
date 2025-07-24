<div>
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Users') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Manage your all your users') }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <div>
        @session('success')
            <div class="flex items-center p-2 mb-4 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50 dark:bg-green-900 dark:text-green-300 dark:border-green-800"
                role="alert">
                <svg class="flex-shrink-0 w-8 h-8 mr-1 text-green-700 dark:text-green-300" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"></path>
                </svg>
                <span class="font-medium"> {{ $value }} </span>
            </div>
        @endsession

        <div class="flex flex-col sm:flex-col lg:flex-row sm:items-start lg:items-center justify-between gap-4 mb-4">
            <div
                class="flex flex-row items-center gap-3 justify-between order-1 sm:justify-between sm:w-full lg:w-auto lg:justify-start lg:order-1">
                @can('users.create')
                    <a wire:navigate href="{{ route('users.create') }}"
                        class="cursor-pointer px-3 py-2 text-xs font-medium text-white bg-green-700 rounded-lg hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 flex-grow sm:flex-grow lg:flex-grow-0">
                        Create User
                    </a>
                @endcan
                <button type="button"
                    class="px-3 py-2 text-xs font-medium text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700 flex-grow sm:flex-grow lg:flex-grow-0">
                    Importar Excel
                </button>
                <button type="button"
                    class="px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 flex-grow sm:flex-grow lg:flex-grow-0">
                    Exportar Excel
                </button>
            </div>
            <div class="relative  order-2 sm:w-full lg:w-auto lg:order-2 ml-auto">
                <input type="search" wire:model.live.debounce.300ms="search" placeholder="Buscar usuario..."
                    class=" px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>
        <div class="overflow-x-auto mt-4">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">ID</th>
                        <th scope="col" class="px-6 py-3">Name</th>
                        <th scope="col" class="px-6 py-3">Email</th>
                        <th scope="col" class="px-6 py-3">Genero</th>
                        <th scope="col" class="px-6 py-3">Roles</th>
                        <th scope="col" class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr
                            class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200">
                            <td class="px-6 py-2 font-medium text-gray-900 dark:text-white">
                                {{ $loop->iteration }}</td>

                            <td class="px-6 py-2 font-medium text-gray-900 dark:text-white flex items-center gap-2">

                                {{ $user->name }}

                            </td>

                            <td class="px-6 py-2 text-gray-600 dark:text-gray-300">
                                {{ $user->email }}</td>

                            <td class="px-6 py-2 text-gray-600 dark:text-gray-300 capitalize">

                                {{ $user->gender ?? 'Not Specified' }}

                            </td>

                            <td class="px-6 py-2 text-gray-600 dark:text-gray-300">

                                @if ($user->roles->isEmpty())
                                    <span
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-800 bg-gray-200 rounded-full dark:bg-gray-700 dark:text-gray-300">

                                        No Roles

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

                            <td class="px-6 py-2">

                                {{-- Contenedor de los botones de acción --}}

                                {{-- Por defecto (móvil): flex-col (apilados), w-full (ancho completo). --}}

                                {{-- En sm (tablet): sm:flex-row (en fila), sm:w-full (ancho completo). --}}

                                {{-- En lg (escritorio): lg:w-auto (ancho autoajustado al contenido), lg:flex-nowrap (asegura que no se envuelvan). --}}

                                <div
                                    class="flex flex-col gap-2 w-fullsm:flex-row sm:w-full sm:gap-1

lg:flex-row lg:w-auto lg:gap-1 lg:flex-nowrap">



                                    @can('users.index')
                                        <a wire:navigate href="{{ route('users.show', $user->id) }}"
                                            class="cursor-pointer px-3 py-2 text-xs font-medium text-white bg-gray-700 rounded-lg hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800 text-center flex-grow
sm:flex-grow

lg:flex-grow-0">

                                            Show

                                        </a>
                                    @endcan



                                    @can('users.edit')
                                        <a wire:navigate href="{{ route('users.edit', $user->id) }}"
                                            class="cursor-pointer px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800

text-center flex-grow

sm:flex-grow

lg:flex-grow-0">

                                            Edit

                                        </a>
                                    @endcan



                                    @can('users.delete')
                                        <button wire:navigate wire:click="deleteUser({{ $user->id }})"
                                            wire:confirm="Are you sure to remove this user?"
                                            class="cursor-pointer px-3 py-2 text-xs font-medium text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800

text-center flex-grow

sm:flex-grow

lg:flex-grow-0">

                                            Delete

                                        </button>
                                    @endcan


                                </div>

                            </td>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>
</div>
