<?php

namespace App\Filament\Resources\AnimalResource\Widgets;

use App\Filament\Resources\AnimalResource;
use Filament\Forms\Form;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Infolist;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Collection;
use Filament\Infolists\Components\Section as InfolistSection;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;


class AnimalFolderTable extends BaseWidget
{
    protected int|string|array $columnSpan = 'full'; // Ocupar todo el ancho disponible

    public string $idFolder;

    public function table(Table $table): Table
    {

        $engMeasures = array('libras'=>2.204,'inch'=>0.0393,'inch2'=>0.155,'kph%'=>3.3);

        return $table
            ->query(AnimalResource::getEloquentQuery()->where('idFolder',$this->idFolder))
            ->defaultPaginationPageOption(10)
            ->columns([
                Tables\Columns\TextColumn::make('category')
                    ->sortable()
                    ->searchable()
                    ->label('Categoria'),
                Tables\Columns\TextColumn::make('rfid')
                    ->sortable()
                    ->searchable()
                    ->label('RFID'),
                Tables\Columns\TextColumn::make('weight')
                    ->sortable()
                    ->searchable()
                    ->label('Peso'),
                Tables\Columns\TextColumn::make('gd')
                    ->searchable()
                    ->label('G.D'),
                Tables\Columns\TextColumn::make('AoB')
                    ->sortable()
                    ->searchable()
                    ->label('AoB'),
                Tables\Columns\TextColumn::make('case')
                    ->sortable()
                    ->searchable()
                    ->label('Carcasa')
                    ->getStateUsing(function ($record) {

                        $case = $record->weight * 0.59; 
                        return number_format($case, 0);

                    }),
                Tables\Columns\TextColumn::make('sold')
                    ->sortable()
                    ->searchable()
                    ->label('Estado')
                    ->badge()
                    ->colors([
                        'success' => fn ($state): bool => $state,
                        'danger' => fn ($state): bool => !$state,
                    ])
                    ->icons([
                        'heroicon-o-check' => fn ($state): bool => $state,
                        'heroicon-o-x-circle' => fn ($state): bool => !$state,
                    ])
                    ->getStateUsing(function ($record) { 

                        if($record->sold)
                            return 'Reservado';
                        else    
                            return 'Disponible';

                    }),
                    
                Tables\Columns\TextColumn::make('im')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable()
                    ->searchable()
                    ->label('I.M')
                    ->getStateUsing(function ($record) {

                        $indiceMuscularidad = ($record->AoB / $record->weight) * 100; 
                        return number_format($indiceMuscularidad, 0);

                    }),
                Tables\Columns\TextColumn::make('yg')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable()
                    ->searchable()
                    ->label('Yield Grade')
                    ->getStateUsing(function ($record) use ($engMeasures) {

                        $case = $record->weight * 0.59; 

                        $n1 = $record->gd * $engMeasures['inch'];
                        $n2 = $engMeasures['kph%'];
                        $n3 = $case * $engMeasures['libras'];
                        $n4 = $record->AoB * $engMeasures['inch2'];
            
                        $yieldGrade = 2.5+(2.5*$n1)+(0.2*$n2)+(0.0038*$n3)-(0.32*$n4); 

                        return number_format($yieldGrade, 1);

                    }),
                Tables\Columns\TextColumn::make('rc')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable()
                    ->searchable()
                    ->label('% R.C')
                    ->getStateUsing(function ($record) use ($engMeasures) {

                        $case = $record->weight * 0.59; 

                        $n1 = $record->gd * $engMeasures['inch'];
                        $n2 = $engMeasures['kph%'];
                        $n3 = $case * $engMeasures['libras'];
                        $n4 = $record->AoB * $engMeasures['inch2'];

                        $rc = 65.59-(9.93*$n1)-(1.29*$n2)+(1.23*$n4)-(0.013*$n3); 

                        return number_format($rc, 2);

                }),
                Tables\Columns\TextColumn::make('grade')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable()
                    ->searchable()
                    ->label('Grado')
                    ->getStateUsing(function ($record){

                        $grado = AnimalResource::getGrade($record->gim,'grade');
                        $name = AnimalResource::getGrade($record->gim,'name');
                        return $grado . ' - ' . $name;

                }),
                ImageColumn::make('gradeImage')
                ->toggleable(isToggledHiddenByDefault: true)
                ->getStateUsing(function ($record) {

                    $gim = $record->gim;
                    $name = AnimalResource::getGrade($gim,'name');
                    $name = strtolower(str_replace(' ','',$name));
                    $gradeImage = "marbling\/$name.png";

                    return $gradeImage;
                })
                ->label('Img Grado')
            ])
            ->actions([

                Tables\Actions\ViewAction::make()
                ->color('primary')
                ->slideOver()
                ->infolist([
                    InfolistSection::make()
                        ->schema([
                            Split::make([
                                Grid::make(3)
                                    ->schema([
                                        Group::make([
                                            TextEntry::make('category')
                                                ->label('Categoria')
                                                ->size('lg')
                                                ->weight('bold'),                            
                                            TextEntry::make('weight')
                                                ->label('Peso')
                                                ->size('lg')
                                                ->weight('bold')
                                                ->getStateUsing(function ($record) {
                        
                                                    return $record->weight . ' Kg';
                                                    
                                                }),                           
                                                TextEntry::make('AoB')
                                                ->label('AoB')
                                                ->size('lg')
                                                ->weight('bold')
                                                ->getStateUsing(function ($record) {
                        
                                                    return $record->AoB . ' cm²';
                                                    
                                                }),       
                                                TextEntry::make('im')
                                                ->label('I.M')
                                                ->size('lg')
                                                ->weight('bold')
                                                ->getStateUsing(function ($record) {
                        
                                                    $indiceMuscularidad = ($record->AoB / $record->weight) * 100; 
                                                    return number_format($indiceMuscularidad, 0);
                                                    
                                                }),       
                                            ]),
                                        Group::make([
                                            TextEntry::make('rfid')->size('lg')
                                                ->size('lg')
                                                ->label('RFID')
                                                ->weight('bold'),
                                            TextEntry::make('gd')
                                                ->label('G.D')
                                                ->size('lg')
                                                ->weight('bold'),
                                            TextEntry::make('case')
                                                ->label('Carcasa')
                                                ->size('lg')
                                                ->weight('bold')
                                                ->getStateUsing(function ($record) {
                        
                                                    $case = $record->weight * 0.59; 
                                                    return number_format($case, 0);

                                                }),
                                            TextEntry::make('yg')
                                                ->label('Yield Grade')
                                                ->size('lg')
                                                ->weight('bold')
                                                ->getStateUsing(function ($record) use ($engMeasures) {
                        
                                                    $case = $record->weight * 0.59; 

                                                    $n1 = $record->gd * $engMeasures['inch'];
                                                    $n2 = $engMeasures['kph%'];
                                                    $n3 = $case * $engMeasures['libras'];
                                                    $n4 = $record->AoB * $engMeasures['inch2'];
                                        
                                                    $yieldGrade = 2.5+(2.5*$n1)+(0.2*$n2)+(0.0038*$n3)-(0.32*$n4); 
                            
                                                    return number_format($yieldGrade, 1);

                                                }),
                                        ]),
                                        Group::make([
                                            TextEntry::make('grade')
                                                ->getStateUsing(function ($record) {
                        
                                                    $gim = $record->gim;
                                                    $grade = AnimalResource::getGrade($gim,'grade');
                                                    $name = AnimalResource::getGrade($gim,'name');
                        
                                                    return $grade . ' - ' . $name;
                                                })
                                                ->label('Grado')
                                                ->size('lg')
                                                ->weight('bold'),
                                            ImageEntry::make('grade-image')
                                                ->hiddenLabel()  
                                                ->visibility('private')
                                                ->height(150)
                                                ->grow(false)
                                                ->extraImgAttributes([
                                                    'class' => 'mx-auto block',
                                                    'loading' => 'lazy',
                                                ])
                                                ->getStateUsing(function ($record) {
                        
                                                    $gim = $record->gim;
                                                    $name = AnimalResource::getGrade($gim,'name');
                                                    $name = strtolower(str_replace(' ','',$name));
                                                    $gradeImage = "marbling\/$name.png";
                        
                                                    return $gradeImage;
                                                }),
                                            Grid::make(2)
                                                ->schema([
                                                TextEntry::make('rc')
                                                    ->getStateUsing(function ($record) use ($engMeasures) {

                                                        $case = $record->weight * 0.59; 
                                
                                                        $n1 = $record->gd * $engMeasures['inch'];
                                                        $n2 = $engMeasures['kph%'];
                                                        $n3 = $case * $engMeasures['libras'];
                                                        $n4 = $record->AoB * $engMeasures['inch2'];
                                
                                                        $rc = 65.59-(9.93*$n1)-(1.29*$n2)+(1.23*$n4)-(0.013*$n3); 
                                
                                                        return number_format($rc, 2);
                                                    })
                                                    ->label('% R.C')
                                                    ->size('lg')
                                                    ->weight('bold'),
                                                TextEntry::make('ms')
                                                    ->getStateUsing(function ($record) use ($engMeasures) {

                                                        $gim = $record->gim;

                                                        $ms = ((769.7 + (56.69 * $gim)) / 100) - 5;
                                                        return number_format($ms, 1);
                                                    })
                                                    ->label('Marbling Score')
                                                    ->size('lg')
                                                    ->weight('bold'),
                                        
                                                ])
                                        ])
                                    ]),
                            ])->from('lg'),
                        ]),
                    InfolistSection::make('Ecografias')
                        ->schema([
                            TextEntry::make('images')
                            ->hiddenLabel()
                            ->getStateUsing(function ($record) {

                                $media = $record->images; // Obtén la URL de la primera imagen

                                return view('components.image-view', ['urls' => $media]);
                            }),
                        ])
                        ->collapsible(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    BulkAction::make('assignToPurchase')
                        ->label('Reservar')
                        ->icon('heroicon-o-currency-dollar')
                        ->requiresConfirmation()
                        ->action(function (Collection $records, array $data) {

                            foreach ($records as $record) {
                                $record->sold = 1; 
                                $record->save();
                            }

                            return false;
                            // Mensaje de éxito
                        })
                        // ->success('Registros asignados a la carpeta exitosamente.')
                ])
            ]);
    }

    public static function getGrade($gim, $type): string
    {
        
        switch ($gim) {
            case $gim > 6.5:
                $grade = 6;
                $name = 'Abundante';
                break;
            case ($gim > 5 && $gim <= 6.5):
                $grade = 5;
                $name = 'Moderado';
                break;
            
            case ($gim > 4 && $gim <= 5):
                $grade = 4;
                $name = 'Modesto';
                break;
            
            case ($gim > 3 && $gim <= 4):
                $grade = 3;
                $name = 'Escaso';
                break;
            
            case ($gim > 1.8 && $gim <= 3):
                $grade = 2;
                $name = 'Muy Escaso';
                break;

            case ($gim > 0.5 && $gim <= 1.8):
                $grade = 1;
                $name = 'Trazas';
                break;

            case ($gim <= 0.5):
                $grade = 0;
                $name = 'Sin Grasa';
                break;
            
            default:
                # code...
                break;
        }

        if($type == 'grade'){
            return $grade;
        } else {
            return $name;
        }

    }
   

}
