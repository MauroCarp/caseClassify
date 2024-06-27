<?php

namespace App\Http\Controllers;

use App\Filament\Resources\AnimalResource;
use App\Models\Folder;
use App\Models\Animal;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    //

    public function folderRecords(Folder $folder){   
        
        $idFolder = $_GET['folder'];
        $animals = Animal::where(['idFolder'=>$idFolder,'sold'=>1])->get();
        $folder = Folder::find($idFolder);

        foreach ($animals as $key => $value) {
          
            $grado = AnimalResource::getGrade($value['gim'],'grade');
            $name = AnimalResource::getGrade($value['gim'],'name');
            
            $animals[$key]['grade'] = $grado . ' - ' . $name;

        }

        $pdf = Pdf::loadView('pdf.inform',['animals'=>$animals->toArray()]);
        return $pdf->stream($folder->name);

    }


    // public static function getGrade($gim, $type): string
    // {
        
    //     switch ($gim) {
    //         case $gim > 6.5:
    //             $grade = 6;
    //             $name = 'Abundante';
    //             break;
    //         case ($gim > 5 && $gim <= 6.5):
    //             $grade = 5;
    //             $name = 'Moderado';
    //             break;
            
    //         case ($gim > 4 && $gim <= 5):
    //             $grade = 4;
    //             $name = 'Modesto';
    //             break;
            
    //         case ($gim > 3 && $gim <= 4):
    //             $grade = 3;
    //             $name = 'Escaso';
    //             break;
            
    //         case ($gim > 1.8 && $gim <= 3):
    //             $grade = 2;
    //             $name = 'Muy Escaso';
    //             break;

    //         case ($gim > 0.5 && $gim <= 1.8):
    //             $grade = 1;
    //             $name = 'Trazas';
    //             break;

    //         case ($gim <= 0.5):
    //             $grade = 0;
    //             $name = 'Sin Grasa';
    //             break;
            
    //         default:
    //             # code...
    //             break;
    //     }

    //     if($type == 'grade'){
    //         return $grade;
    //     } else {
    //         return $name;
    //     }

    // }
}
