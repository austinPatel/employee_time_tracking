<?php

namespace App\Filament\Resources\SubProjectResource\Pages;

use App\Filament\Resources\SubProjectResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubProject extends EditRecord
{
    protected static string $resource = SubProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
