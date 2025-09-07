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
use App\Livewire\Deliveries\WeeklySchedule;
use App\Livewire\SystemConfiguration\SystemConfigurationIndex;


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
    Route::get('users/export', [UserExcelController::class, 'export'])->name('users.export')->middleware('permission:users.export');
    Route::post('users/import', [UserExcelController::class, 'import'])->name('users.import')->middleware('permission:users.import');
    Route::get('users/template', [UserExcelController::class, 'template'])->name('users.template')->middleware('permission:users.export');

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
    Route::get('localities', LocalityIndex::class)->name('localities.index')->middleware('permission:localities.index');
    Route::get('localities/create', LocalityCreate::class)->name('localities.create')->middleware('permission:localities.create');
    Route::get('localities/{locality}/edit', LocalityEdit::class)->name('localities.edit')->middleware('permission:localities.edit');
    Route::get('localities/{locality}/show', LocalityShow::class)->name('localities.show')->middleware('permission:localities.show');

    /*
     * Medical Management
     * */
    Route::get('pathologies', \App\Livewire\Pathologies\PathologyIndex::class)->name('pathologies.index')->middleware('permission:pathologies.index');
    Route::get('pathologies/create', \App\Livewire\Pathologies\PathologyCreate::class)->name('pathologies.create')->middleware('permission:pathologies.create');
    Route::get('pathologies/{pathology}', \App\Livewire\Pathologies\PathologyShow::class)->name('pathologies.show')->middleware('permission:pathologies.show');
    Route::get('pathologies/{pathology}/edit', \App\Livewire\Pathologies\PathologyEdit::class)->name('pathologies.edit')->middleware('permission:pathologies.edit');
    Route::get('medicines', \App\Livewire\Medicines\MedicineIndex::class)->name('medicines.index')->middleware('permission:medicines.index');
    Route::get('medicines/create', \App\Livewire\Medicines\MedicineCreate::class)->name('medicines.create')->middleware('permission:medicines.create');
    Route::get('medicines/{medicine}', \App\Livewire\Medicines\MedicineShow::class)->name('medicines.show')->middleware('permission:medicines.show');
    Route::get('medicines/{medicine}/edit', \App\Livewire\Medicines\MedicineEdit::class)->name('medicines.edit')->middleware('permission:medicines.edit');
    
    /*
     * Delivery Management
     * */
    Route::get('deliveries', DeliveryIndex::class)->name('deliveries.index')->middleware('permission:deliveries.index');
    Route::get('deliveries/create', DeliveryCreate::class)->name('deliveries.create')->middleware('permission:deliveries.create');
    Route::get('deliveries/weekly-schedule', WeeklySchedule::class)->name('deliveries.weekly-schedule');
    Route::get('deliveries/{delivery}/edit', DeliveryEdit::class)->name('deliveries.edit')->middleware('permission:deliveries.edit');
    Route::get('deliveries/{delivery}', DeliveryShow::class)->name('deliveries.show')->middleware('permission:deliveries.show');
    Route::get('deliveries/{delivery}/patients/{deliveryPatient}', DeliveryPatientMedicines::class)->name('deliveries.patient.medicines')->middleware('permission:deliveries.show');
    
    // Delivery Reports
    Route::get('delivery/{delivery}/report', [\App\Http\Controllers\DeliveryReportController::class, 'generateReport'])->name('delivery.report')->middleware('permission:deliveries.show');
    
    // User Medical Management
    Route::get('users/{user}/pathologies', \App\Livewire\Users\Pathologies\UserPathologies::class)->name('users.pathologies')->middleware('permission:user-pathologies.index');
    Route::get('users/{user}/medicines', \App\Livewire\Users\Medicines\UserMedicines::class)->name('users.medicines')->middleware('permission:user-medicines.index');

    // System Configuration
    Route::get('system-configuration', SystemConfigurationIndex::class)->name('system-configuration.index')->middleware('permission:system-configuration.manage');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
