<?php

namespace App\Livewire;

use App\Models\Vacante;
use Livewire\Component;

class MostrarVacantes extends Component
{
    protected $listeners = ['eliminarVacante'];

    public function mostrarAlerta(int $vacanteId): void
    {
        $this->dispatch('mostrarAlerta', vacanteId: $vacanteId);
    }

    public function eliminarVacante(int $vacanteId): void
    {
        $vacante = Vacante::where('user_id', auth()->id())
            ->findOrFail($vacanteId);

        $vacante->delete();

        $this->dispatch('vacanteEliminada');
    }

    public function render()
    {
        $vacantes = Vacante::where('user_id', auth()->id())->paginate(10);

        return view('livewire.mostrar-vacantes', [
            'vacantes' => $vacantes,
        ]);
    }
}
