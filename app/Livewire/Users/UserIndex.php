<?php

namespace App\Livewire\Users;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;

class UserIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    protected $paginationTheme = 'tailwind';

    public function updatedSearch()
    {

    $this->resetPage();
    }
    public function render()
    {
        $users = User::query()
            ->when($this->search, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('name', 'like', '%' . trim($this->search) . '%')
                    ->orWhere('dui', 'like', '%' . trim($this->search) . '%');
                });
            })
            ->paginate($this->perPage)->onEachSide(1);

        return view('livewire.users.user-index', compact('users'));
    }

    public function toggleStatus(User $user)
    {
        $user->update(['status' => !$user->status]);
        $status = $user->status ? 'activado' : 'desactivado';
        session()->flash('success', "Usuario {$status} exitosamente.");
    }

        public function deleteUser(User $user)
    {
        $user->delete();

        session()->flash('success', 'User deleted successfully.');
    }
}

