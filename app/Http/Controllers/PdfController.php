<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    //

    public function folderRecords(Folder $folder){

        $pdf = Pdf::loadView('pdf.example');
        return $pdf->download();

    }
}
