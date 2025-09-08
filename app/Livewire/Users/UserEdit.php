<?php

namespace App\Livewire\Users;

use App\Models\User;
use App\Models\Department;
use App\Models\Municipality;
use App\Models\Locality;
use Illuminate\View\View;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Componente Livewire para editar usuarios
 * 
 * Gestiona el formulario de edición de usuarios con validación,
 * actualización de ubicación geográfica y modificación de roles.
 */
class UserEdit extends Component
{
    // Usuario a editar
    public User $user;
    
    // Datos del usuario
    public string $name = '';
    public ?string $email = null;
    public string $dni = '';
    public string $phone = '';
    public string $address = '';
    public string $gender = '';
    public ?string $admission_date = null;
    public bool $departmental_delivery = false;
    
    // Campos de contraseña (opcionales en edición)
    public ?string $password = null;
    public ?string $confirm_password = null;
    
    // Ubicación geográfica
    public ?int $department_id = null;
    public ?int $municipality_id = null;
    public ?int $locality_id = null;
    public string $locality_search = '';
    
    // Estado y roles
    public bool $status = true;
    public array $roles = [];
    
    // Colecciones para selects
    public $allRoles;
    public $departments = [];
    public $municipalities = [];
    public $localities;
    public $filtered_localities = [];
    
    // Control de envío
    public bool $isSubmitting = false;

    /**
     * Inicializa el componente con los datos del usuario a editar
     */
    public function mount(User $user): void
    {
        $this->user = $user;
        
        // Llenar propiedades con datos del usuario
        $this->fill($user->only([
            'name', 'email', 'dni', 'phone', 'address', 'gender', 'status',
            'department_id', 'municipality_id', 'locality_id', 'departmental_delivery'
        ]));
        
        // Formatear fecha de ingreso para el input date
        $this->admission_date = $user->admission_date ? $user->admission_date->format('Y-m-d') : null;

        // Inicializar locality_search con el nombre de la localidad actual
        if ($this->locality_id) {
            $locality = Locality::find($this->locality_id);
            $this->locality_search = $locality ? $locality->name : '';
        }

        $this->loadInitialData();
    }

    /**
     * Carga los datos iniciales necesarios para el formulario
     */
    protected function loadInitialData(): void
    {
        $this->allRoles = Role::orderBy('name')->get();
        $this->roles = $this->user->roles->pluck('name')->toArray();
        $this->departments = Department::orderBy('name')->get();
        $this->refreshLocations();
    }

    /**
     * Actualiza las colecciones de municipios y localidades
     * basado en las selecciones actuales
     */
    protected function refreshLocations(): void
    {
        $this->municipalities = $this->department_id
            ? Municipality::where('department_id', $this->department_id)
                ->orderBy('name')
                ->get()
            : collect();

        $this->localities = $this->municipality_id
            ? Locality::where('municipality_id', $this->municipality_id)
                ->orderBy('name')
                ->get()
            : collect();
    }

    /**
     * Se ejecuta cuando cambia el departamento seleccionado
     * Carga los municipios correspondientes y resetea selecciones dependientes
     */
    public function updatedDepartmentId(?int $value): void
    {
        $this->resetLocationSelections();
        $this->refreshLocations();
    }

    /**
     * Se ejecuta cuando cambia el municipio seleccionado
     * Carga las localidades correspondientes
     */
    public function updatedMunicipalityId(?int $value): void
    {
        $this->locality_id = null;
        $this->locality_search = '';
        $this->localities = collect();
        $this->filtered_localities = [];
        $this->refreshLocations();
    }

    /**
     * Se ejecuta cuando cambia el texto de búsqueda de localidad
     */
    public function updatedLocalitySearch(string $value): void
    {
        $value = rtrim($value);
        
        if (empty($value) || !$this->municipality_id) {
            $this->filtered_localities = [];
            return;
        }

        $this->filtered_localities = $this->localities->filter(function ($locality) use ($value) {
            return stripos($locality->name, $value) !== false;
        })->take(10);
    }

    /**
     * Selecciona una localidad de la lista filtrada
     */
    public function selectLocality(int $id, string $name): void
    {
        $this->locality_id = $id;
        $this->locality_search = $name;
        $this->filtered_localities = [];
    }

    /**
     * Resetea las selecciones de ubicación dependientes
     */
    private function resetLocationSelections(): void
    {
        $this->municipality_id = null;
        $this->locality_id = null;
        $this->locality_search = '';
        $this->municipalities = [];
        $this->localities = collect();
        $this->filtered_localities = [];
    }

    /**
     * Reglas de validación para el formulario de edición
     */
    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $this->user->id,
            'dni' => 'required|string|unique:users,dni,' . $this->user->id . '|max:13',
            'phone' => 'nullable|string|max:255',
            'address' => 'required|string|max:500',
            'gender' => 'required|in:Masculino,Femenino',
            'admission_date' => 'required|date|before_or_equal:today',
            'password' => 'nullable|string|min:8|same:confirm_password',
            'confirm_password' => $this->password ? 'required|string|min:8' : 'nullable',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,name',
            'department_id' => 'required|exists:departments,id',
            'municipality_id' => 'required|exists:municipalities,id',
            'locality_id' => 'required|exists:localities,id',
            'status' => 'boolean',
            'departmental_delivery' => 'boolean',
        ];
    }

    /**
     * Mensajes de validación personalizados
     */
    protected function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'email.unique' => 'Este email ya está registrado por otro usuario.',
            'dni.required' => 'El DNI es obligatorio.',
            'dni.unique' => 'Este DNI ya está registrado por otro usuario.',
            'address.required' => 'La dirección es obligatoria.',
            'gender.required' => 'Debe seleccionar un género.',
            'admission_date.required' => 'La fecha de ingreso es obligatoria.',
            'admission_date.date' => 'La fecha de ingreso debe ser una fecha válida.',
            'admission_date.before_or_equal' => 'La fecha de ingreso no puede ser futura.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.same' => 'Las contraseñas no coinciden.',
            'confirm_password.required' => 'Debe confirmar la nueva contraseña.',
            'roles.required' => 'Debe seleccionar al menos un rol.',
            'department_id.required' => 'Debe seleccionar un departamento.',
            'municipality_id.required' => 'Debe seleccionar un municipio.',
            'locality_id.required' => 'Debe seleccionar una localidad.',
        ];
    }

    /**
     * Actualiza el usuario en el sistema
     */
    public function editUser(): void
    {
        // Prevenir envíos múltiples
        if ($this->isSubmitting) {
            return;
        }
        
        $this->isSubmitting = true;
        
        try {
            // Validar datos
            $this->validate();
            
            // Usar transacción para garantizar consistencia
            DB::transaction(function () {
                // Actualizar usuario
                $this->user->update($this->prepareUpdateData());
                
                // Sincronizar roles
                $this->user->syncRoles($this->roles);
                
                // Mensaje de éxito
                session()->flash('success', "Usuario '{$this->user->name}' actualizado exitosamente.");
            });

            // Redireccionar con navegación SPA
            $this->redirect(route('users.index'), navigate: true);
            
        } catch (ValidationException $e) {
            $this->isSubmitting = false;
            throw $e;
        } catch (\Exception $e) {
            $this->isSubmitting = false;
            session()->flash('error', 'Error al actualizar el usuario. Inténtelo nuevamente.');
            
            // Log del error para debugging
            Log::error('Error actualizando usuario: ' . $e->getMessage(), [
                'user_id' => $this->user->id,
                'user_data' => [
                    'name' => $this->name,
                    'email' => $this->email,
                    'dni' => $this->dni,
                ]
            ]);
        }
    }

    /**
     * Prepara los datos para la actualización del usuario
     */
    protected function prepareUpdateData(): array
    {
        $data = [
            'name' => ucwords(trim($this->name)),
            'email' => $this->email ? strtolower(trim($this->email)) : null,
            'dni' => $this->dni,
            'phone' => $this->phone ?: null,
            'address' => trim($this->address),
            'gender' => $this->gender,
            'status' => $this->status,
            'admission_date' => $this->admission_date,
            'department_id' => $this->department_id,
            'municipality_id' => $this->municipality_id,
            'locality_id' => $this->locality_id,
            'departmental_delivery' => $this->departmental_delivery,
        ];
        
        // Solo actualizar contraseña si se proporcionó una nueva
        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }
        
        return $data;
    }

    /**
     * Renderiza la vista del componente
     */
    public function render(): View
    {
        return view('livewire.users.user-edit');
    }
}
