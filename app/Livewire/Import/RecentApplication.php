<?php

namespace App\Livewire\Import;

use App\Models\Application;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class RecentApplication extends Component
{


    public function render()
    {

        $applications = Application::with(["applicant", "academicSession", "programme"])
        ->whereHas("academicSession", function($query) {
            $query->where("start_year", "2026");
        })->latest()->limit(5)->get();

        // Log::info(json_encode($application->toArray(), JSON_PRETTY_PRINT));

        return view('livewire.import.recent-application',["applications" => $applications]);
    }
}
