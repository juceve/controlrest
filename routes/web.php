<?php

use App\Http\Controllers\CatitemController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\EntregalounchController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\FeriadoController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MonedaController;
use App\Http\Controllers\NivelcursoController;
use App\Http\Controllers\PreciomenuController;
use App\Http\Controllers\ReporteController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SucursaleController;
use App\Http\Controllers\TipomenuController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\VentasconfigController;
use App\Http\Livewire\Clientes\Tutores;
use App\Http\Livewire\Clientes\Vinculosestudiantes;

use App\Http\Livewire\Entregas\Individual;
use App\Http\Livewire\Entregas\Noentregados;
use App\Http\Livewire\Entregas\Porcurso;
use App\Http\Livewire\Entregas\Profesores;
use App\Http\Livewire\Entregas\Estadopedidos;
use App\Http\Livewire\Masivos;
use App\Http\Livewire\Menu\Elaborarmenu;
use App\Http\Livewire\Menu\Events;
use App\Http\Livewire\Menu\Finish;
use App\Http\Livewire\Menu\Formapago;
use App\Http\Livewire\Menu\Pedido;
use App\Http\Livewire\Menu\Resumen;
use App\Http\Livewire\Pagos\Pagoprofesores;
use App\Http\Livewire\Pedidos\Ppersonales;
use App\Http\Livewire\Reportes\Diario;
use App\Http\Livewire\Reportes\Rventas;
use App\Http\Livewire\Ventas\Aprobarpedido;
use App\Http\Livewire\Ventas\Bonoanual;
use App\Http\Livewire\Ventas\Bonofecha2;
use App\Http\Livewire\Ventas\Bonomensual;
use App\Http\Livewire\Ventas\Cierrebonoreserva;
use App\Http\Livewire\Ventas\Cierrecaja;

use App\Http\Livewire\Ventas\Nueva;
use App\Http\Livewire\Ventas\Pos;
use App\Http\Livewire\Ventas\Reservas;
use App\Http\Livewire\Ventas\Verificacionpedidos;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->middleware('auth')->middleware('can:home')->name('home');

Route::post('users/resetPassword/{id}',[UserController::class,'resetPassword'])->name('admin.users.resetPassword');
Route::get('users/asignaRol/{user}',[UserController::class,'asinaRol'])->name('admin.users.asignaRol');
Route::put('users/updateRol/{user}', [UserController::class,'updateRol'])->name('admin.users.updateRol');
Route::resource('users',UserController::class)->middleware('auth')->names('admin.users');

Route::resource('monedas',MonedaController::class)->middleware('auth')->names('monedas');
Route::resource('tipomenus', TipomenuController::class)->names('tipomenus');

Route::resource('empresas',EmpresaController::class)->middleware('auth')->names('admin.empresas');
Route::resource('roles', RoleController::class)->middleware('auth')->names('admin.roles');
Route::resource('sucursales', SucursaleController::class)->middleware('auth')->names('admin.sucursales');

Route::resource('cursos', CursoController::class)->middleware('auth')->names('cursos');
Route::resource('nivelcursos', NivelcursoController::class)->middleware('auth')->names('nivelcursos');

// Route::resource('tutores', TutoreController::class)->names('tutores');
Route::get('tutores', Tutores::class)->middleware('auth')->middleware('can:tutores.index')->name('tutores');
Route::get('vinculosestudiantes/{id}', Vinculosestudiantes::class)->middleware('auth')->name('vinculosestudiantes');
Route::resource('estudiantes', EstudianteController::class)->middleware('auth')->names('estudiantes');

Route::resource('catitems', CatitemController::class)->middleware('auth')->names('catitems');
Route::resource('items', ItemController::class)->middleware('auth')->names('items');

Route::resource('menus', MenuController::class)->middleware('auth')->names('menus');
Route::get('elaborarmenu/{id}/{dup}', Elaborarmenu::class)->middleware('auth')->name('elaborarmenu');
Route::get('programarmenu', Events::class)->middleware('auth')->name('programarmenu');
Route::get('events', [EventoController::class, 'events'])->middleware('auth')->name('events');
Route::get('menusemanal', Pedido::class)->middleware('auth')->name('menusemanal');
Route::resource('precios', PreciomenuController::class)->middleware('auth')->names('precios');

Route::get('resumenpedido', Resumen::class)->middleware('auth')->name('resumenpedido');
Route::get('formapago/{tipoBusqueda}', Formapago::class)->middleware('auth')->name('formapago');
Route::get('fintransc/{id}', Finish::class)->middleware('auth')->name('fintransc');

Route::get('masivos',Masivos::class)->middleware('auth')->name('masivos');

Route::get('ventas/cierrecaja',Cierrecaja::class)->middleware('can:ventas.cierrecaja')->middleware('auth')->name('ventas.cierrecaja');
Route::get('ventas/cierres',Cierrebonoreserva::class)->middleware('can:ventas.cierrecaja')->middleware('auth')->name('ventas.cierres');
Route::get('ventas/bonofecha',Bonofecha2::class)->middleware('can:ventas.bonos')->middleware('auth')->name('ventas.bonofecha');
Route::get('ventas/bonomensual',Bonomensual::class)->middleware('auth')->name('ventas.bonomensual');
Route::get('ventas/bonoanual',Bonoanual::class)->middleware('can:ventas.bonos')->middleware('auth')->name('ventas.bonoanual');
Route::get('ventas/pos/{indicador?}',Pos::class)->middleware('auth')->name('ventas.pos');
Route::get('ventas/nueva/{id}',Nueva::class)->middleware('auth')->name('ventas.nueva');
Route::get('reservas/nueva',Reservas::class)->middleware('auth')->middleware('can:reservas.nueva')->name('reservas.nueva');
Route::get('ventas/vpagos', Verificacionpedidos::class)->middleware('auth')->middleware('can:ventas.vpagos')->name('ventas.vpagos');
Route::get('ventas/appedido/{venta_id}', Aprobarpedido::class)->middleware('auth')->middleware('can:ventas.appedido')->name('ventas.appedido');
Route::resource('ventas', VentaController::class)->middleware('auth')->names('ventas');
Route::resource('ventasconfigs',VentasconfigController::class)->middleware('auth')->names('ventasconfigs');
Route::resource('feriados',FeriadoController::class)->middleware('auth')->names('feriados');

Route::get('entregas/individual/{indicador?}', Individual::class)->middleware('auth')->middleware('can:entregas.individual')->name('entregas.individual');
Route::get('entregas/porcurso', Porcurso::class)->middleware('auth')->middleware('can:entregas.porcurso')->name('entregas.porcurso');
Route::get('entregas/profesores/{indicador?}',Profesores::class)->middleware('can:entregas.profesores')->middleware('auth')->name('entregas.profesores');
Route::get('entregas/noentregados',Noentregados::class)->middleware('can:entregas.porcurso')->name('entregas.noentregados');
Route::get('entregas/estadopedidos',Estadopedidos::class)->middleware('auth')->name('entregas.estadopedidos');

Route::get('pedidos/personales/{estudiante_id}',Ppersonales::class)->middleware('auth')->name('pedidos.personales');

Route::get('reportes/cierrecaja/{id}',[ReporteController::class,'cierrecaja'])->name('reportes.cierrecaja');
Route::get('reportes/diario',Diario::class)->middleware('auth')->name('reportes.diario');
Route::get('reportes/ventas',Rventas::class)->middleware('auth')->name('reportes.ventas');
Route::get('pagos/profesores', Pagoprofesores::class)->middleware('auth')->name('pagos.profesores');

Route::get('impresiones/recibo/{data}',function(){
    return view('impresiones.recibos');
});

