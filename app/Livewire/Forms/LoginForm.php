<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;
use Illuminate\Support\Facades\Auth;

class LoginForm extends Form
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

    public function auth()
    {
        $this->validate();

        if (Auth::attempt($this->all())) {
            return true;
        } else {
            return false;
        }
    }
}
