<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });



#*************** LANDING ****************#
#RUTAS LANDING
Route::get('/', 'IndexController@index');
Route::get('/inicio', 'IndexController@index');
Route::get('/departamentos', 'IndexController@departamentos');


Route::get('/contacto', 'IndexController@contacto');
Route::post('/contacto', 'IndexController@contacto_form');
#RESERVAS NUEVA
Route::get('/departamentos/{id}/reservar', 'ReservaController@reserva')->name('reserva');
Route::post('/departamentos/{id}/reservar', 'ReservaController@storeReserva');
#RESERVAS EN CURSO
Route::get('/reserva/{id}', 'ReservaController@reservaProcesoEdit');
Route::get('/reserva/{id}/volver', 'ReservaController@volverPasoReserva');

Route::post('/reserva/{id}/paso-uno', 'ReservaController@reservaProcesoUpdatePaso1');
Route::post('/reserva/{id}/paso-dos', 'ReservaController@reservaProcesoUpdatePaso2');
Route::post('/reserva/{id}/paso-tres', 'ReservaController@reservaProcesoUpdatePaso3');
Route::get('/reserva/{id}/ver-detalle', 'ReservaController@reservaProcesoPaso4');

Route::get('/reserva/{id}/download-pdf', 'ReservaController@downloadPdf');

Route::post('/reservas/cancelar', 'ReservaController@cancelarReserva');



#*************** CLIENTE ****************#
Route::get('/cambio-contrasena', 'AuthController@changePassword');
Route::post('/cambio-contrasena', 'AuthController@changePassword');

#RUTAS PARA CLIENTE
Route::get('/login', 'LoginController@login')->name('login');
Route::post('/login', 'LoginController@login');
Route::get('/registro', 'RegisterController@registro');
Route::post('/registro', 'RegisterController@registro');
#*************** PERFIL CLIENTE ****************#
Route::get('/gestion-reservas/mi-perfil', 'cliente\PerfilClienteController@miPerfil');
Route::post('/gestion-reservas/mi-perfil', 'cliente\PerfilClienteController@miPerfilUpdate');
Route::post('/gestion-reservas/mi-perfil/eliminar-cuenta', 'cliente\PerfilClienteController@deleteAccount');

#*************** PERFIL CLIENTE - CAMBIAR CONTRASEÑA ****************#
Route::get('/gestion-reservas/mi-perfil/cambiar-contrasena', 'cliente\PerfilClienteController@changePassword');
Route::post('/gestion-reservas/mi-perfil/cambiar-contrasena', 'cliente\PerfilClienteController@changePasswordUpdate');

#*************** GESTIÓN RESERVAS ****************#
Route::get('/gestion-reservas', 'cliente\DashboardClienteController@index');
Route::get('/gestion-reservas/reservas', 'cliente\DashboardClienteController@listado');
Route::get('/gestion-reservas/reservas/{id}/confirmar', 'cliente\DashboardClienteController@confirmar_reserva');

Route::get('/gestion-reservas/huespedes', 'cliente\HuespedClienteController@index');
Route::get('/gestion-reservas/reservas/{id}/asignar-huespedes', 'cliente\HuespedClienteController@asignar_huespedesCreate');
Route::post('/gestion-reservas/huespedes/eliminar', 'cliente\HuespedClienteController@deleted');
Route::get('/gestion-reservas/reservas/obtener-huesped', 'cliente\HuespedClienteController@getHuesped');
Route::post('/gestion-reservas/reservas/asignar-huesped', 'cliente\HuespedClienteController@store');
Route::post('/gestion-reservas/huespedes/quitar-huesped', 'cliente\HuespedClienteController@remove');

Route::post('/gestion-reservas/reservas/cancelar', 'cliente\DashboardClienteController@cancelarReserva');
Route::get('/gestion-reservas/reservas/{id}/check_in', 'cliente\DashboardClienteController@generarCheckIn');
Route::post('/gestion-reservas/reservas/{id}/check_in', 'cliente\DashboardClienteController@generarCheckIn');
Route::post('/gestion-reservas/reservas/{id}/pago_check_in', 'cliente\DashboardClienteController@generarCheckInPago');
Route::get('/gestion-reservas/reservas/{id}/servicios-extras', 'cliente\DashboardClienteController@createServices');
Route::post('/gestion-reservas/reservas/{id}/servicios-extras', 'cliente\DashboardClienteController@storeServices');


#*************** ADMIN ****************#
#RUTAS PARA ADMIN
Route::get('/login-admin', 'LoginController@login_admin');
Route::post('/login-admin', 'LoginController@login_admin');
#*************** PERFIL ADMIN ****************#
Route::get('/dashboard/mi-perfil', 'admin\PerfilController@miPerfil');
Route::post('/dashboard/mi-perfil', 'admin\PerfilController@miPerfilUpdate');
#*************** PERFIL ADMIN - CAMBIAR CONTRASEÑA ****************#
Route::get('/dashboard/mi-perfil/cambiar-contrasena', 'admin\PerfilController@changePassword');
Route::post('/dashboard/mi-perfil/cambiar-contrasena', 'admin\PerfilController@changePasswordUpdate');
#DASHBOARD ADMIN
Route::get('/dashboard', 'admin\DashboardController@index');
Route::get('/dashboard/obtener-comunas', 'admin\DashboardController@getComunas');

Route::get('/dashboard/reservas', 'admin\ReservaController@index');
Route::get('/dashboard/reservas/{id}/download-pdf', 'admin\ReservaController@downloadPdf');
Route::post('/dashboard/reservas/cancelar', 'admin\ReservaController@cancelarReserva');


Route::get('/dashboard/reservas/{id}/check_in', 'admin\ReservaController@generarCheckIn');
Route::post('/dashboard/reservas/{id}/check_in', 'admin\ReservaController@generarCheckIn');
Route::post('/dashboard/reservas/{id}/pago_check_in', 'admin\ReservaController@generarCheckInPago');


Route::get('/dashboard/reservas/{id}/check_out', 'admin\ReservaController@generarCheckOut');
Route::post('/dashboard/reservas/{id}/check_out', 'admin\ReservaController@generarCheckOutPost');






Route::get('/dashboard/clientes', 'admin\ClienteController@index');
Route::get('/dashboard/clientes/{id}/activar', 'admin\ClienteController@activar');
Route::get('/dashboard/clientes/{id}/desactivar', 'admin\ClienteController@desactivar');
Route::post('/dashboard/clientes/eliminar', 'admin\ClienteController@deleted');



Route::group(['middleware' => ['isAdmin']], function () {
    ########################## MODULOS DE MANTENEDORES #################################
    #*************** DEPARTAMENTOS ****************#
    #MODULO DE SUCURSALES
    Route::get('/dashboard/sucursales', 'admin\SucursalController@index');
    Route::get('/dashboard/sucursales/nueva', 'admin\SucursalController@create'); # -> trae Formulario para luego POST
    Route::post('/dashboard/sucursales/nueva', 'admin\SucursalController@store'); # -> envio Formulario 
    Route::get('/dashboard/sucursales/{id}/editar', 'admin\SucursalController@edit');
    Route::post('/dashboard/sucursales/{id}/editar', 'admin\SucursalController@update');
    // Route::get('/dashboard/sucursales/{id}/eliminar', 'admin\SucursalController@delete_sucursal');
    Route::post('/dashboard/sucursales/eliminar', 'admin\SucursalController@deleted');

    #MODULO DE ACCESORIOS DEPARTAMENTO
    Route::get('/dashboard/accesorios', 'admin\AccesorioController@index');
    Route::get('/dashboard/accesorios/nuevo', 'admin\AccesorioController@create');
    Route::post('/dashboard/accesorios/nuevo', 'admin\AccesorioController@store');
    Route::get('/dashboard/accesorios/{id}/editar', 'admin\AccesorioController@edit');
    Route::post('/dashboard/accesorios/{id}/editar', 'admin\AccesorioController@update');
    // Route::get('/dashboard/accesorios/{id}/eliminar', 'admin\AccesorioController@delete_accesorio');
    Route::post('/dashboard/accesorios/eliminar', 'admin\AccesorioController@deleted');

    #MODULO DE DEPARTAMENTOS
    Route::get('/dashboard/departamentos', 'admin\DepartamentoController@index');
    Route::get('/dashboard/departamentos/nuevo', 'admin\DepartamentoController@create'); # -> trae Formulario para luego POST
    Route::post('/dashboard/departamentos/nuevo', 'admin\DepartamentoController@store'); # -> envio Formulario 
    Route::get('/dashboard/departamentos/{id}/editar', 'admin\DepartamentoController@edit');
    Route::post('/dashboard/departamentos/{id}/editar', 'admin\DepartamentoController@update');
    Route::get('/dashboard/departamentos/{id}/asignar-accesorios', 'admin\DepartamentoController@editAccesorios');
    Route::post('/dashboard/departamentos/{id}/asignar-accesorios', 'admin\DepartamentoController@updateAccesorios');
    // Route::get('/dashboard/departamentos/{id}/eliminar', 'admin\DepartamentoController@delete_departamento');
    Route::post('/dashboard/departamentos/eliminar', 'admin\DepartamentoController@deleted');


    #*************** MULTAS ****************#

    #MODULO DE TIPO DE MULTAS
    Route::get('/dashboard/tipo-multas', 'admin\TipoMultaController@index');
    Route::get('/dashboard/tipo-multas/nuevo', 'admin\TipoMultaController@create'); # -> trae Formulario para luego POST
    Route::post('/dashboard/tipo-multas/nuevo', 'admin\TipoMultaController@store'); # -> envio Formulario 
    Route::get('/dashboard/tipo-multas/{id}/editar', 'admin\TipoMultaController@edit');
    Route::post('/dashboard/tipo-multas/{id}/editar', 'admin\TipoMultaController@update');
    // Route::get('/dashboard/tipo-multas/{id}/eliminar', 'admin\TipoMultaController@delete_tipo_multa');
    Route::post('/dashboard/tipo-multas/eliminar', 'admin\TipoMultaController@deleted');


    #MODULO DE MULTAS
    Route::get('/dashboard/multas', 'admin\MultaController@index');
    Route::get('/dashboard/multas/nueva', 'admin\MultaController@create'); # -> trae Formulario para luego POST
    Route::post('/dashboard/multas/nueva', 'admin\MultaController@store'); # -> envio Formulario 
    Route::get('/dashboard/multas/{id}/editar', 'admin\MultaController@edit');
    Route::post('/dashboard/multas/{id}/editar', 'admin\MultaController@update');
    // Route::get('/dashboard/multas/{id}/eliminar', 'admin\MultaController@delete_multa');
    Route::post('/dashboard/multas/eliminar', 'admin\MultaController@deleted');

    #*************** SERVICIOS ****************#
    #MODULO DE TIPO DE SERVICIOS
    Route::group(['middleware' => ['isRoot']], function () {
        Route::get('/dashboard/tipo-servicios',  'admin\TipoServicioController@index');
        Route::get('/dashboard/tipo-servicios/nuevo', 'admin\TipoServicioController@create'); # -> trae Formulario para luego POST
        Route::post('/dashboard/tipo-servicios/nuevo', 'admin\TipoServicioController@store'); # -> envio Formulario 
        Route::get('/dashboard/tipo-servicios/{id}/editar', 'admin\TipoServicioController@edit');
        Route::post('/dashboard/tipo-servicios/{id}/editar', 'admin\TipoServicioController@update');
        // Route::get('/dashboard/tipo-servicios/{id}/eliminar', 'admin\TipoServicioController@delete_tipo_servicio');
        Route::post('/dashboard/tipo-servicios/eliminar', 'admin\TipoServicioController@deleted');
    });
    Route::get('/dashboard/tipo-servicios/obtener-tipo-servicio', 'admin\TipoServicioController@getTipoServicio');


    #MODULO DE SERVICIOS
    Route::get('/dashboard/servicios', 'admin\ServicioController@index');
    Route::get('/dashboard/servicios/nuevo', 'admin\ServicioController@create'); # -> trae Formulario para luego POST
    Route::post('/dashboard/servicios/nuevo', 'admin\ServicioController@store'); # -> envio Formulario 
    Route::get('/dashboard/servicios/{id}/editar', 'admin\ServicioController@edit');
    Route::post('/dashboard/servicios/{id}/editar', 'admin\ServicioController@update');
    // Route::get('/dashboard/servicios/{id}/eliminar', 'admin\ServicioController@delete_servicio');
    Route::post('/dashboard/servicios/eliminar', 'admin\ServicioController@deleted');
    #MODULO DE TRANSPORTISTAS
    Route::get('/dashboard/transportistas', 'admin\TransportistaController@index');
    Route::get('/dashboard/transportistas/nuevo', 'admin\TransportistaController@create'); # -> trae Formulario para luego POST
    Route::post('/dashboard/transportistas/nuevo', 'admin\TransportistaController@store'); # -> envio Formulario 
    Route::get('/dashboard/transportistas/{id}/editar', 'admin\TransportistaController@edit');
    Route::post('/dashboard/transportistas/{id}/editar', 'admin\TransportistaController@update');
    // Route::get('/dashboard/transportistas/{id}/eliminar', 'admin\TransportistaController@delete_transportista');
    Route::post('/dashboard/transportistas/eliminar', 'admin\TransportistaController@deleted');


    ########################## MODULOS DE ADMINISTRADOR #################################
    #MODULO DE ROLES
    Route::get('/dashboard/roles', 'admin\RolController@index');
    Route::get('/dashboard/roles/nuevo', 'admin\RolController@create');
    Route::post('/dashboard/roles/nuevo', 'admin\RolController@store');
    Route::get('/dashboard/roles/{id}/editar', 'admin\RolController@edit');
    Route::post('/dashboard/roles/{id}/editar', 'admin\RolController@update');
    Route::get('/dashboard/roles/{id}/eliminar', 'admin\RolController@delete_rol');

    #MODULO DE CARGOS EMPLEADOS
    Route::get('/dashboard/cargos', 'admin\CargoController@index');
    Route::get('/dashboard/cargos/nuevo', 'admin\CargoController@create');
    Route::post('/dashboard/cargos/nuevo', 'admin\CargoController@store');
    Route::get('/dashboard/cargos/{id}/editar', 'admin\CargoController@edit');
    Route::post('/dashboard/cargos/{id}/editar', 'admin\CargoController@update');
    // Route::get('/dashboard/cargos/{id}/eliminar', 'admin\CargoController@delete_cargo');
    Route::post('/dashboard/cargos/eliminar', 'admin\CargoController@deleted');

    #MODULO DE EMPLEADOS
    Route::get('/dashboard/empleados', 'admin\EmpleadoController@index');
    Route::get('/dashboard/empleados/nuevo', 'admin\EmpleadoController@create');
    Route::post('/dashboard/empleados/nuevo', 'admin\EmpleadoController@store');
    Route::get('/dashboard/empleados/{id}/editar', 'admin\EmpleadoController@edit');
    Route::post('/dashboard/empleados/{id}/editar', 'admin\EmpleadoController@update');
    // Route::get('/dashboard/empleados/{id}/eliminar', 'admin\EmpleadoController@delete_empleado');
    Route::post('/dashboard/empleados/eliminar', 'admin\EmpleadoController@deleted');
});

#RUTAS GENERALES
Route::get('/logout', 'LoginController@logout');

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
});
