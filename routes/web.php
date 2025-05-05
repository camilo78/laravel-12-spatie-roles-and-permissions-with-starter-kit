<?php

use App\Livewire\Products\ProductCreate;
use App\Livewire\Products\ProductEdit;
use App\Livewire\Products\ProductIndex;
use App\Livewire\Products\ProductShow;
use App\Livewire\Roles\RoleCreate;
use App\Livewire\Roles\RoleEdit;
use App\Livewire\Roles\RoleIndex;
use App\Livewire\Roles\RoleShow;
use App\Livewire\Users\UserCreate;
use App\Livewire\Users\UserEdit;
use App\Livewire\Users\UserIndex;
use App\Livewire\Users\UserShow;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    /*
     * User Management
     * */
    Route::get('users', UserIndex::class)->name('users.index');
    Route::get('users/create', UserCreate::class)->name('users.create');
    Route::get('users/{user}/edit', UserEdit::class)->name('users.edit');
    Route::get('users/{user}/show', UserShow::class)->name('users.show');

    /*
     * Product Management
     * */
    Route::get('products', ProductIndex::class)->name('products.index');
    Route::get('products/create', ProductCreate::class)->name('products.create');
    Route::get('products/{product}/edit', ProductEdit::class)->name('products.edit');
    Route::get('products/{product}/show', ProductShow::class)->name('products.show');

    /*
     * Role Management
     * */
    Route::get('roles', RoleIndex::class)->name('roles.index');
    Route::get('roles/create', RoleCreate::class)->name('roles.create');
    Route::get('roles/{role}/edit', RoleEdit::class)->name('roles.edit');
    Route::get('roles/{role}/show', RoleShow::class)->name('roles.show');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
