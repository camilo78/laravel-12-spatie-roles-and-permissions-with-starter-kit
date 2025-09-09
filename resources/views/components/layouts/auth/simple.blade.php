<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900">
        <div class="bg-background flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
            <div class="flex w-full max-w-sm flex-col gap-2">
                @php
                    $config = \App\Models\SystemConfiguration::first();
                    $appLogo = $config->app_logo ?? null;
                @endphp
                
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-2 font-medium" wire:navigate>
                    <span class="flex mb-1 items-center justify-center rounded-md" style="height: 150px; width: 150px;">
                        @if($appLogo)
                            <img src="{{ Storage::url($appLogo) }}" alt="Logo" class="h-25 w-25 object-contain">
                        @else
                            <x-app-logo-icon class="size-9 fill-current text-black dark:text-white" />
                        @endif
                    </span>
                    <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
                </a>
                <div class="flex flex-col gap-6">
                    {{ $slot }}
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
