<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Project;
use App\Models\TimeLog;
use Livewire\Component;
use App\Models\Department;
use App\Models\Subproject;
use App\Http\Controllers\ExportController;
use App\Http\Repository\TimeLogRepository;

class ManageTimeLogs extends Component
{
    public $filters = [
        'employee_id' => null,
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
        $this->employees = User::where('role', 'employee')->pluck('name', 'id');
        $this->departments = Department::all()->pluck('name', 'id');
        $this->projects = Project::all()->pluck('name','id');
        $this->subprojects = Subproject::all()->pluck('name','id');
    }

   
    public function updated($propertyName)
    {
        if ($propertyName === 'filters.department_id') {
            $this->afterStateUpdated('department_id');
        }
    
        if ($propertyName === 'filters.project_id') {
            $this->afterStateUpdated('project_id');
        }
    }
    
    public function afterStateUpdated($filterKey)
    {
        if ($filterKey === 'department_id') {
            // Update project list when department changes
            $this->filters['project_id'] = null; // Reset project and subproject when department changes
            $this->filters['subproject_id'] = null;
            $this->projects = Project::where('department_id', $this->filters['department_id'])->pluck('name', 'id');
            $this->subprojects = []; // Reset subprojects when department changes
        }

        if ($filterKey === 'project_id') {
            // Update subproject list when project changes
            $this->filters['subproject_id'] = null; // Reset subproject when project changes
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

        if ($this->filters['employee_id']) {
            $query->where('user_id', $this->filters['employee_id']);
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
            $query->where('date', '>=', Carbon::parse($this->filters['start_date']))->orderBy('date','desc');
        }

        if ($this->filters['end_date']) {
            $query->where('date', '<=', Carbon::parse($this->filters['end_date']))->orderBy('date','desc');
            
        }

        $this->timeLogs = $query->with(['subproject.project', 'subproject.project.department', 'user'])
        ->orderBy('created_at','desc')
        ->get();
    }

    public function render()
    {
        $this->filterLogs();
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

