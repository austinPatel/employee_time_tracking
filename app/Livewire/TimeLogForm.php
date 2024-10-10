<?php

namespace App\Livewire;

use Filament\Forms;
use App\Models\User;
use App\Models\Project;
use App\Models\TimeLog;
use Livewire\Component;
use App\Models\Department;
use App\Models\SubProject;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Concerns\InteractsWithForms;

class TimeLogForm extends Component implements HasForms
{
    use InteractsWithForms;

    public $departments, $projects, $subprojects;
    public $department_id, $project_id, $subproject_id, $date, $start_time, $end_time;
    public $timeLogId = null;
    public $user_id;

    public function mount($timeLogId = null)
    {
        $this->departments = Department::all()->pluck('name', 'id');
        $this->user_id = Auth::id();
        
        if ($timeLogId) {
            $this->getTimeLog($timeLogId);
        }
    }

    protected function getFormSchema(): array
    {
        return [
            Select::make('user_id')
                ->label('Employee')
                ->options($this->getUserOptions())
                ->disabled(Auth::user()->role === 'employee'),

            Select::make('department_id')
                ->label('Department')
                ->options($this->departments)
                ->reactive()
                ->afterStateUpdated(fn ($state) => $this->updateProjects($state)),

            Select::make('project_id')
                ->label('Project')
                ->options(fn () => $this->projects ?? [])
                ->reactive()
                ->afterStateUpdated(fn ($state) => $this->updateSubprojects($state)),

            Select::make('subproject_id')
                ->label('Subproject')
                ->options(fn () => $this->subprojects ?? []),

            DatePicker::make('date')->label('Date'),
            TimePicker::make('start_time')->label('Start Time'),
            TimePicker::make('end_time')->label('End Time'),
        ];
    }

    protected function getUserOptions()
    {
        if (Auth::user()->role === 'employee') {
            return [Auth::id() => Auth::user()->name];
        }

        return User::where('role', 'employee')->pluck('name', 'id');
    }

    public function getTimeLog($timeLogId)
    {
        $timeLog = TimeLog::with('subproject.project.department')->findOrFail($timeLogId);
        $this->fillTimeLogData($timeLog);
    }

    protected function fillTimeLogData($timeLog)
    {
        $this->department_id = $timeLog->subproject->project->department_id;
        $this->project_id = $timeLog->subproject->project_id;
        $this->subproject_id = $timeLog->subproject_id;
        $this->projects = $this->getProjects($this->department_id);
        $this->subprojects = $this->getSubProjects($this->project_id);

        $this->user_id = $timeLog->user_id;
        $this->date = $timeLog->date;
        $this->start_time = $timeLog->start_time;
        $this->end_time = $timeLog->end_time;
        
        $this->form->fill([
            'user_id' => $this->user_id,
            'department_id' => $this->department_id,
            'project_id' => $this->project_id,
            'subproject_id' => $this->subproject_id,
            'date' => $this->date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
        ]);
    }

    public function save()
    {
        $total_hours = (strtotime($this->end_time) - strtotime($this->start_time)) / 3600;
        $timeLogData = [
            'user_id' => $this->user_id,
            'subproject_id' => $this->subproject_id,
            'date' => $this->date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'total_hours' => $total_hours,
        ];

        if ($this->timeLogId) {
            $timeLog = TimeLog::findOrFail($this->timeLogId);
            $timeLog->update($timeLogData);
        } else {
            TimeLog::create($timeLogData);
        }

        session()->flash('message', $this->timeLogId ? 'Time log updated successfully.' : 'Time log created successfully.');

        $this->timeLogId ? $this->resetForm() : $this->reset();
    }

    public function updateProjects($departmentId)
    {
        $this->projects = $this->getProjects($departmentId);
        $this->resetSelections();
    }

    public function updateSubprojects($projectId)
    {
        $this->subprojects = $this->getSubProjects($projectId);
        $this->subproject_id = null;
    }

    protected function resetSelections()
    {
        $this->project_id = null;
        $this->subprojects = [];
        $this->subproject_id = null;
    }

    protected function getProjects($departmentId)
    {
        return Project::where('department_id', $departmentId)->pluck('name', 'id');
    }

    protected function getSubProjects($projectId)
    {
        return SubProject::where('project_id', $projectId)->pluck('name', 'id');
    }

    public function resetForm()
    {
        $this->timeLogId = null;
        $this->reset();
    }

    public function render()
    {
        return view('livewire.time-log-form', [
            'projects' => $this->projects,
            'subprojects' => $this->subprojects,
        ]);
    }
}
