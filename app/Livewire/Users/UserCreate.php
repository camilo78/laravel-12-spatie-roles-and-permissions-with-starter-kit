<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\View\View;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use App\Models\Department;
use App\Models\Municipality;

class UserCreate extends Component
{
    public $name, $email, $dui, $phone, $address, $gender, $password, $confirm_password, $allRoles;
    public $roles = [];
    public $departments = []; 
    public $department_id = '';
    public $municipality_id = '';
    public $municipalities = [];

    public function mount()
    {
        $this->allRoles = Role::latest()->get();
        $this->departments = Department::orderBy('name')->get(); 
    }

    public function updatedDepartmentId($value)
    {
        $this->municipalities = Municipality::where('department_id', $value)->get();
        $this->municipality_id = ''; 
    }

    public function render(): View
    {
        return view('livewire.users.user-create');
    }

    public function createUser()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'dui' => 'required|unique:users,dui',
            'phone' => 'nullable|string|max:15',
            'address' => 'required|string|max:255',
            'gender' => 'required|in:Masculino,Femenino',
            'password' => 'required|string|min:8|same:confirm_password',
            'roles' => 'required',
            'department_id' => 'required|exists:departments,id',
            'municipality_id' => 'required|exists:municipalities,id',
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
        ]);

        $user->syncRoles($this->roles);

        return to_route('users.index')->with('success', 'Usuario creado correctamente.');
    }
}