<x-layouts.app.sidebar :title="$title ?? null">
    <flux:main>
        {{ $slot }}
        {{-- <footer class="bg-gray-800 text-white dark:bg-gray-200 dark:text-gray-900n">
            <div class="container mx-auto text-center text-sm text-gray-500">
                <p>© 2025 <a class="hover:text-blue-500" href="https://hospitalatlantida.com/">Hospital General Atlántida.</a> Todos los Derechos Reservados.</p>
            </div>
        </footer> --}}
    </flux:main>
</x-layouts.app.sidebar>
