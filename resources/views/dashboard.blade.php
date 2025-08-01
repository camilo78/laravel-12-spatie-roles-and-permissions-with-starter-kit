
@role('Admin')
<x-layouts.app :title="__('Dashboard - Admin')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-4">
                <div class="flex flex-col h-full">
                    <h3 class="text-lg font-semibold mb-2">Total de Usuarios: {{ $totalUsers }}</h3>
                    <div class="flex-1 relative">
                        {!! $chart->render() !!}
                    </div>
                </div>
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-4">
                <div class="flex flex-col h-full">
                    <h3 class="text-lg font-semibold mb-2">Usuarios por Género M: {{ $maleUsers }}   F: {{ $femaleUsers }}</h3>
                    <div class="flex-1 relative">
                        {!! $genderChart->render() !!}
                    </div>
                </div>
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
        </div>
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
        </div>
    </div>
</x-layouts.app>
@else
<x-layouts.app :title="__('Dashboard - User')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-4">
                <div class="flex flex-col h-full">
                    <h3 class="text-lg font-semibold mb-2">Total de Usuarios: {{ $totalUsers }}</h3>
                    <div class="flex-1 relative">
                        {!! $chart->render() !!}
                    </div>
                </div>
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-4">
                <div class="flex flex-col h-full">
                    <h3 class="text-lg font-semibold mb-2">Usuarios por Género</h3>
                    <div class="flex-1 relative">
                        {!! $genderChart->render() !!}
                    </div>
                </div>
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
        </div>
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
        </div>
    </div>
</x-layouts.app>
@endrole
