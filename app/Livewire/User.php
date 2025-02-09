<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User as UserModel;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;

class User extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function resetSearch()
    {
        $this->reset(['search']);
    }

    public function render()
    {
        $users = UserModel::query()
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($this->search) . '%']);
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('livewire.user', compact('users'))->layout('components.layouts.app')->title('User');;
    }
}
