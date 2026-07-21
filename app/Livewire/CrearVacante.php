<?php

namespace App\Livewire;

use App\Models\Categoria;
use App\Models\Salario;
use App\Models\Vacante;
use Illuminate\Validation\ValidationException;
use League\Flysystem\UnableToRetrieveMetadata;
use Livewire\Component;
use Livewire\WithFileUploads;

class CrearVacante extends Component
{
    use WithFileUploads;

    public $titulo;
    public $salario;
    public $categoria;
    public $empresa;
    public $ultimo_dia;
    public $descripcion;
    public $imagen;
    public $imagenPreview;

    protected $rules = [
        'titulo' => 'required|string',
        'salario' => 'required',
        'categoria' => 'required',
        'empresa' => 'required',
        'ultimo_dia' => 'required',
        'descripcion' => 'required',
        'imagen' => 'required|image|max:1024',
    ];

    public function crearVacante()
    {
        try {
            $datos = $this->validate();
        } catch (UnableToRetrieveMetadata $e) {
            $this->reset('imagen', 'imagenPreview');
            $this->addError('imagen', 'Selecciona la imagen nuevamente.');

            return;
        }

        //Almacenar  la imagen
        $imagen = $this->imagen->store('vacantes', 'public');
        $datos['imagen'] = str_replace('vacantes/', '', $imagen);
        //dd($nombre_Imagen);

        //Almacenar  la Vacante
        Vacante::create([
            'titulo' => $datos['titulo'],
            'salario_id' => $datos['salario'],
            'categoria_id' => $datos['categoria'],
            'empresa' => $datos['empresa'],
            'ultimo_dia' => $datos['ultimo_dia'],
            'descripcion' => $datos['descripcion'],
            'imagen' => $datos['imagen'],
            'user_id' => auth()->user()->id
        ]);

        //Almacenar  un mensaje
        session()->flash('mensaje', 'La vacante se publicó correctamente');

        //Redireccionar al usuario
        return redirect()->route('vacantes.index');
    }

    public function updatedImagen()
    {
        $this->resetErrorBag('imagen');
        $this->imagenPreview = null;

        try {
            $this->validateOnly('imagen');
            $this->imagenPreview = $this->imagen->temporaryUrl();
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            $this->reset('imagen', 'imagenPreview');
            $this->addError('imagen', 'Selecciona una imagen valida nuevamente.');
        }
    }

    public function render()
    {
        //Consulta BD
        $salarios = Salario::all();
        $categorias = Categoria::all();

        return view('livewire.crear-vacante', [
            'salarios' => $salarios,
            'categorias' => $categorias
        ]);
    }
}
