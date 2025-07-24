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
                             ->orWhere('email', 'like', '%' . trim($this->search) . '%');
                });
            })
            ->with('roles')
            ->orderBy('name', 'asc')
            ->paginate($this->perPage);

        return view('livewire.users.user-index', compact('users'));
    }
}

