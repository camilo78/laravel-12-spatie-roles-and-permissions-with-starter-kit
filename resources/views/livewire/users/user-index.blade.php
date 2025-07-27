<div>
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Users') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Manage your all your users') }}</flux:subheading>
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
        <div class="flex flex-col sm:flex-col lg:flex-row sm:items-start lg:items-center justify-between gap-4 mb-4">
            <div
                class="flex flex-row items-center gap-3 justify-between order-1 sm:justify-between sm:w-full lg:w-auto lg:justify-start lg:order-1">
                @can('users.create')
                    <a wire:navigate href="{{ route('users.create') }}"
                        class="px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 flex-grow sm:flex-grow lg:flex-grow-0">
                        Crear Usuario
                    </a>
                @endcan
                <button type="button"
                    class="px-3 py-2 text-xs font-medium text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700 flex-grow sm:flex-grow lg:flex-grow-0">
                    Importar Excel
                </button>
                <button type="button"
                    class="cursor-pointer px-3 py-2 text-xs font-medium text-white bg-green-700 rounded-lg hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 flex-grow sm:flex-grow lg:flex-grow-0">
                    Exportar Excel
                </button>
            </div>
            <div class="relative  order-2 sm:w-full lg:w-auto lg:order-2 ml-auto">
                <input type="search" wire:model.live.debounce.300ms="search" placeholder="Buscar usuario..."
                    class="w-full px-4 py-2 text-sm text-gray-900 placeholder-gray-500 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 dark:bg-gray-800 dark:text-white dark:placeholder-gray-400 dark:border-gray-700" />

            </div>
        </div>
        <div class="relative overflow-x-auto rounded-lg shadow-md dark:shadow-none mt-4 w-full"> {{-- Aseguramos w-full para el contenedor --}}
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">ID</th>
                        <th scope="col" class="px-6 py-3">Name</th>
                        <th scope="col" class="px-6 py-3">DNI</th>
                        <th scope="col" class="px-6 py-3">Phone</th>
                        <th scope="col" class="px-6 py-3">Genero</th>
                        <th scope="col" class="px-6 py-3">Roles</th>
                        <th scope="col" class="px-6 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $key => $user) {{-- Añadimos $key para el estilo de rayas --}}
                        <tr
                            class="{{ $key % 2 === 0 ? 'bg-white dark:bg-gray-800' : 'bg-gray-50 dark:bg-gray-700' }} border-b dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-600">
                            <td class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}
                            </td>
                            <td class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <div class="flex items-center gap-2 h-full">

                                    @php
                                        // Asegúrate de que $user tiene los métodos gravatarUrl y initials
                                        $gravatarUrl = method_exists($user, 'gravatarUrl')
                                            ? $user->gravatarUrl(64)
                                            : '';
                                        $headers = @get_headers($gravatarUrl);
                                        $gravatarExists = $headers && Str::contains($headers[0], '200');
                                        $avatarSrc = $gravatarExists ? $gravatarUrl : null;
                                        $initials = method_exists($user, 'initials')
                                            ? $user->initials()
                                            : Str::limit(
                                                strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $user->name)),
                                                2,
                                                '',
                                            );
                                    @endphp
                                    @if ($avatarSrc)
                                        <img src="{{ $avatarSrc }}" alt="{{ $user->name }}"
                                            class="w-8 h-8 rounded-full object-cover" />
                                    @else
                                        <span
                                            class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-300 text-gray-700 font-bold text-sm">
                                            {{ $initials }}
                                        </span>
                                    @endif
                                    <span>{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-2 text-gray-600 dark:text-gray-300">
                                {{ $user->dui ?? 'Not Specified' }}
                            </td>
                            <td class="px-6 py-2 text-gray-600 dark:text-gray-300">
                                {{ $user->phone ?? 'Not Specified' }}
                            </td>
                            <td class="px-6 py-2 text-gray-600 dark:text-gray-300 capitalize">
                                {{ $user->gender ?? 'Not Specified' }}
                            </td>
                            <td class="px-6 py-2 text-gray-600 dark:text-gray-300">
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
                            <td class="px-6 py-2 text-center">
                                <div
                                    class="flex flex-col gap-2 w-full
                                    sm:flex-row sm:w-auto sm:justify-center
                                    lg:flex-row lg:w-auto lg:gap-1 lg:flex-nowrap">

                                    @can('users.index')
                                        {{-- `wire:navigate` se mantiene porque 'show' es una navegación a otra página --}}
                                        <a wire:navigate href="{{ route('users.show', $user->id) }}"
                                            class="inline-flex items-center justify-center px-3 py-2 text-xs font-small text-white bg-gray-700 rounded-lg hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800
                                           flex-grow sm:flex-none">
                                            <flux:icon.eye variant="micro" />
                                        </a>
                                    @endcan

                                    @can('users.edit')
                                        {{-- `wire:navigate` se mantiene porque 'edit' es una navegación a otra página --}}
                                        <a wire:navigate href="{{ route('users.edit', $user->id) }}"
                                            class="inline-flex items-center justify-center px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800
                                           flex-grow sm:flex-none">
                                            <flux:icon.square-pen variant="micro" />
                                        </a>
                                    @endcan

                                    @can('users.delete')
                                        {{-- ¡Importante! `wire:navigate` se ELIMINA de este botón. --}}
                                        {{-- Solo se usa `wire:click` para ejecutar el método en el componente actual. --}}
                                        <button wire:click="deleteUser({{ $user->id }})"
                                            wire:confirm="Are you sure to remove this user?"
                                            class="inline-flex items-center justify-center px-3 py-2 text-xs font-medium text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800
                                           flex-grow sm:flex-none">
                                            <flux:icon.trash-2 variant="micro" />
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="bg-white dark:bg-gray-800">
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                No se encontraron usuarios.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div wire:loading wire:target="search, previousPage, nextPage, gotoPage"
                class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-60 dark:bg-gray-800 dark:bg-opacity-40 z-10">
                <div
                    class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                    <x-placeholder-pattern
                        class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
                </div>
            </div>
        </div>
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>
