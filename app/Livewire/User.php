<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Hash;

use App\Models\User as UserModel;

class User extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $search = '';

    #[Validate]
    public $name = '';
    #[Validate]
    public $email = '';
    #[Validate]
    public $password = '';
    #[Validate]
    public $confirmPassword = '';
    #[Validate]
    public $role = '';

    public $id = null;
    public $user = null;

    public $isModalOpen = false;
    public $modalTitle = '';
    public $modalAction = '';

    protected function rules()
    {
        return [
            'name' => 'required|min:3',
            'email' => $this->modalAction === 'edit'
                ? "required|email|unique:users,email,{$this->user->id}"
                : "required|email|unique:users,email",
            'password' => $this->modalAction === 'edit'
                ? "nullable|min:8"
                : "required|min:8",
            'confirmPassword' => $this->modalAction === 'edit'
                ? ($this->password ? 'nullable|same:password' : 'nullable')
                : 'required|same:password',
            'role' => 'required,in:user,admin',
        ];
    }

    protected $messages = [
        'name.required' => 'Nama wajib diisi!',
        'name.min' => 'Nama harus memiliki minimal 3 karakter!',
        'email.required' => 'Email wajib diisi!',
        'email.email' => 'Format email tidak valid!',
        'email.unique' => 'Email sudah digunakan, coba yang lain!',
        'password.required' => 'Password wajib diisi!',
        'password.min' => 'Password harus minimal 8 karakter!',
        'password.confirmed' => 'Konfirmasi password tidak cocok!',
        'confirmPassword.required' => 'Konfirmasi password wajib diisi!',
        'confirmPassword.same' => 'Konfirmasi password harus sama dengan password!',
        'role.required' => 'Role wajib diisi!'
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal($action, $id = null)
    {
        $this->resetModal();
        $this->modalAction = $action;
        $this->modalTitle = ucfirst($action) . ' User';

        if (in_array($action, ['edit', 'lihat', 'hapus']) && $id) {
            $this->user = UserModel::find($id);
            $this->name = $this->user->name;
            $this->email = $this->user->email;
            $this->role = $this->user->role;
        }

        $this->isModalOpen = true;
    }

    public function resetModal()
    {
        $this->resetValidation();
        $this->reset(['isModalOpen', 'modalTitle', 'modalAction', 'user', 'name', 'email', 'password', 'role', 'confirmPassword']);
    }

    public function saveData()
    {
        $this->validate();

        UserModel::updateOrCreate(
            ['id' => $this->modalAction === 'edit' ? $this->user->id : null],
            [
                'name' => $this->name,
                'email' => $this->email,
                'password' => $this->password ? Hash::make($this->password) : $this->user->password,
                'role' => $this->role
            ]
        );

        $this->resetModal();

        $this->js('Swal.fire({icon: "success", title: "Berhasil", text: "User saved."})');
    }

    public function delete()
    {
        $this->user->delete();
        $this->resetModal();

        $this->js('Swal.fire({icon: "success", title: "Berhasil", text: "User deleted."})');
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
            ->paginate(10);

        return view('livewire.user', compact('users'))->layout('components.layouts.app')->title('User');
    }
}
