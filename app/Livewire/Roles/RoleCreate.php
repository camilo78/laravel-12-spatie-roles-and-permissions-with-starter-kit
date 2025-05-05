<?php

namespace App\Livewire\Roles;

use Illuminate\View\View;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleCreate extends Component
{
    public $name;
    public $permissions = [];
    public $allPermissions = [];

    public function mount()
    {
        $this->allPermissions = Permission::latest()->get();
    }

    public function render(): View
    {
        return view('livewire.roles.role-create');
    }

    public function createRole()
    {
        $this->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'required',
        ]);

        $role = Role::create([
            'name' => $this->name,
        ]);

        $role->syncPermissions($this->permissions);

        return to_route('roles.index')->with('success', 'Role created successfully.');
    }
}
