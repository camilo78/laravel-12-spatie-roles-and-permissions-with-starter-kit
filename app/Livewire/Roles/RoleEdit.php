<?php

namespace App\Livewire\Roles;

use Illuminate\View\View;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleEdit extends Component
{
    public $name, $role;
    public $permissions = [];
    public $allPermissions = [];

    public function mount(Role $role)
    {
        $this->role = $role;
        $this->allPermissions = Permission::latest()->get();
        $this->name = $this->role->name;
        $this->permissions = $this->role->permissions->pluck('name')->toArray();
    }

    public function render(): View
    {
        return view('livewire.roles.role-edit');
    }

    public function editRole()
    {
        $this->validate([
            'name' => 'required|unique:roles,name,' . $this->role->id,
            'permissions' => 'required',
        ]);

        $this->role->name = $this->name;

        $this->role->save();

        $this->role->syncPermissions($this->permissions);

        return to_route('roles.index')->with('success', 'Role updated successfully.');
    }
}
