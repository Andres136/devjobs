<?php

namespace App\Livewire;

use Livewire\Component;

class PostularVacante extends Component
{
    public $cv;
    protected $rules = [
        'cv' => 'required|mimes:pdf'
    ];

    public function postularme()
    {
        $this->validate();

        // Almacenar CV en el disco duro

        // Creaal la vacante

        // Crear notificacion y enviar l email

        // Mostrar el usuario un mensaje de ok
    }
    public function render()
    {
        return view('livewire.postular-vacante');
    }
}
