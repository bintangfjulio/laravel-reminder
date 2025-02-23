<?php

namespace App\Livewire;

use Livewire\Component;
use App\Livewire\Forms\LoginForm;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.auth')]
#[Title('Masuk')]
class Login extends Component
{
    public LoginForm $form;

    public function login()
    {
        if ($this->form->auth()) {
            session()->regenerate();
            $this->js('Swal.fire({icon: "success", title: "Berhasil", text: "Login berhasil."})');

            return $this->redirect("/lend", navigate: true);
        }

        $this->js('Swal.fire({icon: "error", title: "Gagal", text: "Login gagal."})');
    }

    public function render()
    {
        return view('livewire.login');
    }
}
