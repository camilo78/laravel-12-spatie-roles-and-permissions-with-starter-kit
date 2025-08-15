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
 * Componente Livewire para crear usuarios
 * 
 * Gestiona el formulario de creación de usuarios con validación,
 * selección de ubicación geográfica y asignación de roles.
 */
class UserCreate extends Component
{
    // Datos del usuario
    public string $name = '';
    public string $email = '';
    public string $dni = '';
    public string $phone = '';
    public string $address = '';
    public string $gender = '';

    
    // Ubicación geográfica
    public ?int $department_id = null;
    public ?int $municipality_id = null;
    public ?int $locality_id = null;
    
    // Estado y roles
    public bool $status = true;
    public array $roles = [];
    
    // Colecciones para selects
    public $allRoles;
    public $departments = [];
    public $municipalities = [];
    public $localities = [];
    
    // Control de envío
    public bool $isSubmitting = false;

    /**
     * Inicializa el componente cargando roles y departamentos
     */
    public function mount(): void
    {
        $this->allRoles = Role::orderBy('name')->get();
        $this->departments = Department::orderBy('name')->get();
    }

    /**
     * Se ejecuta cuando cambia el departamento seleccionado
     * Carga los municipios correspondientes y resetea selecciones dependientes
     */
    public function updatedDepartmentId(?int $value): void
    {
        $this->resetLocationSelections();
        
        if ($value) {
            $this->municipalities = Municipality::where('department_id', $value)
                ->get();
        }
    }

    /**
     * Se ejecuta cuando cambia el municipio seleccionado
     * Carga las localidades correspondientes
     */
    public function updatedMunicipalityId(?int $value): void
    {
        $this->locality_id = null;
        $this->localities = [];
        
        if ($value) {
            $this->localities = Locality::where('municipality_id', $value)
                ->orderBy('name')
                ->get();
        }
    }

    /**
     * Resetea las selecciones de ubicación dependientes
     */
    private function resetLocationSelections(): void
    {
        $this->municipality_id = null;
        $this->locality_id = null;
        $this->municipalities = [];
        $this->localities = [];
    }

    /**
     * Reglas de validación para el formulario
     */
    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email',
            'dni' => 'required|string|unique:users,dni|max:13',
            'phone' => 'nullable|string|max:255',
            'address' => 'required|string|max:500',
            'gender' => 'required|in:Masculino,Femenino',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,name',
            'department_id' => 'required|exists:departments,id',
            'municipality_id' => 'required|exists:municipalities,id',
            'locality_id' => 'required|exists:localities,id',
            'status' => 'boolean',
        ];
    }

    /**
     * Mensajes de validación personalizados
     */
    protected function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'email.email' => 'El formato del correo electrónico no es válido.',
            'email.unique' => 'Este email ya está registrado.',
            'dni.required' => 'El DNI es obligatorio.',
            'dni.unique' => 'Este DNI ya está registrado.',
            'dni.max' => 'El DNI no puede tener más de 13 caracteres.',
            'address.required' => 'La dirección es obligatoria.',
            'address.max' => 'La dirección no puede tener más de 500 caracteres.',
            'gender.required' => 'Debe seleccionar un género.',
            'roles.required' => 'Debe seleccionar al menos un rol.',
            'department_id.required' => 'Debe seleccionar un departamento.',
            'municipality_id.required' => 'Debe seleccionar un municipio.',
            'locality_id.required' => 'Debe seleccionar una localidad.',
        ];
    }

    /**
     * Crea un nuevo usuario en el sistema
     */
    public function createUser(): void
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
                // Crear usuario
                $user = User::create([
                    'name' => ucwords(trim($this->name)),
                    'email' => $this->email ? strtolower(trim($this->email)) : null,
                    'dni' => $this->dni,
                    'phone' => $this->phone ?: null,
                    'address' => trim($this->address),
                    'gender' => $this->gender,
                    'status' => $this->status,
                    'password' => Hash::make($this->dni),
                    'department_id' => $this->department_id,
                    'municipality_id' => $this->municipality_id,
                    'locality_id' => $this->locality_id,
                ]);

                // Asignar roles
                $user->syncRoles($this->roles);
                
                // Mensaje de éxito
                session()->flash('success', "Usuario '{$user->name}' creado exitosamente.");
            });

            // Redireccionar con navegación SPA
            $this->redirect(route('users.index'), navigate: true);
            
        } catch (ValidationException $e) {
            $this->isSubmitting = false;
            throw $e;
        } catch (\Exception $e) {
            $this->isSubmitting = false;
            session()->flash('error', 'Error al crear el usuario. Inténtelo nuevamente.');
            
            // Log del error para debugging
            Log::error('Error creando usuario: ' . $e->getMessage(), [
                'user_data' => [
                    'name' => $this->name,
                    'email' => $this->email,
                    'dni' => $this->dni,
                ]
            ]);
        }
    }

    /**
     * Renderiza la vista del componente
     */
    public function render(): View
    {
        return view('livewire.users.user-create');
    }
}
