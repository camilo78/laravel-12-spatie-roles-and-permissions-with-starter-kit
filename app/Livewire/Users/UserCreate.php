<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\View\View;
use Livewire\Component;

class UserCreate extends Component
{
    public $name, $email, $password, $confirm_password;

    public function render(): View
    {
        return view('livewire.users.user-create');
    }

    public function createUser()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|same:confirm_password',
        ]);

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password),
        ]);

        return to_route('users.index')->with('success', 'User created successfully.');
    }
}
