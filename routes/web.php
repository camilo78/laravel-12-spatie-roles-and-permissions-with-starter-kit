<?php

use App\Livewire\Roles\RoleCreate;
use App\Livewire\Roles\RoleEdit;
use App\Livewire\Roles\RoleIndex;
use App\Livewire\Roles\RoleShow;
use App\Livewire\Users\UserCreate;
use App\Livewire\Users\UserEdit;
use App\Livewire\Users\UserIndex;
use App\Livewire\Users\UserShow;
use App\Livewire\Zones\ZoneIndex;
use App\Livewire\Zones\ZoneCreate;
use App\Livewire\Zones\ZoneEdit;
use App\Livewire\Zones\ZoneShow;
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
    Route::get('users', UserIndex::class)->name('users.index')->middleware('permission:users.index|users.create|users.edit|users.delete');
    Route::get('users/create', UserCreate::class)->name('users.create')->middleware('permission:users.create');
    Route::get('users/{user}/edit', UserEdit::class)->name('users.edit')->middleware('permission:users.edit');
    Route::get('users/{user}/show', UserShow::class)->name('users.show')->middleware('permission:users.show');

    /*
     * Zone Management
     * */
    Route::get('zones', ZoneIndex::class)->name('zones.index');
    Route::get('zones/create', ZoneCreate::class)->name('zones.create');
    Route::get('zones/{zone}/edit', ZoneEdit::class)->name('zones.edit'); 
    Route::get('zones/{zone}/show', ZoneShow::class)->name('zones.show');   

    /*
     * Role Management
     * */
    Route::get('roles', RoleIndex::class)->name('roles.index')->middleware('permission:roles.index|roles.create|roles.edit|roles.delete');
    Route::get('roles/create', RoleCreate::class)->name('roles.create')->middleware('permission:roles.create');
    Route::get('roles/{role}/edit', RoleEdit::class)->name('roles.edit')->middleware('permission:roles.edit');
    Route::get('roles/{role}/show', RoleShow::class)->name('roles.show')->middleware('permission:roles.show');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
