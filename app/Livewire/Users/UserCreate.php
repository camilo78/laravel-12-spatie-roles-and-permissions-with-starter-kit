<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\View\View;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class UserCreate extends Component
{
    public $name, $email, $gender, $password, $confirm_password, $allRoles;
    public $roles = [];

    public function mount()
    {
        $this->allRoles = Role::latest()->get();
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
            'dui' => 'nullable|string|max:13',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
            'password' => 'required|string|min:8|same:confirm_password',
            'gender' => 'required|in:Masculino,Femenino',
            'roles' => 'required',
        ]);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'dui' => $this->dui,
            'phone' => $this->phone,
            'address' => $this->address,
            'email' => $this->gender,
            'password' => bcrypt($this->password),
        ]);

        $user->syncRoles($this->roles);

        return to_route('users.index')->with('success', 'User created successfully.');
    }
}
