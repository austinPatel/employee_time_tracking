<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Project;
use App\Models\TimeLog;
use Livewire\Component;
use App\Models\Department;
use App\Models\Subproject;
use Illuminate\Support\Carbon;
use App\Http\Services\ExportServices;
use App\Http\Controllers\ExportController;
use App\Http\Repository\TimeLogRepository;

class ManageTimeLogs extends Component
{
    public $filters = [
        'user_id' => null,
        'department_id' => null,
        'project_id' => null,
        'subproject_id' => null,
        'start_date' => null,
        'end_date' => null,
    ];

    public $employees = [];
    public $departments = [];
    public $projects = [];
    public $subprojects = [];
    public $timeLogs = [];
    public $editLogId = null;
    public $exportServices;
    
    public function mount()
    {
        // Populate dropdowns
        $this->employees = User::where('role', 'Employee')->pluck('name', 'id');
        $this->departments = Department::all()->pluck('name', 'id');
    }

    public function updatedFilters($propertyName)
    {
        if ($propertyName == 'filters.department_id') {
            $this->projects = Project::where('department_id', $this->filters['department_id'])->pluck('name', 'id');
        }
        if ($propertyName == 'filters.project_id') {
            $this->subprojects = Subproject::where('project_id', $this->filters['project_id'])->pluck('name', 'id');
        }
    }

    public function editTimeLog($timeLogId)
    {
        
        $this->editLogId = $timeLogId;
    }

    public function exportLogs(TimeLogRepository $timeLogRepository){
        return redirect()->action([ExportController::class, 'exportToCsv']);
    }

    public function filterLogs()
    {
        $query = TimeLog::query();

        if ($this->filters['user_id']) {
            $query->where('user_id', $this->filters['user_id']);
        }

        if ($this->filters['department_id']) {
            $query->whereHas('subproject.project', function ($q) {
                $q->where('department_id', $this->filters['department_id']);
            });
        }

        if ($this->filters['project_id']) {
            $query->whereHas('subproject', function ($q) {
                $q->where('project_id', $this->filters['project_id']);
            });
        }

        if ($this->filters['subproject_id']) {
            $query->where('subproject_id', $this->filters['subproject_id']);
        }

        if ($this->filters['start_date']) {
            $query->where('date', '>=', Carbon::parse($this->filters['start_date']));
        }

        if ($this->filters['end_date']) {
            $query->where('date', '<=', Carbon::parse($this->filters['end_date']));
        }
        $this->timeLogs = $query->with(['subproject.project', 'subproject.project.department', 'user'])->get();
       // return view('livewire.manage-time-logs');
    }

    public function render()
    {
       // return view('livewire.manage-time-logs');
        $this->timeLogs = TimeLog::with(['user', 'subproject.project.department'])
        ->when($this->filters['user_id'], fn($query) => $query->where('user_id', $this->filters['user_id']))
        ->when($this->filters['department_id'], fn($query) => $query->whereHas('subproject.project.department', fn($q) => $q->where('id', $this->filters['department_id'])))
        ->get();
        return view('livewire.manage-time-logs');

    }
    /**
     * Delete Timelog
     * 
     */
    public function deleteTimeLog($id)
    {
        TimeLog::find($id)->delete();
        $this->filterLogs(); // reset the time log list
        session()->flash('success', 'Time log successfully deleted!');
    }

}

