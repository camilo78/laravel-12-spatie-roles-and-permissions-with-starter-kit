<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\View\View;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class UserEdit extends Component
{
    public $user, $name, $email, $password, $confirm_password, $allRoles;
    public $roles = [];

    public function mount(User $user): void
    {
        $this->user = $user;
        $this->name = $this->user->name;
        $this->email = $this->user->email;
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
            'password' => 'same:confirm_password',
            'roles' => 'required',
        ]);

        $this->user->name = $this->name;
        $this->user->email = $this->email;

        if ($this->password) {
            $this->user->password = bcrypt($this->password);
        }

        $this->user->save();

        $this->user->syncRoles($this->roles);

        return to_route('users.index')->with('success', 'User updated successfully.');
    }
}
