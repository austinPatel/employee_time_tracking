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
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Concerns\InteractsWithForms;

class TimeLogForm extends Component implements HasForms
{

    use InteractsWithForms;

    public $departments, $subprojects;
    public $department_id, $project_id, $subproject_id, $date, $start_time, $end_time;
    public $projects = [];
    public $timeLogId = null; // if we are editing or creating
    public $user_id;


    public function mount($timeLogId = null)
    {
        if ($timeLogId) {
            // dd("getTimeLog");
            $this->getTimeLog($timeLogId);
        }
    }

    protected function getFormSchema(): array
    {
        return [
            Select::make('user_id')
                ->label('Employee')
                ->options(User::where('role', 'employee')->pluck('name', 'id')),

            Select::make('department_id')
                ->label('Department')
                ->options(Department::all()->pluck('name', 'id'))
                ->reactive()
                ->afterStateUpdated(fn ($state) =>  $this->updateProjects($state)),

            Select::make('project_id')
            ->label('Project')
            ->options(fn () => optional($this->projects)->pluck('name', 'id') ?? [])
            ->reactive()
            ->afterStateUpdated(fn ($state) => $this->updateSubprojects($state)),

            Select::make('subproject_id')
            ->label('Subproject')
            ->options(fn () => optional($this->subprojects)->pluck('name', 'id') ?? []),


            DatePicker::make('date')->label('Date'),
            TimePicker::make('start_time')->label('Start Time'),
            TimePicker::make('end_time')->label('End Time'),
        ];
    

    }
/*
    public function submit(){
        $this->validate([
            'subproject_id' => 'required',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        
        $total_hours = (strtotime($this->end_time) - strtotime($this->start_time)) / 3600;
        TimeLog::create([
            'user_id' => Auth::id(),
            'subproject_id' => $this->subproject_id,
            'date' => $this->date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'total_hours' => $total_hours,
        ]);

        session()->flash('message', 'Time logged successfully!');
        $this->reset();
    }
*/

    public function render()
    {
        // return view('livewire.time-log-form');
        return view('livewire.time-log-form', [
            'departments' => Department::all(), // Load departments for the dropdown
            'projects' => $this->projects, // Pass the selected projects
            'subprojects' => $this->subprojects, // Pass the selected subprojects
        ]);

    }

    public function getTimeLog($timeLogId)
    {
        $timeLog = TimeLog::find($timeLogId);
        // Load the log data into the form
        // dd($timeLog);
        $this->department_id = $timeLog->subproject->project->department_id; // Set the department ID
        $this->project_id = $timeLog->subproject->project_id; // Set the project ID
        $this->subproject_id = $timeLog->subproject_id; // Set the subproject ID
    
        $this->projects = $this->getProjects($this->department_id);
        $this->subprojects = $this->getSubProjects($this->project_id);

        $this->timeLogId = $timeLog->id;


        $this->user_id = $timeLog->user_id;
        $this->date = $timeLog->date;
        $this->start_time = $timeLog->start_time;
        $this->end_time= $timeLog->end_time;
    
        $total_hours = (strtotime($timeLog->end_time) - strtotime($timeLog->start_time)) / 3600;
        $this->form->fill([
            'user_id' => $timeLog->user_id,
            'department_id' => $timeLog->subproject->project->department_id,
            'project_id' => $this->project_id,
            'subproject_id' => $this->subproject_id,
            'date' => $timeLog->date,
            'start_time' => $timeLog->start_time,
            'end_time' => $timeLog->end_time,
            'total_hours' => $total_hours,
        ]);
    }

    public function save()
    {
        // $this->validate();
        $total_hours = (strtotime($this->end_time) - strtotime($this->start_time)) / 3600;
        if ($this->timeLogId) {
            // Update the existing timelog
            $timeLog = TimeLog::findOrFail($this->timeLogId);
            $timeLog->user_id = $this->user_id;
            // $timeLog->department_id = $this->form->getState('department_id');
            // $timeLog->project_id = $this->form->getState('project_id');
            $timeLog->subproject_id = $this->subproject_id;
            $timeLog->date = $this->date;
            $timeLog->start_time = $this->start_time;
            $timeLog->end_time = $this->end_time;
            $timeLog->total_hours = $total_hours;
            $timeLog->save();

        } else {
            // Create a new timelog
                 TimeLog::create([
                    'user_id' => Auth::id(),
                    'subproject_id' => $this->subproject_id,
                    'date' => $this->date,
                    'start_time' => $this->start_time,
                    'end_time' => $this->end_time,
                    'total_hours' => $total_hours,
                ]);
        }

        session()->flash('message', $this->timeLogId ? 'Time log updated successfully.' : 'Time log created successfully.');
        // $this->reset();
        if($this->timeLogId){
            return redirect()->route('manage.time.logs');
        }
        $this->timeLogId ? $this->resetForm() : $this->reset();


    }

    public function resetForm()
    {
        $this->timeLogId = null;
    }
    /**
     * 
     * Update Projects List on Change the Department options
     */
    public function updateProjects($departmentId)
    {
        $this->projects = $this->getProjects($departmentId);
        $this->project_id = null; // Reset project selection
        $this->subprojects = []; // Clear subprojects when department changes
        $this->subproject_id = null; // Reset subproject selection
    }
    /**
     * 
     * Update SubProjects Options list on change the projects
     */
    public function updateSubprojects($projectId)
    {
        $this->subprojects = $this->getSubProjects($projectId);
        $this->subproject_id = null; // Reset subproject selection
    }

    /**
     * Get Projects By DepartmentId
     */
    public function getProjects($departmentId){
        return Project::where('department_id', $departmentId)->get();
    }
    /**
     * Get Sub Projects By Project ID
     */
    public function getSubProjects($projectId){
        return SubProject::where('project_id', $projectId)->get();
    }

}
