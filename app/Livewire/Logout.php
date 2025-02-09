<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Logout extends Component
{
    public function logout()
    {
        Auth::logout();

        session()->invalidate();
        session()->regenerateToken();

        $this->js('Swal.fire({
            icon: "success",
            title: "Berhasil",
            text: "Logout berhasil."
        })');

        return $this->redirect('/login', navigate: true);
    }

    public function render()
    {
        return view('livewire.logout');
    }
}
