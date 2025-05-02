<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\View\View;
use Livewire\Component;

class UserEdit extends Component
{
    public $user, $name, $email, $password, $confirm_password;

    public function mount(User $user): void
    {
        $this->user = $user;
        $this->name = $this->user->name;
        $this->email = $this->user->email;
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
        ]);

        $this->user->name = $this->name;
        $this->user->email = $this->email;

        if ($this->password) {
            $this->user->password = bcrypt($this->password);
        }

        $this->user->save();

        return to_route('users.index')->with('success', 'User updated successfully.');
    }
}
