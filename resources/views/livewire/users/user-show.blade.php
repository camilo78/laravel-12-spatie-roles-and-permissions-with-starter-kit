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
                        <tr>
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
                            <th class="px-6 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">Address
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
        </div>
    </div>
</div>
