<?php

namespace App\Livewire;

use App\Models\Vacante;
use Livewire\Attributes\On;
use Livewire\Component;

class HomeVacantes extends Component
{
    public $termino = '';
    public $categoria = '';
    public $salario = '';

    #[On('terminosBusqueda')]
    public function buscar($termino = '', $categoria = '', $salario = '')
    {
        $this->termino = trim($termino ?? '');
        $this->categoria = $categoria;
        $this->salario = $salario;
    }

    public function render()
    {
        $vacantes = Vacante::query()
            ->when($this->termino, function ($query) {
                $query->where('titulo', 'like', '%' . $this->termino . '%');
            })
            ->when($this->termino, function ($query) {
                $query->orWhere('empresa', 'like', '%' . $this->termino . '%');
            })
             ->when($this->categoria, function ($query) {
                $query->orWhere('categoria_id', $this->categoria);
            })
            ->when($this->salario, function ($query) {
                $query->where('salario_id', $this->salario);
            })
            ->paginate(20);

        return view('livewire.home-vacantes', [
            'vacantes' => $vacantes,
        ]);
    }
}
