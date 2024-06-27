<?php

namespace App\Filament\Resources\RazeResource\Pages;

use App\Filament\Resources\RazeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRaze extends EditRecord
{
    protected static string $resource = RazeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
