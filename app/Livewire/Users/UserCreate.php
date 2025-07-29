<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\View\View;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use App\Models\Department;
use App\Models\Municipality;
use App\Models\Locality;

class UserCreate extends Component
{
    public $name, $email, $dui, $phone, $address, $gender, $password, $confirm_password, $allRoles;
    public $roles = [], $departments = [], $municipalities = [], $localities = [];
    public ?int $department_id = null;
    public ?int $municipality_id = null;
    public ?int $locality_id = null;

    public function mount(): void
    {
        $this->allRoles = Role::latest()->get();
        $this->departments = Department::orderBy('name')->get();
    }

    public function updatedDepartmentId($value)
    {
        $this->municipality_id = null;
        $this->municipalities = Municipality::where('department_id', $value)->get();
        $this->localities = [];
        $this->locality_id = null;
    }

    public function updatedMunicipalityId($value)
    {
        $this->locality_id = null;
        $this->localities = Locality::where('municipality_id', $value)->get();
    }

    public function createUser()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'dui' => 'required|unique:users,dui',
            'phone' => 'nullable|string|max:255',
            'address' => 'required|string|max:255',
            'gender' => 'required|in:Masculino,Femenino',
            'password' => 'required|string|min:8|same:confirm_password',
            'confirm_password' => 'required|string|min:8',
            'roles' => 'required|array|min:1',
            'department_id' => 'required|exists:departments,id',
            'municipality_id' => 'required|exists:municipalities,id',
            'locality_id' => 'required|exists:localities,id',
        ]);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'dui' => $this->dui,
            'phone' => $this->phone,
            'address' => $this->address,
            'gender' => $this->gender,
            'password' => bcrypt($this->password),
            'department_id' => $this->department_id,
            'municipality_id' => $this->municipality_id,
            'locality_id' => $this->locality_id,
        ]);

        $user->syncRoles($this->roles);

        return redirect()->route('users.index')->with('success', 'Usuario creado correctamente.');
    }

    public function render(): View
    {
        return view('livewire.users.user-create');
    }
}
