# Flujo de vacantes y correcciones realizadas

Este documento resume lo que se reviso y corrigio durante el desarrollo del flujo para publicar vacantes.

## Objetivo del flujo

El usuario autenticado entra a `vacantes/create`, llena el formulario de una nueva vacante, selecciona salario, categoria e imagen, envia el formulario y luego la aplicacion guarda la vacante en base de datos y redirige al listado de vacantes.

## Archivos principales

### `routes/web.php`

Define las rutas web de Laravel.

Rutas importantes:

```php
Route::get('/dashboard', [VacanteController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('vacantes.index');

Route::get('/vacantes/create', [VacanteController::class, 'create'])
    ->middleware(['auth', 'verified'])
    ->name('vacantes.create');
```

Que hace cada una:

- `vacantes.index`: muestra el listado de vacantes. Actualmente usa la URL `/dashboard`.
- `vacantes.create`: muestra el formulario para crear una vacante.
- `middleware(['auth', 'verified'])`: obliga a que el usuario este autenticado y verificado.

### `app/Http/Controllers/VacanteController.php`

Controlador que devuelve las vistas de vacantes.

Flujo esperado:

```php
public function index()
{
    return view('vacantes.index');
}

public function create()
{
    return view('vacantes.create');
}
```

El controlador no guarda la vacante directamente porque ese trabajo lo hace el componente Livewire `CrearVacante`.

### `resources/views/vacantes/create.blade.php`

Vista principal de la pagina de creacion.

Dentro de esta vista se carga el componente Livewire:

```blade
<livewire:crear-vacante />
```

Esto significa que el formulario real esta en:

```text
resources/views/livewire/crear-vacante.blade.php
```

### `resources/views/livewire/crear-vacante.blade.php`

Es el formulario visual.

El formulario envia la informacion a Livewire con:

```blade
<form wire:submit.prevent='crearVacante'>
```

Eso significa:

- No se envia como formulario HTML normal.
- Livewire intercepta el submit.
- Se ejecuta el metodo `crearVacante()` del componente `app/Livewire/CrearVacante.php`.

Campos importantes:

```blade
wire:model.live="titulo"
wire:model.live="salario"
wire:model.live="categoria"
wire:model.live="empresa"
wire:model.live="ultimo_dia"
wire:model.live="descripcion"
wire:model="imagen"
```

Cada `wire:model` debe coincidir con una propiedad publica del componente Livewire.

Ejemplo:

```blade
wire:model.live="salario"
```

necesita esta propiedad en PHP:

```php
public $salario;
```

### `app/Livewire/CrearVacante.php`

Es el componente que controla el formulario.

Propiedades:

```php
public $titulo;
public $salario;
public $categoria;
public $empresa;
public $ultimo_dia;
public $descripcion;
public $imagen;
public $imagenPreview;
```

Estas propiedades reciben los datos del formulario.

Reglas de validacion:

```php
protected $rules = [
    'titulo' => 'required|string',
    'salario' => 'required',
    'categoria' => 'required',
    'empresa' => 'required',
    'ultimo_dia' => 'required',
    'descripcion' => 'required',
    'imagen' => 'required|image|max:1024',
];
```

Significado:

- `required`: el campo es obligatorio.
- `string`: debe ser texto.
- `image`: debe ser una imagen valida.
- `max:1024`: la imagen no debe pesar mas de 1024 KB.

Metodo principal:

```php
public function crearVacante()
{
    try {
        $datos = $this->validate();
    } catch (UnableToRetrieveMetadata $e) {
        $this->reset('imagen', 'imagenPreview');
        $this->addError('imagen', 'Selecciona la imagen nuevamente.');

        return;
    }

    $imagen = $this->imagen->store('public/vacantes');
    $datos['imagen'] = str_replace('public/vacantes/', '', $imagen);

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

    session()->flash('mensaje', 'La vacante se publico correctamente');

    return redirect()->route('vacantes.index');
}
```

Flujo:

1. Valida los datos.
2. Si Livewire perdio el archivo temporal de imagen, limpia el campo y muestra error.
3. Guarda la imagen en `storage/app/public/vacantes`.
4. Limpia la ruta para guardar solo el nombre del archivo.
5. Crea la vacante en base de datos.
6. Guarda un mensaje flash.
7. Redirige al listado `vacantes.index`.

Metodo para preview de imagen:

```php
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
```

Este metodo se ejecuta automaticamente cuando cambia la propiedad `$imagen`.

Sirve para:

- Validar solo la imagen.
- Crear una URL temporal para mostrar preview.
- Evitar error 500 si Livewire no puede previsualizar el archivo.

### `app/Models/Vacante.php`

Modelo Eloquent de la tabla `vacantes`.

Campos permitidos para asignacion masiva:

```php
protected $fillable = [
    'titulo',
    'salario_id',
    'categoria_id',
    'empresa',
    'ultimo_dia',
    'descripcion',
    'imagen',
    'publicado',
    'user_id'
];
```

Esto permite usar:

```php
Vacante::create([...]);
```

Relaciones:

```php
public function salario()
{
    return $this->belongsTo(Salario::class);
}

public function categoria()
{
    return $this->belongsTo(Categoria::class);
}
```

Una vacante pertenece a un salario y a una categoria.

### `database/migrations/2026_07_09_234107_add_columns_to_vacantes_table.php`

Migracion que agrega columnas a la tabla `vacantes`.

En `up()` agrega:

```php
$table->string('titulo');
$table->foreignId('salario_id')->constrained()->onDelete('cascade');
$table->foreignId('categoria_id')->constrained()->onDelete('cascade');
$table->string('empresa');
$table->string('ultimo_dia');
$table->text('descripcion');
$table->string('imagen');
$table->integer('publicado')->default(1);
$table->foreignId('user_id')->constrained()->onDelete('cascade');
```

En `down()` elimina primero las llaves foraneas y luego las columnas:

```php
$table->dropForeign('vacantes_salario_id_foreign');
$table->dropForeign('vacantes_categoria_id_foreign');
$table->dropForeign('vacantes_user_id_foreign');

$table->dropColumn([
    'titulo',
    'salario_id',
    'categoria_id',
    'empresa',
    'ultimo_dia',
    'descripcion',
    'imagen',
    'publicado',
    'user_id'
]);
```

Es importante borrar primero las llaves foraneas porque MySQL no permite eliminar columnas que tienen restricciones activas.

## Errores encontrados y solucion

### 1. Error al hacer rollback de migracion

Error:

```text
Can't DROP 'vacantes_salario_id_foreign'; check that column/key exists
```

Causa:

El `down()` intentaba eliminar dos veces la misma foreign key:

```php
$table->dropForeign('vacantes_salario_id_foreign');
$table->dropForeign('vacantes_salario_id_foreign');
```

Solucion:

Eliminar la repetida y agregar la foreign key que faltaba:

```php
$table->dropForeign('vacantes_salario_id_foreign');
$table->dropForeign('vacantes_categoria_id_foreign');
$table->dropForeign('vacantes_user_id_foreign');
```

Nota:

Como el primer rollback alcanzo a borrar una foreign key antes de fallar, hubo un estado intermedio inconsistente. Se ejecuto de nuevo el rollback despues de corregir la migracion.

Comando usado:

```bash
php artisan migrate:rollback
```

Resultado final:

```text
2026_07_09_234107_add_columns_to_vacantes_table .. DONE
```

### 2. Error de Livewire con propiedades inexistentes

Error:

```text
Livewire: [wire:model="salario_id"] property does not exist on component: [crear-vacante]
Livewire: [wire:model="categoria_id"] property does not exist on component: [crear-vacante]
```

Causa:

En la vista se usaba:

```blade
wire:model.live="salario_id"
wire:model.live="categoria_id"
```

Pero en el componente existian:

```php
public $salario;
public $categoria;
```

Solucion:

Cambiar la vista a:

```blade
wire:model.live="salario"
wire:model.live="categoria"
```

Regla importante:

El valor de `wire:model` debe existir como propiedad publica en el componente.

### 3. Error al previsualizar imagen

Error:

```text
Livewire\Features\SupportFileUploads\FileNotPreviewableException
File with extension "" is not previewable.
```

Causa:

La vista llamaba directamente:

```blade
<img src="{{ $imagen->temporaryUrl() }}">
```

Si Livewire no podia detectar una imagen valida, la vista explotaba con error 500.

Solucion:

Mover la generacion del preview al componente:

```php
public $imagenPreview;
```

```php
$this->imagenPreview = $this->imagen->temporaryUrl();
```

Y en la vista mostrar solo la URL ya generada:

```blade
@if ($imagenPreview)
    Imagen:
    <img src="{{ $imagenPreview }}">
@endif
```

### 4. Error al validar imagen temporal perdida

Error en logs:

```text
Unable to retrieve the file_size for file at location: livewire-tmp/...
```

Causa:

Livewire tenia registrada una imagen temporal, pero el archivo fisico ya no existia en `storage/app/private/livewire-tmp`.

Solucion:

Capturar la excepcion durante la validacion:

```php
try {
    $datos = $this->validate();
} catch (UnableToRetrieveMetadata $e) {
    $this->reset('imagen', 'imagenPreview');
    $this->addError('imagen', 'Selecciona la imagen nuevamente.');

    return;
}
```

Asi la pagina no se cae con error 500 y el usuario puede seleccionar la imagen nuevamente.

### 5. Error de ruta no encontrada

Error:

```text
Route [vacantes.index] not defined.
```

Causa:

El componente redirigia a:

```php
return redirect()->route('vacantes.index');
```

Pero en ese momento la ruta del listado estaba nombrada como:

```php
->name('dashboard')
```

Solucion:

Nombrar la ruta del listado como `vacantes.index`:

```php
Route::get('/dashboard', [VacanteController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('vacantes.index');
```

Con eso esta redireccion funciona:

```php
return redirect()->route('vacantes.index');
```

## Comandos ejecutados

### Buscar texto en el proyecto

```bash
rg -n "vacantes_salario_id_foreign|salario_id|vacantes" database/migrations
```

Sirve para encontrar donde aparece una cadena en los archivos.

### Listar migraciones

```bash
ls database/migrations
```

Sirve para ver los archivos de migracion disponibles.

### Ver archivos con numeros de linea

```bash
nl -ba app/Livewire/CrearVacante.php
nl -ba resources/views/livewire/crear-vacante.blade.php
nl -ba routes/web.php
```

Sirve para ubicar exactamente la linea del error.

### Ejecutar rollback

```bash
php artisan migrate:rollback
```

Sirve para revertir la ultima migracion ejecutada.

### Ver rutas registradas

```bash
php artisan route:list
```

Sirve para confirmar los nombres reales de las rutas.

En este caso confirmo:

```text
GET|HEAD dashboard .. vacantes.index › VacanteController@index
GET|HEAD vacantes/create .. vacantes.create › VacanteController@create
```

### Revisar logs de Laravel

```bash
tail -n 120 storage/logs/laravel.log
```

Sirve para ver el error real cuando la pantalla solo muestra un 500 general.

### Validar sintaxis PHP

```bash
php -l app/Livewire/CrearVacante.php
```

Sirve para confirmar que el archivo PHP no tiene errores de sintaxis.

Resultado:

```text
No syntax errors detected in app/Livewire/CrearVacante.php
```

## Flujo completo de creacion de vacante

1. El usuario entra a:

```text
/vacantes/create
```

2. Laravel ejecuta la ruta:

```php
Route::get('/vacantes/create', [VacanteController::class, 'create'])
```

3. El controlador devuelve:

```php
return view('vacantes.create');
```

4. La vista carga:

```blade
<livewire:crear-vacante />
```

5. Livewire renderiza:

```text
resources/views/livewire/crear-vacante.blade.php
```

6. El usuario llena campos.

7. Cada campo se sincroniza con una propiedad publica del componente.

Ejemplo:

```blade
wire:model.live="titulo"
```

actualiza:

```php
public $titulo;
```

8. Cuando selecciona imagen, se ejecuta:

```php
updatedImagen()
```

9. Al enviar el formulario, se ejecuta:

```php
crearVacante()
```

10. Se validan los datos.

11. Se guarda la imagen.

12. Se crea el registro:

```php
Vacante::create([...]);
```

13. Se crea mensaje flash:

```php
session()->flash('mensaje', 'La vacante se publico correctamente');
```

14. Se redirige:

```php
return redirect()->route('vacantes.index');
```

## Reglas importantes aprendidas

- En Livewire, `wire:model` debe coincidir con una propiedad publica del componente.
- Si usas `Vacante::create()`, los campos deben estar en `$fillable`.
- Para subir archivos con Livewire se necesita `use WithFileUploads;`.
- No conviene llamar `$imagen->temporaryUrl()` directamente en Blade sin control.
- Si una ruta se usa con `route('nombre')`, ese nombre debe existir en `routes/web.php`.
- Para revisar nombres de rutas, usar `php artisan route:list`.
- Para errores 500, revisar `storage/logs/laravel.log`.
- En migraciones, primero se eliminan foreign keys y despues columnas.

## Comandos para crear la estructura del flujo

Esta seccion resume los comandos que se usan para crear los archivos principales del flujo de vacantes. Algunos ya existen en el proyecto; se documentan para entender de donde sale cada archivo y para poder repetir el proceso.

### Crear modelo `Vacante`

Comando:

```bash
php artisan make:model Vacante
```

Genera:

```text
app/Models/Vacante.php
```

Sirve para representar la tabla `vacantes` desde PHP usando Eloquent.

En este proyecto el modelo contiene:

```php
protected $fillable = [
    'titulo',
    'salario_id',
    'categoria_id',
    'empresa',
    'ultimo_dia',
    'descripcion',
    'imagen',
    'publicado',
    'user_id'
];
```

Esto permite guardar datos con:

```php
Vacante::create([...]);
```

### Crear modelo con migracion

Comando comun:

```bash
php artisan make:model Vacante -m
```

Genera:

```text
app/Models/Vacante.php
database/migrations/xxxx_xx_xx_xxxxxx_create_vacantes_table.php
```

Sirve para crear el modelo y la migracion al mismo tiempo.

Nota:

En el proyecto existe esta migracion:

```text
database/migrations/2026_05_31_025347_create_vacante_table.php
```

El nombre esta en singular `create_vacante_table`, pero la tabla creada dentro es `vacantes`.

### Crear migracion para agregar columnas a `vacantes`

Comando:

```bash
php artisan make:migration add_columns_to_vacantes_table --table=vacantes
```

Genera un archivo parecido a:

```text
database/migrations/2026_07_09_234107_add_columns_to_vacantes_table.php
```

Sirve para modificar una tabla existente.

En este caso se uso para agregar:

```php
$table->string('titulo');
$table->foreignId('salario_id')->constrained()->onDelete('cascade');
$table->foreignId('categoria_id')->constrained()->onDelete('cascade');
$table->string('empresa');
$table->string('ultimo_dia');
$table->text('descripcion');
$table->string('imagen');
$table->integer('publicado')->default(1);
$table->foreignId('user_id')->constrained()->onDelete('cascade');
```

### Crear modelo `Salario`

Comando:

```bash
php artisan make:model Salario -m
```

Genera:

```text
app/Models/Salario.php
database/migrations/xxxx_xx_xx_xxxxxx_create_salarios_table.php
```

En el proyecto existe:

```text
app/Models/Salario.php
database/migrations/2026_06_01_010606_create_salarios_table.php
```

Sirve para manejar los salarios que se muestran en el select del formulario.

El componente los consulta con:

```php
$salarios = Salario::all();
```

Y la vista los muestra con:

```blade
@foreach ($salarios as $salario)
    <option value="{{ $salario->id }}">{{ $salario->salario }}</option>
@endforeach
```

### Crear modelo `Categoria`

Comando:

```bash
php artisan make:model Categoria -m
```

Genera:

```text
app/Models/Categoria.php
database/migrations/xxxx_xx_xx_xxxxxx_create_categorias_table.php
```

En el proyecto existe:

```text
app/Models/Categoria.php
database/migrations/2026_06_01_032957_create_categorias_table.php
```

Sirve para manejar las categorias que se muestran en el select del formulario.

El componente las consulta con:

```php
$categorias = Categoria::all();
```

Y la vista las muestra con:

```blade
@foreach ($categorias as $categoria)
    <option value="{{ $categoria->id }}">{{ $categoria->categoria }}</option>
@endforeach
```

### Crear controlador `VacanteController`

Comando:

```bash
php artisan make:controller VacanteController
```

Genera:

```text
app/Http/Controllers/VacanteController.php
```

Sirve para agrupar las acciones web relacionadas con vacantes.

En este flujo se usan principalmente:

```php
public function index()
{
    return view('vacantes.index');
}

public function create()
{
    return view('vacantes.create');
}
```

Tambien se pudo crear como controlador resource:

```bash
php artisan make:controller VacanteController --resource
```

Ese comando genera metodos como:

```php
index()
create()
store()
show()
edit()
update()
destroy()
```

En este proyecto existe un `store()` en el controlador, pero el guardado real de la vacante se esta haciendo desde Livewire en `CrearVacante`.

### Crear componente Livewire `CrearVacante`

Comando:

```bash
php artisan make:livewire CrearVacante
```

Genera:

```text
app/Livewire/CrearVacante.php
resources/views/livewire/crear-vacante.blade.php
```

Sirve para crear un componente interactivo.

En este proyecto:

- `app/Livewire/CrearVacante.php` contiene la logica del formulario.
- `resources/views/livewire/crear-vacante.blade.php` contiene el HTML del formulario.

Se carga en una vista con:

```blade
<livewire:crear-vacante />
```

### Crear componente Livewire `MostrarAlerta`

Comando:

```bash
php artisan make:livewire MostrarAlerta
```

Genera:

```text
app/Livewire/MostrarAlerta.php
resources/views/livewire/mostrar-alerta.blade.php
```

Sirve para reutilizar el componente visual de errores.

Se usa asi:

```blade
@error('titulo')
    <livewire:mostrar-alerta :message="$message" />
@enderror
```

Tambien se usa con otros campos:

```blade
@error('imagen')
    <livewire:mostrar-alerta :message="$message" />
@enderror
```

### Crear un Form Request

Comando:

```bash
php artisan make:request StoreVacanteRequest
```

Genera:

```text
app/Http/Requests/StoreVacanteRequest.php
```

Sirve para mover reglas de validacion fuera del controlador.

Ejemplo:

```php
public function rules(): array
{
    return [
        'titulo' => 'required|string',
        'salario' => 'required',
        'categoria' => 'required',
        'empresa' => 'required',
        'ultimo_dia' => 'required',
        'descripcion' => 'required',
        'imagen' => 'required|image|max:1024',
    ];
}
```

Nota importante:

Para este flujo de vacantes no se creo un `StoreVacanteRequest`, porque la validacion esta dentro del componente Livewire:

```php
protected $rules = [
    'titulo' => 'required|string',
    'salario' => 'required',
    'categoria' => 'required',
    'empresa' => 'required',
    'ultimo_dia' => 'required',
    'descripcion' => 'required',
    'imagen' => 'required|image|max:1024',
];
```

Los requests que si existen actualmente son de autenticacion/perfil:

```text
app/Http/Requests/Auth/LoginRequest.php
app/Http/Requests/ProfileUpdateRequest.php
```

Esos vienen normalmente con Laravel Breeze o el scaffold de autenticacion.

### Crear seeders

Comando para salario:

```bash
php artisan make:seeder SalarioSeeder
```

Genera:

```text
database/seeders/SalarioSeeder.php
```

Comando para categorias:

```bash
php artisan make:seeder CategoriasSeeder
```

Genera:

```text
database/seeders/CategoriasSeeder.php
```

Sirven para llenar tablas base, por ejemplo salarios y categorias que luego aparecen en los selects.

Para ejecutar seeders:

```bash
php artisan db:seed
```

O ejecutar uno especifico:

```bash
php artisan db:seed --class=SalarioSeeder
php artisan db:seed --class=CategoriasSeeder
```

### Ejecutar migraciones

Comando:

```bash
php artisan migrate
```

Sirve para aplicar las migraciones pendientes y crear/modificar tablas en la base de datos.

### Revertir ultima migracion

Comando:

```bash
php artisan migrate:rollback
```

Sirve para deshacer el ultimo batch de migraciones.

Fue el comando usado cuando encontramos el problema con las foreign keys del metodo `down()`.

### Refrescar toda la base de datos

Comando:

```bash
php artisan migrate:fresh
```

Sirve para borrar todas las tablas y correr todas las migraciones desde cero.

Con seeders:

```bash
php artisan migrate:fresh --seed
```

Advertencia:

Este comando borra los datos de la base de datos. Usarlo solo en desarrollo.

### Crear enlace publico para storage

Comando:

```bash
php artisan storage:link
```

Genera el enlace:

```text
public/storage -> storage/app/public
```

Sirve para que las imagenes guardadas en:

```text
storage/app/public/vacantes
```

se puedan mostrar desde el navegador.

Como el componente guarda imagenes con:

```php
$this->imagen->store('public/vacantes');
```

normalmente necesitas `storage:link` para mostrarlas despues.

### Instalar autenticacion con Laravel Breeze

Si el proyecto fue creado desde cero, una forma comun de generar login, registro, perfil y requests de autenticacion es:

```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install
npm run build
php artisan migrate
```

Esto suele generar archivos como:

```text
app/Http/Controllers/Auth/*
app/Http/Requests/Auth/LoginRequest.php
app/Http/Requests/ProfileUpdateRequest.php
resources/views/auth/*
resources/views/profile/*
resources/views/layouts/*
routes/auth.php
```

En el proyecto ya existen esos archivos, por eso el flujo de vacantes esta protegido con:

```php
middleware(['auth', 'verified'])
```

### Comandos de verificacion usados durante el diagnostico

Ver rutas:

```bash
php artisan route:list
```

Ver errores de Laravel:

```bash
tail -n 120 storage/logs/laravel.log
```

Validar sintaxis PHP:

```bash
php -l app/Livewire/CrearVacante.php
```

Buscar texto en archivos:

```bash
rg -n "vacantes.index|route\\(|wire:model|salario_id|categoria_id" app resources routes database
```

Ver archivo con numeros de linea:

```bash
nl -ba app/Livewire/CrearVacante.php
nl -ba resources/views/livewire/crear-vacante.blade.php
nl -ba routes/web.php
```

## Commits sugeridos

Para los cambios de migracion:

```bash
fix: corregir rollback de llaves foraneas en vacantes
```

Para los cambios del formulario de vacantes:

```bash
fix: corregir creacion de vacantes con imagen
```
