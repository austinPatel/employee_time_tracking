<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Project;
use App\Models\TimeLog;
use Filament\Forms\Form;
use App\Models\Department;
use App\Models\SubProject;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Livewire\GlobalSearch;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Repository\TimeLogRepository;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Filament\Resources\TimeLogResource\Pages;
// use Illuminate\Database\Eloquent\SoftDeletingScope;
// use App\Filament\Resources\TimeLogResource\RelationManagers;

class TimeLogResource extends Resource
{
    protected static ?string $model = TimeLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static $departments, $projects, $subprojects;
    public $department_id, $project_id, $subproject_id, $date, $start_time, $end_time;
    public $timeLogId = null;
    public $user_id;
 
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label('Employee')
                    ->options(self::getUserOptions()),
                    // ->disabled(Auth::user()->role === 'employee'),
    
                Select::make('department_id')
                    ->label('Department')
                    ->options(Department::all()->pluck('name', 'id'))
                    ->reactive()
                    ->afterStateUpdated(fn ($state) => self::updateProjects($state)),
    
                Select::make('project_id')
                    ->label('Project')
                    ->options(fn () => self::$projects ?? [])
                    ->reactive()
                    ->afterStateUpdated(fn ($state) => self::updateSubprojects($state)),
    
                    Select::make('subproject_id')
                    ->label('Subproject')
                    ->options(fn () => self::$subprojects ?? []),
        
                DatePicker::make('date')->label('Date'),
                TimePicker::make('start_time')->label('Start Time'),
                TimePicker::make('end_time')->label('End Time'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('Employee'),
                TextColumn::make('subproject.project.department.name')->label('Department'),
                TextColumn::make('subproject.project.name')->label('Project'),
                TextColumn::make('subproject.name')->label('Subproject'),
                TextColumn::make('date')->label('Date'),
                TextColumn::make('start_time')->label('Start Time'),
                TextColumn::make('end_time')->label('End Time'),
                TextColumn::make('total_hours')->label('Total Hours'),
            ])
            ->filters([
                // Add a custom text filter for searching
                Filter::make('search')
                    ->label('Search')
                    ->form([
                        TextInput::make('query')
                            ->placeholder('Search by Employee, Department, Project, Subproject...')
                            ->reactive(),
                        DatePicker::make('start_date')->label('Start Date'),
                        DatePicker::make('end_date')->label('End Date'),        
                    ])
                    ->query(function (Builder $query, array $data) {
                        
                        if (!empty($data['query']) || (!empty($data['start_date']) && !empty($data['end_date']))) {
                            $repository = App::make(TimeLogRepository::class);
                            // Use the repository's filterLogs method to modify the query
                            $filteredQuery = $repository->filterTimeLogs($data);// return the eloquent builder query
                            $query->mergeConstraintsFrom($filteredQuery); //merging from the eloquent query into filament query builder
                        }
                    }),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTimeLogs::route('/'),
            'create' => Pages\CreateTimeLog::route('/create'),
            'edit' => Pages\EditTimeLog::route('/{record}/edit'),
        ];
    }
    public static function getUserOptions() : array
    {
        if (Auth::user()->role === 'employee') {
            return [Auth::id() => Auth::user()->name];
        }

        return User::where('role', 'employee')->pluck('name', 'id')->toArray();
    }
    public static function updateProjects($departmentId)
    {
        self::$projects= self::getProjects($departmentId);
    }

    public static function updateSubprojects($projectId)
    {
        self::$subprojects = self::getSubProjects($projectId);
        // dd(self::$subprojects);
    }
    public static function getProjects($departmentId)
    {
        return Project::where('department_id', $departmentId)->pluck('name', 'id');
    }

    public static function getSubProjects($projectId)
    {
        // dd(SubProject::where('project_id', $projectId)->pluck('name', 'id'));
        return SubProject::where('project_id', $projectId)->pluck('name', 'id');
    }

}
