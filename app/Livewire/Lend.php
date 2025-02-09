<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Models\Lend as LendModel;
use App\Models\Room;

class Lend extends Component
{
    use WithPagination, WithoutUrlPagination;

    #[Validate]
    public $room_id = '';
    #[Validate]
    public $lend_start = '';
    #[Validate]
    public $lend_end = '';
    public $status = '';

    public $id = null;
    public $lend = null;

    public $isModalOpen = false;
    public $modalTitle = '';
    public $modalAction = '';

    public $filter_start = '';
    public $filter_end = '';

    public function rules()
    {
        return [
            'room_id' => 'required',
            'lend_start' => [
                'required',
                'date',
                'after_or_equal:' . Carbon::now()->format('Y-m-d H:i:s')
            ],
            'lend_end' => [
                'required',
                'date',
                'after:lend_start'
            ],
            'status' => 'in:1,2,3'
        ];
    }

    protected $messages = [
        'room_id.required' => 'Ruangan wajib diisi!',
        'status.in' => 'Status tidak valid!',
        'lend_start.required' => 'Waktu mulai wajib diisi!',
        'lend_start.after_or_equal' => 'Waktu mulai tidak boleh kurang dari hari ini!',
        'lend_start.date' => 'Format waktu mulai tidak valid!',
        'lend_end.required' => 'Waktu akhir wajib diisi!',
        'lend_end.after' => 'Waktu akhir harus lebih besar dari Waktu mulai!',
        'lend_end.date' => 'Format waktu akhir tidak valid!'
    ];

    public function openModal($action, $id = null)
    {
        $this->resetModal();
        $this->modalAction = $action;
        $this->modalTitle = ucfirst($action) . ' Lend';

        if (in_array($action, ['edit', 'lihat', 'hapus']) && $id) {
            $this->lend = LendModel::find($id);
            $this->room_id = $this->lend->room_id;
            $this->status = $this->lend->status;
            $this->lend_start = $this->lend->lend_start;
            $this->lend_end = $this->lend->lend_end;
        }

        $this->isModalOpen = true;
    }

    public function resetModal()
    {
        $this->resetValidation();
        $this->reset(['isModalOpen', 'modalTitle', 'modalAction', 'lend', 'room_id', 'status', 'lend_start', 'lend_end']);
    }

    public function saveData()
    {
        $this->validate();

        $data = [
            'room_id' => $this->room_id,
            'lend_start' => $this->lend_start,
            'lend_end' => $this->lend_end
        ];

        if ($this->modalAction !== 'edit') {
            $data['user_id'] = Auth::user()->id;
            $data['status'] = 1;
        } else {
            $data['status'] = Auth::user()->role == 'user' ? 1 : $this->status;
        }

        LendModel::updateOrCreate(
            ['id' => $this->modalAction === 'edit' ? $this->lend->id : null],
            $data
        );

        $this->resetModal();

        $this->js('Swal.fire({icon: "success", title: "Berhasil", text: "Lend saved."})');
    }

    public function delete()
    {
        $this->lend->delete();
        $this->resetModal();

        $this->js('Swal.fire({icon: "success", title: "Berhasil", text: "Lend deleted."})');
    }

    public function resetFilters()
    {
        $this->reset(['filter_start', 'filter_end']);
    }

    public function render()
    {
        $lends = LendModel::query()
            ->with(['user', 'room'])
            ->where(function ($query) {
                if (Auth::user()->role == 'user') {
                    $query->where('user_id', Auth::user()->id);
                }
            })
            ->when($this->filter_start, function ($query) {
                $query->where('lend_start', '>=', $this->filter_start);
            })
            ->when($this->filter_end, function ($query) {
                $query->where('lend_end', '<=', $this->filter_end);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $rooms = Room::orderBy('name', 'asc')
            ->get();

        return view('livewire.lend', compact('lends', 'rooms'))->layout('components.layouts.app')->title('Lend');
    }
}
