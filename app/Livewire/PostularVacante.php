<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

class PostularVacante extends Component
{
    public $cv;

    use WithFileUploads;
    protected $rules = [
        'cv' => 'required|mimes:pdf'
    ];

    public function postularme()
    {
        $datos = $this->validate();

        // Almacenar CV en el disco duro
        $cv = $this->cv->store('public/cv');
        $datos ['imagen'] = str_replace('public/cv/', '', $cv);
         

        // Creaal la vacante

        // Crear notificacion y enviar l email

        // Mostrar el usuario un mensaje de ok
    }
    public function render()
    {
        return view('livewire.postular-vacante');
    }
}
