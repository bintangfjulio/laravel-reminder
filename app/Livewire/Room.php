<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use Livewire\Attributes\Validate;

use App\Models\Room as RoomModel;

class Room extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $search = '';

    #[Validate('required|min:3')]
    public $name = '';

    public $id = null;
    public $room = null;

    public $isModalOpen = false;
    public $modalTitle = '';
    public $modalAction = '';

    protected $messages = [
        'name.required' => 'Nama wajib diisi!',
        'name.min' => 'Nama harus memiliki minimal 3 karakter!',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal($action, $id = null)
    {
        $this->resetModal();
        $this->modalAction = $action;
        $this->modalTitle = ucfirst($action) . ' Room';

        if (in_array($action, ['edit', 'lihat', 'hapus']) && $id) {
            $this->room = RoomModel::find($id);
            $this->name = $this->room->name;
        }

        $this->isModalOpen = true;
    }

    public function resetModal()
    {
        $this->resetValidation();
        $this->reset(['isModalOpen', 'modalTitle', 'modalAction', 'room', 'name']);
    }

    public function saveData()
    {
        $this->validate();

        RoomModel::updateOrCreate(
            ['id' => $this->modalAction === 'edit' ? $this->room->id : null],
            [
                'name' => $this->name,
            ]
        );

        $this->resetModal();

        $this->js('Swal.fire({icon: "success", title: "Berhasil", text: "Room saved."})');
    }

    public function delete()
    {
        $this->room->delete();
        $this->resetModal();

        $this->js('Swal.fire({icon: "success", title: "Berhasil", text: "Room deleted."})');
    }

    public function render()
    {
        $rooms = RoomModel::query()
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($this->search) . '%']);
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.room', compact('rooms'))->layout('components.layouts.app')->title('Room');
    }
}
