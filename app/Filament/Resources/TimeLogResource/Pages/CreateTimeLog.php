<?php

namespace App\Filament\Resources\TimeLogResource\Pages;

use App\Models\User;
use App\Models\Project;
use App\Models\TimeLog;
use App\Models\Department;
use App\Models\SubProject;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\TimeLogResource;

class CreateTimeLog extends CreateRecord
{
    protected static string $resource = TimeLogResource::class;

    protected function getFormSchema(): array
    {
        return [
            Select::make('user_id')
            ->label('Employee')
            ->options($this->getUserOptions())
            ->disabled(Auth::user()->role === 'employee'),

            Select::make('department_id')
                ->label('Department')
                ->options(Department::all()->pluck('name', 'id'))
                ->reactive()
                ->afterStateUpdated(fn ($state) => $this->form->fill([
                    'projects' => Project::where('department_id', $state)->pluck('name', 'id')
                ])),
                
            Select::make('project_id')
                ->label('Project')
                ->options(fn () => Project::where('department_id', $this->form->getState('department_id'))->pluck('name', 'id'))
                ->reactive()
                ->afterStateUpdated(fn ($state) => $this->form->fill([
                    'subprojects' => SubProject::where('project_id', $state)->pluck('name', 'id')
                ])),

            Select::make('subproject_id')
                ->label('Subproject')
                ->options(fn () => SubProject::where('project_id', $this->form->getState('project_id'))->pluck('name', 'id')),

            DatePicker::make('date')->label('Date')->required(),

            TimePicker::make('start_time')->label('Start Time')->required(),

            TimePicker::make('end_time')->label('End Time')->required(),
        ];
    }
    public static function getUserOptions()
    {
        if (Auth::user()->role === 'employee') {
            return [Auth::id() => Auth::user()->name];
        }

        return User::where('role', 'employee')->pluck('name', 'id');
    }
}
