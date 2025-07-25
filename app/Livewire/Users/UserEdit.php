<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\View\View;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class UserEdit extends Component
{
    public $user, $name, $email, $dui, $phone, $address, $gender, $password, $confirm_password, $allRoles;
    public $roles = [];

    public function mount(User $user): void
    {
        $this->user = $user;
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->dui = $this->user->dui;
        $this->phone = $this->user->phone;
        $this->address = $this->user->address;
        $this->gender = $this->user->gender;
        $this->allRoles = Role::latest()->get();
        $this->roles = $this->user->roles->pluck('name')->toArray();
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
            'dui' => 'nullable|string|max:13',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
            'gender' => 'required|in:Masculino,Femenino',
            'password' => 'same:confirm_password',
            'roles' => 'required',
        ]);

        $this->user->name = $this->name;
        $this->user->email = $this->email;
        $this->user->gender = $this->gender;

        if ($this->password) {
            $this->user->password = bcrypt($this->password);
        }

        $this->user->save();

        $this->user->syncRoles($this->roles);

        return to_route('users.index')->with('success', 'Usuario actualizado.');
    }
}
