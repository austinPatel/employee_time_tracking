<?php

namespace App\Livewire;

use App\Models\TimeLog;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TimeLogs extends Component
{
    /**
     * Employee time logs 
     */
    public function render()
    {

        // return view('livewire.time-logs');
        $timeLogs = TimeLog::with('subproject.project.department')
        ->where('user_id', Auth::id())
        ->get();
        
        return view('livewire.time-logs', compact('timeLogs'));

    }
}
