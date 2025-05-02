<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\View\View;
use Livewire\Component;

class UserShow extends Component
{
    public $user;

    public function mount(User $user): void
    {
        $this->user = $user;
    }
    public function render(): View
    {
        return view('livewire.users.user-show');
    }
}
