<div>
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Create User') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Form For Create New User') }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <div>
        <a wire:navigate href="{{ route('users.index') }}" class="cursor-pointer px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Back
        </a>

        <div>
            <form class="mt-6 space-y-6" wire:submit="createUser">
                <flux:input label="Name" type="text" name="name" placeholder="Enter Name" wire:model="name" />
                <flux:input label="Email" type="email" name="email" placeholder="Enter Email" wire:model="email" />
                <flux:input label="Password" type="password" name="password" placeholder="Enter Password" wire:model="password" />
                <flux:input label="Confirm Password" type="password" name="confirm_password" placeholder="Enter Password (Again)" wire:model="confirm_password" />
                <flux:checkbox.group wire:model="roles" label="Roles">
                    @foreach($allRoles as $allRole)
                        <flux:checkbox value="{{ $allRole->name }}" label="{{ $allRole->name }}" />
                    @endforeach
                </flux:checkbox.group>
                <flux:button type="submit" variant="primary">Create User</flux:button>
            </form>
        </div>
    </div>
</div>
