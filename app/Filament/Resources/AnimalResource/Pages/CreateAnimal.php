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
    protected function handleRecordCreation(array $data): Animal
    {

        $animal = Animal::create([
            'category' => $data['category'],
            'rfid' => $data['rfid'],
            'weight' => $data['weight'],
            'gd' => $data['gd'],
            'AoB' => $data['AoB'],
            'gim' => $data['gim'],
        ]);

        if (isset($data['images']) && is_array($data['images'])) {
            foreach ($data['images'] as $image) {

                $filePath = Storage::disk('public')->put('uploads/animals', $image);
                // Añadir el archivo desde el almacenamiento temporal a la colección de medios
                $path = 'app\public\\' . $filePath;
                $animal->addMedia(storage_path($path))->toMediaCollection('images');
            }
        }

        return $animal;
    }
}
