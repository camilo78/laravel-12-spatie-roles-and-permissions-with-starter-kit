<div>
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Show User') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Show User') }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <div>
        <a wire:navigate href="{{ route('users.index') }}" class="cursor-pointer px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Back
        </a>

        <div>
            <div class="overflow-x-auto mt-6">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 border dark:border-gray-700">
                    <tbody>
                    <tr class="border-b dark:border-gray-700 bg-white dark:bg-gray-900">
                        <th class="px-6 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">ID</th>
                        <td class="px-6 py-3 text-gray-600 dark:text-gray-300">{{ $user->id }}</td>
                    </tr>
                    <tr class="border-b dark:border-gray-700 bg-white dark:bg-gray-900">
                        <th class="px-6 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">Name</th>
                        <td class="px-6 py-3 text-gray-600 dark:text-gray-300">{{ $user->name }}</td>
                    </tr>
                    <tr class="border-b dark:border-gray-700 bg-white dark:bg-gray-900">
                        <th class="px-6 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">E-Mail</th>
                        <td class="px-6 py-3 text-gray-600 dark:text-gray-300">{{ $user->email }}</td>
                    </tr>
                    <tr class="border-b dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        <th class="px-6 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">{{ __('Created At') }}</th>
                        <td class="px-6 py-3 text-gray-600 dark:text-gray-300">{{ \Carbon\Carbon::parse($user->created_at)->translatedFormat('d F Y l H:i:s') }}</td>
                    </tr>
                    <tr class="bg-white dark:bg-gray-900">
                        <th class="px-6 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">{{ __('Updated At') }}</th>
                        <td class="px-6 py-3 text-gray-600 dark:text-gray-300">{{ \Carbon\Carbon::parse($user->updated_at)->translatedFormat('d F Y l H:i:s') }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
