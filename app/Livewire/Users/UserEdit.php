<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\View\View;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use App\Models\Department;
use App\Models\Municipality; 

class UserEdit extends Component
{
    public $user, $name, $email, $dui, $phone, $address, $gender, $password, $confirm_password, $allRoles;
    public $roles = [];
    public $departments = [];
    public ?int $department_id = null;
    public ?int $municipality_id = null;
    public $municipalities = [];

    public function mount(User $user): void
    {
        $this->user = $user;
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->dui = $this->user->dui;
        $this->phone = $this->user->phone;
        $this->address = $this->user->address;
        $this->gender = $this->user->gender;
        $this->department_id = $this->user->department_id;
        $this->allRoles = Role::latest()->get();
        $this->roles = $this->user->roles->pluck('name')->toArray();
        $this->departments = Department::orderBy('name')->get();
        if ($this->department_id) {
            $this->municipalities = Municipality::where('department_id', $this->department_id)->get();
        }
        $this->municipality_id = $this->user->municipality_id;
    }

    public function updatedDepartmentId($value)
    {
        $this->municipalities = Municipality::where('department_id', $value)->get();
        $this->municipality_id = null; 
    }

    public function render(): View
    {
        return view('livewire.users.user-edit');
    }

    public function editUser()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->user->id,
            'dui' => 'required|unique:users,dui,' . $this->user->id,
            'phone' => 'nullable|string|max:15',
            'address' => 'required|string|max:255', 
            'gender' => 'required|in:Masculino,Femenino',
            'password' => 'nullable|string|min:8|same:confirm_password',
            'roles' => 'required',
            'department_id' => 'required|exists:departments,id',
            'municipality_id' => 'required|exists:municipalities,id',
        ]);

        $updateData = [
            'name' => $this->name,
            'email' => $this->email,
            'dui' => $this->dui,
            'phone' => $this->phone,
            'address' => $this->address,
            'gender' => $this->gender,
            'department_id' => $this->department_id,
            'municipality_id' => $this->municipality_id,
        ];

        if (!empty($this->password)) {
            $updateData['password'] = bcrypt($this->password);
        }

        $this->user->update($updateData);

        $this->user->syncRoles($this->roles);

        return to_route('users.index')->with('success', 'Usuario actualizado correctamente.');
    }
}