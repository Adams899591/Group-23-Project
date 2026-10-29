<?php

namespace App\Livewire;

use Livewire\Component;

class Applicants extends Component
{
    public function render()
    {
        return view('livewire.applicants')->layout("layouts.app");
    }
}
