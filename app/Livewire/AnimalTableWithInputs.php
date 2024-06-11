<?php

namespace App\Livewire;

use App\Models\Animal;
use Livewire\Component;

class AnimalTableWithInputs extends Component
{
    public $category;
    public $rfid;
    public $weight;
    public $gd;
    public $AoB;
    public $AoBType;
    public $case;

    protected $rules = [
        'category' => 'required|string|max:255',
        'rfid' => 'required|string|max:255',
        'weight' => 'required|numeric|max:255',
        'gd' => 'required|numeric|max:255',
        'AoB' => 'required|numeric|max:255',
        'AoBType' => 'required|string|max:255',
        'case' => 'required|numeric|max:255',
    ];

    public function addAnimal()
    {
        $this->validate();

        Animal::create([
            'category' => $this->category,
            'rfid' => $this->rfid,
            'weight' => $this->weight,
            'gd' => $this->gd,
            'AoB' => $this->AoB,
            'AoBType' => $this->AoBType,
            'case' => $this->case,
        ]);

        // Limpia los inputs después de agregar el animal
        $this->reset(['category','rfid','weight','gd','AoB','AoBType','case']);

        // Actualiza la tabla
        $this->emit('refreshTable');
    }

    public function render()
    {
        return view('livewire.animal-table-with-inputs', [
            'animals' => Animal::all(),
        ]);
    }
}
