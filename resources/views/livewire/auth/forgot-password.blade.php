<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        Password::sendResetLink($this->only('email'));

        session()->flash('status', 'Se enviará un enlace de restablecimiento si la cuenta existe.');
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header title="Recuperar Contraseña" description="Ingrese su correo electrónico para recibir un enlace de restablecimiento" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="sendPasswordResetLink" class="flex flex-col gap-6">
        <!-- Email Address -->
        <flux:input
            wire:model="email"
            label="Correo Electrónico"
            type="email"
            required
            autofocus
            placeholder="correo@ejemplo.com"
            viewable
        />

        <flux:button variant="primary" type="submit" class="w-full">Enviar Enlace de Restablecimiento</flux:button>
    </form>

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-400">
        O, regresar a
        <flux:link :href="route('login')" wire:navigate>iniciar sesión</flux:link>
    </div>
</div>
