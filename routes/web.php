<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Livewire\Login;
use App\Livewire\Register;
use App\Livewire\Room;
use App\Livewire\Lend;
use App\Livewire\User;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('lend');
    }

    return redirect()->route('login');
});

Route::get('/login', Login::class)->name('login');
Route::get('/register', Register::class)->name('register');

Route::middleware([
    'auth'
])->group(function () {
    Route::middleware('role:admin')->group(function () {
        Route::get('/room', Room::class)->name('room');
        Route::get('/user', User::class)->name('user');
    });

    Route::get('/lend', Lend::class)->name('lend');
});
