<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\View\View;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use App\Models\Department;
use App\Models\Municipality;
use App\Models\Locality;
use Illuminate\Support\Facades\Hash;

class UserEdit extends Component
{
    public User $user;
    public string $name = '', $email = '', $dui = '', $address = '', $gender = 'Masculino';
    public ?string $phone = null, $password = null, $confirm_password = null;
    public $allRoles, $departments = [], $municipalities = [], $localities = [];
    public array $roles = [];
    public ?int $department_id = null, $municipality_id = null, $locality_id = null;

    protected $listeners = ['refreshLocationData' => 'refreshLocations'];

    public function mount(User $user): void
    {
        $this->user = $user;
        $this->fill($user->only([
            'name', 'email', 'dui', 'phone', 'address', 'gender',
            'department_id', 'municipality_id', 'locality_id'
        ]));

        $this->loadInitialData();
    }

    protected function loadInitialData(): void
    {
        $this->allRoles = Role::latest()->get();
        $this->roles = $this->user->roles->pluck('name')->toArray();
        $this->departments = Department::orderBy('name')->get();
        $this->refreshLocations();
    }

    protected function refreshLocations(): void
    {
        $this->municipalities = $this->department_id
            ? Municipality::where('department_id', $this->department_id)->get()
            : collect();

        $this->localities = $this->municipality_id
            ? Locality::where('municipality_id', $this->municipality_id)->get()
            : collect();
    }

    public function updatedDepartmentId($value): void
    {
        $this->reset(['municipality_id', 'locality_id']);
        $this->refreshLocations();
    }

    public function updatedMunicipalityId($value): void
    {
        $this->reset(['locality_id']);
        $this->refreshLocations();
    }


    public function editUser()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->user->id,
            'dui' => 'required|unique:users,dui,' . $this->user->id,
            'phone' => 'nullable|string|max:255',
            'address' => 'required|string|max:255',
            'gender' => 'required|in:Masculino,Femenino',
            'password' => 'nullable|string|min:8|confirmed',
            'confirm_password' => $this->password ? 'required|string|min:8' : 'nullable',
            'roles' => 'required|array|min:1',
            'department_id' => 'required|exists:departments,id',
            'municipality_id' => 'required|exists:municipalities,id',
            'locality_id' => 'required|exists:localities,id',
        ]);

        $this->user->update($this->prepareUpdateData());
        $this->user->syncRoles($this->roles);

        session()->flash('success', 'Usuario actualizado correctamente.');
        return redirect()->route('users.index');
    }

    protected function prepareUpdateData(): array
    {
        return array_filter([
            'name' => $this->name,
            'email' => $this->email,
            'dui' => $this->dui,
            'phone' => $this->phone,
            'address' => $this->address,
            'gender' => $this->gender,
            'department_id' => $this->department_id,
            'municipality_id' => $this->municipality_id,
            'locality_id' => $this->locality_id,
            'password' => $this->password ? Hash::make($this->password) : null
        ]);
    }

    public function render(): View
    {
        return view('livewire.users.user-edit');
    }
}
