<?php

namespace App\Livewire;

use Livewire\Component;

class LoadingSpinner extends Component
{

    public function render()
    {
        return <<<'blade'
            <div>
                <div class="overlay"></div>
                <div class="spinner-container">
                    <div class="spinner"></div>
                    <div class="logo"></div>
                </div>
            </div>
        blade;
    }
}
