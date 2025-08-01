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
use App\Http\Controllers\PathologyController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\PatientPathologyController;
use App\Http\Controllers\PatientMedicineController;
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
    Route::resource('pathologies', PathologyController::class);
    Route::resource('medicines', MedicineController::class);
    
    /*
     * Delivery Management
     * */
    Route::get('deliveries', DeliveryIndex::class)->name('deliveries.index');
    Route::get('deliveries/create', DeliveryCreate::class)->name('deliveries.create');
    Route::get('deliveries/{delivery}/edit', DeliveryEdit::class)->name('deliveries.edit');
    Route::get('deliveries/{delivery}', DeliveryShow::class)->name('deliveries.show');
    Route::get('deliveries/{delivery}/patients/{deliveryPatient}', DeliveryPatientMedicines::class)->name('deliveries.patient.medicines');
    
    // User Medical Management
    Route::get('users/{user}/pathologies', [PatientPathologyController::class, 'userPathologies'])->name('users.pathologies');
    Route::post('users/{user}/pathologies', [PatientPathologyController::class, 'assignPathology'])->name('users.pathologies.assign');
    Route::get('users/{user}/pathologies/{patientPathology}/edit', [PatientPathologyController::class, 'editPathology'])->name('users.pathologies.edit');
    Route::put('users/{user}/pathologies/{patientPathology}', [PatientPathologyController::class, 'updatePathology'])->name('users.pathologies.update');
    Route::delete('users/{user}/pathologies/{patientPathology}', [PatientPathologyController::class, 'removePathology'])->name('users.pathologies.remove');
    
    Route::get('users/{user}/medicines', [PatientMedicineController::class, 'userMedicines'])->name('users.medicines');
    Route::post('users/{user}/medicines', [PatientMedicineController::class, 'assignMedicine'])->name('users.medicines.assign');
    Route::get('users/{user}/medicines/{patientMedicine}/edit', [PatientMedicineController::class, 'editMedicine'])->name('users.medicines.edit');
    Route::put('users/{user}/medicines/{patientMedicine}', [PatientMedicineController::class, 'updateMedicine'])->name('users.medicines.update');
    Route::delete('users/{user}/medicines/{patientMedicine}', [PatientMedicineController::class, 'removeMedicine'])->name('users.medicines.remove');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
