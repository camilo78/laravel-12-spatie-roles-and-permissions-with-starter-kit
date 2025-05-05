<div>
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Create Product') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Form For Create New Product') }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <div>
        <a wire:navigate href="{{ route('products.index') }}" class="cursor-pointer px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Back
        </a>

        <div>
            <form class="mt-6 space-y-6" wire:submit="createProduct">
                <flux:input label="Name" type="text" name="name" placeholder="Enter Name" wire:model="name" />
                <flux:textarea label="Detail" name="detail" placelhoder="Enter Detail" wire:model="detail" />
                <flux:button type="submit" variant="primary">Create Product</flux:button>
            </form>
        </div>
    </div>
</div>
