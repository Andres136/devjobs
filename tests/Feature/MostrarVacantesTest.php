<?php

use App\Livewire\MostrarVacantes;
use App\Models\Categoria;
use App\Models\Salario;
use App\Models\User;
use App\Models\Vacante;
use Livewire\Livewire;

function crearVacantePara(User $user): Vacante
{
    $salario = Salario::forceCreate(['salario' => fake()->unique()->word()]);
    $categoria = Categoria::forceCreate(['categoria' => fake()->unique()->word()]);

    return Vacante::create([
        'titulo' => 'Desarrollador Laravel',
        'salario_id' => $salario->id,
        'categoria_id' => $categoria->id,
        'empresa' => 'Acme',
        'ultimo_dia' => now()->addWeek()->toDateString(),
        'descripcion' => 'Descripción de prueba',
        'imagen' => 'vacante.jpg',
        'publicado' => 1,
        'user_id' => $user->id,
    ]);
}

test('solicita confirmación antes de eliminar una vacante', function () {
    $user = User::factory()->create();
    $vacante = crearVacantePara($user);

    Livewire::actingAs($user)
        ->test(MostrarVacantes::class)
        ->call('mostrarAlerta', $vacante->id)
        ->assertDispatched('mostrarAlerta', vacanteId: $vacante->id);
});

test('elimina una vacante que pertenece al usuario autenticado', function () {
    $user = User::factory()->create();
    $vacante = crearVacantePara($user);

    Livewire::actingAs($user)
        ->test(MostrarVacantes::class)
        ->call('eliminarVacante', $vacante->id)
        ->assertDispatched('vacanteEliminada');

    expect($vacante->fresh())->toBeNull();
});
