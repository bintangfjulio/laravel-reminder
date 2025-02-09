<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;

class Login extends Component
{
    #[Validate('required|email')]
    public $email = '';

    #[Validate('required')]
    public $password = '';

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required'
    ];

    protected $messages = [
        'email.required' => 'Email wajib diisi!',
        'email.email' => 'Format email tidak valid!',
        'password.required' => 'Password wajib diisi!',
    ];

    public function login()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            $this->js('Swal.fire({icon: "success", title: "Berhasil", text: "Login berhasil."})');
            session()->regenerate();
            return $this->redirect("/lend", navigate: true);
        }

        $this->js('Swal.fire({icon: "error", title: "Gagal", text: "Login gagal."})');
    }

    public function render()
    {
        return view('livewire.login')->layout('components.layouts.auth')->title('Masuk');
    }
}
