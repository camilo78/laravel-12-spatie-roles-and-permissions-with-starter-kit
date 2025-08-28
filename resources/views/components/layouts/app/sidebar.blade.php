<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 overflow-y-hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />
        <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
            <x-app-logo />
        </a>
        <hr class="h-px bg-gray-200 border-0 dark:bg-gray-700">
        <flux:navlist variant="outline">
            <flux:navlist.group heading="Plataforma" class="grid">
                <flux:navlist.item wire:navigate icon="home" :href="route('dashboard')"
                    :current="request()->routeIs('dashboard')">Panel de Control</flux:navlist.item>
                @if (auth()->user()->can('users.index') ||
                        auth()->user()->can('users.create') ||
                        auth()->user()->can('users.edit') ||
                        auth()->user()->can('users.delete'))
                    <flux:navlist.item wire:navigate icon="users" :href="route('users.index')"
                        :current="request()->routeIs('users.*')">Usuarios
                    </flux:navlist.item>
                @endif
                @if (auth()->user()->can('deliveries.index'))
                    <flux:navlist.item wire:navigate icon="truck" :href="route('deliveries.index')"
                        :current="request()->routeIs('deliveries.index', 'deliveries.create', 'deliveries.edit', 'deliveries.show')">
                        Entregas
                    </flux:navlist.item>
                    <flux:navlist.item wire:navigate icon="calendar-days" :href="route('deliveries.weekly-schedule')"
                        :current="request()->routeIs('deliveries.weekly-schedule')">
                        Entregas Programadas
                    </flux:navlist.item>
                @endif
            </flux:navlist.group>
        </flux:navlist>
        <flux:spacer />
        <hr class="h-px  bg-gray-200 border-0 dark:bg-gray-700">
        <flux:navlist variant="outline">
            <flux:navlist.group heading="Configuración" class="grid">
                @role('User')
                    <flux:navlist.item wire:navigate icon="user" :href="route('settings.profile')"
                        :current="request()->routeIs('settings.*')">Editar mi Información
                    </flux:navlist.item>
                @endrole
                @role('Admin')
                    @if (auth()->user()->can('localities.index'))
                        <flux:navlist.item wire:navigate icon="map-pin-house" :href="route('localities.index')"
                            :current="request()->routeIs('localities.*')">Localidades
                        </flux:navlist.item>
                    @endif
                    @if (auth()->user()->can('pathologies.index'))
                        <flux:navlist.item wire:navigate icon="clipboard-plus" :href="route('pathologies.index')"
                            :current="request()->routeIs('pathologies.*')">
                            Patologías
                        </flux:navlist.item>
                    @endif
                    @if (auth()->user()->can('medicines.index'))
                        <flux:navlist.item wire:navigate icon="pill" :href="route('medicines.index')"
                            :current="request()->routeIs('medicines.*')">
                            Medicamentos
                        </flux:navlist.item>
                    @endif
                    @if (auth()->user()->can('roles.index'))
                        <flux:navlist.item wire:navigate icon="link-slash" :href="route('roles.index')"
                            :current="request()->routeIs('roles.*')">Roles
                        </flux:navlist.item>
                    @endif
                @endrole
            </flux:navlist.group>
        </flux:navlist>
        <!-- Desktop User Menu -->
        <flux:dropdown position="bottom" align="start">
            @php
                $gravatarUrl = auth()->user()->gravatarUrl(64);
                $gravatarExists = Str::contains(@get_headers($gravatarUrl)[0] ?? '', '200');
                $avatarSrc = $gravatarExists ? $gravatarUrl : null;
            @endphp
            <flux:profile :name="auth()->user()->name" :initials="auth()->user()->initials()" :avatar="$avatarSrc"
                icon-trailing="chevrons-up-down" />
            <flux:menu class="w-[220px]">
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            @php
                                $gravatarExists = @get_headers(auth()->user()->gravatarUrl(64))[0] ?? '';
                            @endphp
                            <span class="relative flex h-8 w-8 shrink-0  rounded-lg">
                                @if (Str::contains($gravatarExists, '200'))
                                    <img src="{{ auth()->user()->gravatarUrl(64) }}" alt="Avatar"
                                        class="h-full w-full rounded-lg object-cover">
                                @else
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                        {{ strtoupper(Str::substr(auth()->user()->name, 0, 2)) }}
                                    </span>
                                @endif
                            </span>
                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>
                <flux:menu.separator />
                <flux:menu.radio.group>
                    <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>Configuración
                    </flux:menu.item>
                </flux:menu.radio.group>
                <flux:menu.separator />
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        Cerrar Sesión
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:sidebar>
    <!-- Mobile User Menu -->
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
        <flux:spacer />
        <flux:dropdown position="top" align="end">
            @php
                $gravatarUrl = auth()->user()->gravatarUrl(64);
                $gravatarExists = Str::contains(@get_headers($gravatarUrl)[0] ?? '', '200');
                $avatarSrc = $gravatarExists ? $gravatarUrl : null;
            @endphp
            <flux:profile :name="auth()->user()->name" :initials="auth()->user()->initials()" :avatar="$avatarSrc"
                icon-trailing="chevrons-up-down" />
            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            @php
                                $gravatarExists = @get_headers(auth()->user()->gravatarUrl(64))[0] ?? '';
                            @endphp
                            <span class="relative flex h-8 w-8 shrink-0  rounded-lg">
                                @if (Str::contains($gravatarExists, '200'))
                                    <img src="{{ auth()->user()->gravatarUrl(64) }}" alt="Avatar"
                                        class="h-full w-full rounded-lg object-cover">
                                @else
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                        {{ strtoupper(Str::substr(auth()->user()->name, 0, 2)) }}
                                    </span>
                                @endif
                            </span>
                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>
                <flux:menu.separator />
                <flux:menu.radio.group>
                    <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>
                        Configuración</flux:menu.item>
                </flux:menu.radio.group>
                <flux:menu.separator />
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        Cerrar Sesión
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>
    {{ $slot }}
    @fluxScripts
</body>
</html>
