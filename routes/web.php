<?php

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
Auth::routes();
Route::group(['prefix' => 'admin',"namespace"=>'admin'],function() {
  Route::get('/login','Auth\AdminLoginController@showLoginForm')->name('admin.login');
  Route::post('/login', 'Auth\AdminLoginController@login')->name('admin.login.submit');
  Route::get('logout/', 'Auth\AdminLoginController@logout')->name('admin.logout');
  Route::get('/', 'dashboardController@index')->name('admin.dashboard');

  Route::resource('/permissions', 'permissionsController',["as"=>'admin']);
  Route::resource('/users', 'usersController',["as"=>'admin']);
  Route::resource('/roles', 'rolesController',["as"=>'admin']);

  Route::match(array('GET','POST','PUT'), '/acceso', 'usersController@acceso')->name('admin.users.acceso');

  Route::resource('/dashboard', 'dashboardController');
  Route::get('/dashboard', 'dashboardController@index')->name('admin.dashboard.index');

  Route::resource('/productos', 'productosController');
  Route::get('/productos', 'productosController@index')->name('admin.productos.index');

  Route::resource('/inventario', 'bodegaController');
  Route::get('/inventario', 'bodegaController@inventario')->name('admin.bodega.inventario');
  Route::match(array('GET','POST','PUT'), '/inventario/{fecha?}', 'bodegaController@actualizar_inventario');
  Route::get('/despachar', 'bodegaController@pedidos')->name('admin.bodega.despachar');
  Route::get('/pedido_para_despachar/{id?}', 'bodegaController@pedido_para_despachar')->name('pedido_para_despachar');
  Route::get('/despachar_pedido/{id_pedido?}', 'bodegaController@despachar_pedido')->name('despachar_pedido');
  Route::match(array('GET','POST','PUT'), '/ingresos/{fecha?}', 'bodegaController@ingresos')->name('admin.bodega.ingresos');
  Route::get('/recarga_inventario/{id_producto?}/{cantidad?}/{fecha?}', 'bodegaController@recarga_inventario')->name('recarga_inventario');
  Route::get('/venta_bodega', 'bodegaController@venta_bodega')->name('admin.bodega.venta_bodega');
  Route::get('/traslado/{id_pedido?}', 'bodegaController@traslado')->name('admin.bodega.traslado');
  Route::get('/enviar_traslado/{id_pedido?}', 'bodegaController@enviar_traslado')->name('enviar_traslado');
  Route::get('/cargar_traslado/{id_pedido?}/{id_producto?}/{cantidad?}', 'bodegaController@cargar_traslado')->name('cargar_traslado');

  Route::resource('/toma_de_pedidos', 'meseroController');
  Route::get('/toma_de_pedidos', 'meseroController@toma_de_pedidos')->name('admin.mesero.toma_de_pedidos');
  Route::match(array('GET','POST','PUT'), '/toma_de_pedidos/{id?}', 'meseroController@asignar_pedido')->name('asignar_pedido');
  Route::get('/pedidos/{fecha?}', 'meseroController@pedidos')->name('admin.mesero.pedidos');
  Route::get('/agregar_productos/{id?}', 'meseroController@agregar_productos')->name('agregar_productos');
  Route::get('/selecionar_botella/{id_pedido?}/{id_tipo?}', 'meseroController@selecionar_botella')->name('selecionar_botella');
  Route::get('/cargar_productos/{id_pedido?}/{id_producto?}/{cantidad?}/{max_mixers?}/{contable?}', 'meseroController@cargar_productos')->name('cargar_productos');
  Route::get('/borrar_productos/{id_detalle?}', 'meseroController@borrar_productos')->name('borrar_productos');
  Route::get('/enviar_cobro/{id_pedido?}', 'meseroController@enviar_cobro')->name('enviar_cobro');
  Route::get('/pedido_recibido/{id_pedido?}', 'meseroController@pedido_recibido')->name('pedido_recibido');
  Route::get('/balance/', 'meseroController@balance')->name('admin.mesero.balance');
  Route::get('/cargar_pull/{id_pedido?}', 'meseroController@cargar_pull')->name('cargar_pull');

  Route::get('/pedidos_por_cobrar/{fecha?}', 'cobradorController@pedidos_por_cobrar')->name('admin.cobrador.pedidos_por_cobrar');
  Route::get('/pedido_detallado/{id?}', 'cobradorController@pedido_detallado')->name('pedido_detallado');
  Route::match(array('GET','POST','PUT'), '/enviar_pago/{id_pedido?}', 'cobradorController@enviar_pago')->name('enviar_pago');
  Route::match(array('GET','POST','PUT'), '/cierre/{fecha?}', 'cobradorController@cierre')->name('admin.cobrador.cierre');
  Route::get('/editar_pedido/{id_pedido?}', 'cobradorController@editar_pedido')->name('editar_pedido');

  Route::get('/cortesias', 'encargadoController@cortesias')->name('admin.encargado.cortesias');
  Route::get('/agregar_cortesia/{id_pedido?}', 'encargadoController@agregar_cortesia')->name('admin.gerencia.agregar_cortesia');
  Route::get('/cargar_cortesia/{id_pedido?}/{id_producto?}/{cantidad?}', 'encargadoController@cargar_cortesia')->name('cargar_cortesia');
  Route::get('/borrar_cortesia/{id_detalle?}', 'encargadoController@borrar_cortesia')->name('borrar_cortesia');
  Route::get('/aprobar_cortesia/{id_pedido?}/{cliente?}', 'encargadoController@aprobar_cortesia')->name('aprobar_cortesia');
  Route::match(array('GET','POST','PUT'), '/pagos/{semana?}', 'encargadoController@pagos')->name('admin.encargado.pagos');
  Route::match(array('GET','POST','PUT'), '/asignacion/{fecha?}', 'encargadoController@asignacion')->name('admin.encargado.asignacion');
  Route::get('/asignar_mesero/{id_mesero?}/{id_cobrador?}/{columna?}/{fecha?}', 'encargadoController@asignar_mesero')->name('asignar_mesero');
  Route::get('/asignar_cobrador/{id_cobrador?}/{columna?}/{fecha?}', 'encargadoController@asignar_cobrador')->name('asignar_cobrador');
  Route::match(array('GET','POST','PUT'), '/cierre_total/{fecha?}', 'encargadoController@cierre_total')->name('admin.encargado.cierre_total');

  Route::get('/resumen/{fecha?}', 'gerenciaController@resumen')->name('admin.gerencia.resumen');
});

Route::get('/', function () {
  return redirect('/admin/login');
});
