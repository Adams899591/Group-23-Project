<?php

namespace App\Livewire;

use App\Models\Application;
use App\Models\AcademicSession;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.dashboard')->layout("layouts.app");
    }
}
