<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Project;
use Filament\Forms\Form;
use App\Models\Department;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProjectResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProjectResource\RelationManagers;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('department_id')
                ->label('Department')
                ->options(Department::all()->pluck('name', 'id'))
                ->required(),
                
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID'),
                TextColumn::make('department.name')->label('Department'),
                TextColumn::make('name')->label('Project Name'),
                TextColumn::make('created_at')->label('Created At')->dateTime(),
                TextColumn::make('updated_at')->label('Updated At')->dateTime(),
            ])
            ->filters([
                Filter::make('department')
                    ->query(fn ($query) => $query->whereHas('department')),
            ]);
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
