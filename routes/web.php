<?php

use App\Livewire\Localities\LocalityCreate;
use App\Livewire\Localities\LocalityEdit;
use App\Livewire\Localities\LocalityIndex;
use App\Livewire\Localities\LocalityShow;
use App\Livewire\Roles\RoleCreate;
use App\Livewire\Roles\RoleEdit;
use App\Livewire\Roles\RoleIndex;
use App\Livewire\Roles\RoleShow;
use App\Livewire\Users\UserCreate;
use App\Livewire\Users\UserEdit;
use App\Livewire\Users\UserIndex;
use App\Livewire\Users\UserShow;



use App\Http\Controllers\UserExcelController;
use App\Livewire\Deliveries\DeliveryCreate;
use App\Livewire\Deliveries\DeliveryEdit;
use App\Livewire\Deliveries\DeliveryIndex;
use App\Livewire\Deliveries\DeliveryShow;
use App\Livewire\Deliveries\DeliveryPatientMedicines;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
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
    
    // Excel Routes
    Route::get('users/export/excel', [UserExcelController::class, 'export'])->name('users.export.excel');
    Route::get('users/template/excel', [UserExcelController::class, 'template'])->name('users.template.excel');
    Route::post('users/import/excel', [UserExcelController::class, 'import'])->name('users.import.excel');

    /*
     * Role Management
     * */
    Route::get('roles', RoleIndex::class)->name('roles.index')->middleware('permission:roles.index|roles.create|roles.edit|roles.delete');
    Route::get('roles/create', RoleCreate::class)->name('roles.create')->middleware('permission:roles.create');
    Route::get('roles/{role}/edit', RoleEdit::class)->name('roles.edit')->middleware('permission:roles.edit');
    Route::get('roles/{role}/show', RoleShow::class)->name('roles.show')->middleware('permission:roles.show');

    /*
     * Locality Management
     * */
    Route::get('localities', LocalityIndex::class)->name('localities.index');
    Route::get('localities/create', LocalityCreate::class)->name('localities.create');
    Route::get('localities/{locality}/edit', LocalityEdit::class)->name('localities.edit');
    Route::get('localities/{locality}/show', LocalityShow::class)->name('localities.show');

    /*
     * Medical Management
     * */
    Route::get('pathologies', \App\Livewire\Pathologies\PathologyIndex::class)->name('pathologies.index');
    Route::get('pathologies/create', \App\Livewire\Pathologies\PathologyCreate::class)->name('pathologies.create');
    Route::get('pathologies/{pathology}', \App\Livewire\Pathologies\PathologyShow::class)->name('pathologies.show');
    Route::get('pathologies/{pathology}/edit', \App\Livewire\Pathologies\PathologyEdit::class)->name('pathologies.edit');
    Route::get('medicines', \App\Livewire\Medicines\MedicineIndex::class)->name('medicines.index');
    Route::get('medicines/create', \App\Livewire\Medicines\MedicineCreate::class)->name('medicines.create');
    Route::get('medicines/{medicine}', \App\Livewire\Medicines\MedicineShow::class)->name('medicines.show');
    Route::get('medicines/{medicine}/edit', \App\Livewire\Medicines\MedicineEdit::class)->name('medicines.edit');
    
    /*
     * Delivery Management
     * */
    Route::get('deliveries', DeliveryIndex::class)->name('deliveries.index');
    Route::get('deliveries/create', DeliveryCreate::class)->name('deliveries.create');
    Route::get('deliveries/{delivery}/edit', DeliveryEdit::class)->name('deliveries.edit');
    Route::get('deliveries/{delivery}', DeliveryShow::class)->name('deliveries.show');
    Route::get('deliveries/{delivery}/patients/{deliveryPatient}', DeliveryPatientMedicines::class)->name('deliveries.patient.medicines');
    
    // User Medical Management
    Route::get('users/{user}/pathologies', \App\Livewire\Users\Pathologies\UserPathologies::class)->name('users.pathologies');
    Route::get('users/{user}/medicines', \App\Livewire\Users\Medicines\UserMedicines::class)->name('users.medicines');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
