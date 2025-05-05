<?php

namespace App\Livewire\Roles;

use Illuminate\View\View;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class RoleShow extends Component
{
    public $role;

    public function mount(Role $role): void
    {
        $this->role = $role;
    }

    public function render(): View
    {
        return view('livewire.roles.role-show');
    }
}
