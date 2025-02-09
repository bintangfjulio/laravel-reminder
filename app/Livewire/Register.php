<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Validate;

class Register extends Component
{
    #[Validate('required|min:3')]
    public $name = '';

    #[Validate('required|email|unique:users')]
    public $email = '';

    #[Validate('required|min:8')]
    public $password = '';

    #[Validate('required|same:password')]
    public $confirmPassword = '';

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
    ];

    public function register()
    {
        $this->validate();

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => 'user'
        ]);

        $this->js('Swal.fire({icon: "success", title: "Berhasil", text: "Daftar berhasil."})');

        return $this->redirect('/login', navigate: true);
    }

    public function render()
    {
        return view('livewire.register')->layout('components.layouts.auth')->title('Daftar');
    }
}
