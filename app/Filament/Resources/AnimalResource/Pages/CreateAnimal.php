<?php

namespace App\Filament\Resources\AnimalResource\Pages;

use App\Filament\Resources\AnimalResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Animal;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CreateAnimal extends CreateRecord
{
    protected static string $resource = AnimalResource::class;

}
