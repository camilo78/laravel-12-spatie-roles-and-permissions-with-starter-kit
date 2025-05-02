<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\View\View;
use Livewire\Component;

class UserIndex extends Component
{
    public function render(): View
    {
        return view('livewire.users.user-index', [
            'users' => User::latest()->get(),
        ]);
    }

    public function deleteUser(User $user)
    {
        $user->delete();

        session()->flash('success', 'User deleted successfully.');
    }
}
