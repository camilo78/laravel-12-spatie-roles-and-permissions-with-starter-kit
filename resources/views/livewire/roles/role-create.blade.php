<div>
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Create Role') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Form For Create New Role') }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <div>
        <a wire:navigate href="{{ route('roles.index') }}" class="cursor-pointer px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Back
        </a>

        <div>
            <form class="mt-6 space-y-6" wire:submit="createRole">
                <flux:input label="Name" type="text" name="name" placeholder="Enter Name" wire:model="name" />
                <flux:checkbox.group wire:model="permissions" label="Permissions">
                    @foreach($allPermissions as $allPermission)
                        <flux:checkbox value="{{ $allPermission->name }}" label="{{ $allPermission->name }}" />
                    @endforeach
                </flux:checkbox.group>
                <flux:button type="submit" variant="primary">Create Role</flux:button>
            </form>
        </div>
    </div>
</div>
