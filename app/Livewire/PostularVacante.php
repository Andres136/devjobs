<?php

namespace App\Livewire;

use App\Models\Vacante;
use App\Notifications\NuevoCandidato;
use Livewire\Component;
use Livewire\WithFileUploads;

class PostularVacante extends Component
{
    public $cv;
    public $vacante;

    use WithFileUploads;
    protected $rules = [
        'cv' => 'required|file|extensions:pdf'
    ];

    public function mount(Vacante  $vacante)
    {
        $this->vacante = $vacante;
    }

    public function postularme()
    {
        $datos = $this->validate();

        // Almacenar CV en el disco duro
       $cv = $this->cv->store('cv', 'public');
        $datos ['cv'] = str_replace('public/cv/', '', $cv);
         

        // Crear el candidato a la vacante
        $this->vacante->candidatos()->create([
           'user_id' => auth()->user()->id,
           'cv'=> $datos ['cv']
        ]);

        // Crear notificacion y enviar l email
        $this->vacante->reclutador->notify(new NuevoCandidato($this->vacante->id, $this->vacante->titulo,auth()->user()->id));

        // Mostrar el usuario un mensaje de ok
        session()->flash('mensaje', ' s envio correctamente tu informacion, mucha suerte');

        return redirect()->back();
    }
    public function render()
    {
        return view('livewire.postular-vacante');
    }
}
