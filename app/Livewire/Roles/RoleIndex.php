<?php

namespace App\Livewire\Roles;

use Illuminate\View\View;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class RoleIndex extends Component
{
    public function render(): View
    {
        return view('livewire.roles.role-index', [
            'roles' => Role::with('permissions')->latest()->get(),
        ]);
    }

    public function deleteRole(Role $role)
    {
        $role->delete();

        session()->flash('success', 'Role deleted successfully.');
    }
}
