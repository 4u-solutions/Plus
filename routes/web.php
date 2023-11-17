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
Auth::routes(['exclude' => ['informacion_para_evento'] ]);

Route::group(['prefix' => 'admin',"namespace"=>'admin'],function() {
  Route::get('/login','Auth\AdminLoginController@showLoginForm')->name('admin.login');
  Route::post('/login', 'Auth\AdminLoginController@login')->name('admin.login.submit');
  Route::get('logout/', 'Auth\AdminLoginController@logout')->name('admin.logout');
  Route::get('/', 'reservasController@lista_invitados')->name('lista_invitados');

  Route::resource('/permissions', 'permissionsController',["as"=>'admin']);
  Route::resource('/users', 'usersController',["as"=>'admin']);
  Route::resource('/roles', 'rolesController',["as"=>'admin']);

  Route::match(array('GET','POST','PUT'), '/acceso', 'usersController@acceso')->name('admin.users.acceso');

  // *---- RUTAS PARA POS BODEGA ----*

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
    Route::match(array('GET','POST','PUT'), '/propinas/{id?}', 'meseroController@propinas')->name('admin.mesero.propinas');
    Route::get('/pedidos/{fecha?}', 'meseroController@pedidos')->name('admin.mesero.pedidos');
    Route::get('/agregar_productos/{id?}', 'meseroController@agregar_productos')->name('agregar_productos');
    Route::get('/selecionar_botella/{id_pedido?}/{id_tipo?}', 'meseroController@selecionar_botella')->name('selecionar_botella');
    Route::get('/cargar_productos/{id_pedido?}/{id_producto?}/{cantidad?}/{contable?}', 'meseroController@cargar_productos')->name('cargar_productos');
    Route::get('/borrar_productos/{id_detalle?}', 'meseroController@borrar_productos')->name('borrar_productos');
    Route::get('/enviar_cobro/{id_pedido?}', 'meseroController@enviar_cobro')->name('enviar_cobro');
    Route::get('/pedido_recibido/{id_pedido?}', 'meseroController@pedido_recibido')->name('pedido_recibido');
    Route::get('/cargar_pull/{id_pedido?}', 'meseroController@cargar_pull')->name('cargar_pull');
    Route::get('/balance/{fecha?}', 'meseroController@balance')->name('admin.mesero.balance');
    Route::get('/cambiar_mesero/{id_mesero?}', 'meseroController@cambiar_mesero')->name('cambiar_mesero');

    Route::get('/pedidos_por_cobrar/{fecha?}', 'cobradorController@pedidos_por_cobrar')->name('admin.cobrador.pedidos_por_cobrar');
    Route::get('/pedido_detallado/{id?}', 'cobradorController@pedido_detallado')->name('pedido_detallado');
    Route::match(array('GET','POST','PUT'), '/enviar_pago/{id_pedido?}/{params?}', 'cobradorController@enviar_pago')->name('enviar_pago');
    Route::match(array('GET','POST','PUT'), '/cierre/{fecha?}', 'cobradorController@cierre')->name('admin.cobrador.cierre');
    Route::get('/editar_pedido/{id_pedido?}', 'cobradorController@editar_pedido')->name('editar_pedido');
    Route::match(array('GET','POST','PUT'), '/descarga_efectivo', 'cobradorController@descarga_efectivo')->name('admin.cobrador.descarga_efectivo');
    Route::get('/borrar_orden/{id_pedido}', 'cobradorController@borrar_orden')->name('borrar_orden');
    Route::get('/detalle_pedido/{id?}', 'cobradorController@detalle_pedido')->name('detalle_pedido');
    Route::match(array('GET','POST','PUT'), '/lista_meseros/{fecha?}', 'cobradorController@lista_meseros')->name('admin.cobrador.meseros');
    Route::get('/resumen-c/{fecha?}', 'cobradorController@resumen')->name('admin.cobrador.resumen');

    Route::get('/resumen-c/{fecha?}', 'cobradorController@resumen')->name('admin.cobrador.resumen');

    Route::get('/cortesias', 'encargadoController@cortesias')->name('admin.encargado.cortesias');
    Route::get('/agregar_cortesia/{id_pedido?}', 'encargadoController@agregar_cortesia')->name('admin.gerencia.agregar_cortesia');
    Route::get('/cargar_cortesia/{id_pedido?}/{id_producto?}/{cantidad?}', 'encargadoController@cargar_cortesia')->name('cargar_cortesia');
    Route::get('/borrar_cortesia/{id_detalle?}', 'encargadoController@borrar_cortesia')->name('borrar_cortesia');
    Route::get('/aprobar_cortesia/{id_pedido?}/{cliente?}', 'encargadoController@aprobar_cortesia')->name('aprobar_cortesia');
    Route::match(array('GET','POST','PUT'), '/asignacion/{fecha?}', 'encargadoController@asignacion')->name('admin.encargado.asignacion');
    Route::get('/asignar_mesero/{id_mesero?}/{id_cobrador?}/{columna?}/{fecha?}', 'encargadoController@asignar_mesero')->name('asignar_mesero');
    Route::get('/asignar_cobrador/{id_cobrador?}/{columna?}/{fecha?}', 'encargadoController@asignar_cobrador')->name('asignar_cobrador');
    Route::match(array('GET','POST','PUT'), '/cierre_total/{fecha?}', 'encargadoController@cierre_total')->name('admin.encargado.cierre_total');

    Route::match(array('GET','POST','PUT'), '/resumen-g', 'gerenciaController@resumen')->name('admin.gerencia.resumen');
    Route::match(array('GET','POST','PUT'), '/reporte-ventas', 'gerenciaController@rep_ventas')->name('admin.gerencia.rep_ventas');
    Route::match(array('GET','POST','PUT'), '/pagos', 'gerenciaController@pagos')->name('admin.gerencia.pagos');
  
  // *---- RUTAS PARA POS BODEGA ----*

  // *---- RUTAS PARA RESERVAS ----*
    Route::get('/mesas/{id_evento?}', 'reservasController@mesas')->name('admin.reservas.mesas');
    Route::match(array('GET','POST','PUT'), '/agregar_mesa/{id_evento?}/{id?}', 'reservasController@agregar_mesa')->name('agregar_mesa');
    Route::get('/eventos/{fecha?}', 'reservasController@eventos')->name('admin.reservas.eventos');
    Route::match(array('GET','POST','PUT'), '/agregar_evento/{id?}', 'reservasController@agregar_evento')->name('agregar_evento');
    Route::get('/lista_invitados/{id_mesa?}', 'reservasController@lista_invitados')->name('admin.reservas.lista_invitados');
    Route::get('/agregar_invitado/{id_mesa?}/{nombre?}/{sexo?}/{file?}/{accion?}/{id?}', 'reservasController@agregar_invitado')->name('agregar_invitado');
    Route::get('/detalle_invitados/{id_evento?}/{id_mesa?}/{filtro?}', 'reservasController@detalle_invitados')->name('detalle_invitados');

    Route::get('/borrar_invitado/{id_invitado?}', 'reservasController@borrar_invitado')->name('borrar_invitado');
    Route::get('/pago_invitado/{id?}/{pagado?}', 'reservasController@pago_invitado')->name('pago_invitado');
    Route::get('/es_menor/{id?}/{es_menor?}', 'reservasController@es_menor')->name('es_menor');
    Route::get('/activar_invitado/{id?}/{estado?}', 'reservasController@activar_invitado')->name('activar_invitado');
    Route::get('/pull_pagado/{id?}/{pull_pagado?}', 'reservasController@pull_pagado')->name('pull_pagado');
    Route::get('/invitado_info/{id?}', 'reservasController@invitado_info')->name('invitado_info');
    Route::get('/lider_info', 'reservasController@lider_info')->name('lider_info');
    Route::get('/lider_actualizado/{id?}/{nombre?}/{correo?}/{telefono?}/{fecha_nacimiento?}', 'reservasController@lider_actualizado')->name('lider_actualizado');
    Route::get('/invitado_actualizado/{id?}/{nombre?}/{correo?}/{telefono?}/{fnacimiento?}', 'reservasController@invitado_actualizado')->name('invitado_actualizado');
    Route::get('/cambio_invitado/{id?}/{id_invitado?}/{id_mesa?}', 'reservasController@cambio_invitado')->name('cambio_invitado');
    Route::get('/cargar_invitados/{id?}/{id_mesa?}', 'reservasController@cargar_invitados')->name('cargar_invitados');

    Route::get('/lideres/{id?}', 'reservasController@lideres')->name('admin.reservas.lideres');
    Route::match(array('GET','POST','PUT'), '/agregar_lider/{id?}/{nombre?}/{sexo?}/{mayor?}', 'reservasController@agregar_lider')->name('agregar_lider');
    Route::get('/agregar_lider_a_mesa/{id_mesa?}/{id_lider?}/{accion?}', 'reservasController@agregar_lider_a_mesa')->name('agregar_lider_a_mesa');
    Route::get('/borrar_lider_de_mesa/{id_lider?}/{id_mesa?}', 'reservasController@borrar_lider_de_mesa')->name('borrar_lider_de_mesa');
    Route::get('/borrar_invitado_de_mesa/{id?}', 'reservasController@borrar_invitado_de_mesa')->name('borrar_invitado_de_mesa');
    Route::match(array('GET','POST','PUT'), '/todos_los_invitados/{id_evento?}', 'reservasController@todos_los_invitados')->name('todos_los_invitados');
    Route::get('/mantenerInvitado/{id_invitado?}', 'reservasController@mantenerInvitado')->name('mantenerInvitado');
    Route::get('/cerrar_lista/{id_mesa?}/{accion?}', 'reservasController@cerrar_lista')->name('cerrar_lista');
    
    Route::get('/informacion_para_evento/{id_mesa?}', 'shareController@informacion_para_evento')->name('informacion_para_evento');
    Route::get('/actualizar_mesa/{id?}/{cantidad?}/{evento?}/{pull?}/{id_pull?}', 'reservasController@actualizar_mesa')->name('actualizar_mesa');

    Route::get('/lista_eventos/{vacio?}', 'reservasController@lista_eventos')->name('admin.reservas.lista_eventos');
    Route::get('/colaboradores', 'reservasController@colaboradores')->name('admin.reservas.colaboradores');
    Route::match(array('GET','POST','PUT'), '/agregar_colaborador/{id?}', 'reservasController@agregar_colaborador')->name('agregar_colaborador');


    Route::get('/venues/{id?}', 'reservasController@venues')->name('admin.reservas.venues');
    Route::match(array('GET','POST','PUT'), '/agregar_venue/{id?}', 'reservasController@agregar_venue')->name('agregar_venue');
    Route::get('/agregar_venue_ubicacion/{id_area?}/{id_tipo?}/{max?}/{nombre?}', 'reservasController@agregar_venue_ubicacion')->name('agregar_venue_ubicacion');
    Route::get('/borrar_area_de_venue/{id?}', 'reservasController@borrar_area_de_venue')->name('borrar_area_de_venue');

    Route::get('/reportes', 'reservasController@reportes')->name('admin.reservas.reporte');
    Route::get('/reporte_estadisticas/{id_evento?}', 'reservasController@reporte_estadisticas')->name('reporte_estadisticas');
    Route::get('/reporte_por_pax/{id_evento?}/{reporte?}', 'reservasController@reporte_por_pax')->name('reporte_por_pax');

    Route::get('/asignar_mesa_lider/{id_mesa?}/{id_sector?}/{no_mesa?}', 'reservasController@asignar_mesa_lider')->name('asignar_mesa_lider');
    Route::get('/cargar_mesas_asignadas/{id_mesa?}', 'reservasController@cargar_mesas_asignadas')->name('cargar_mesas_asignadas');
    Route::get('/borrar_mesas_asignadas/{id?}', 'reservasController@borrar_mesas_asignadas')->name('borrar_mesas_asignadas');
    Route::get('/borrar_reservacion/{id_mesa?}', 'reservasController@borrar_reservacion')->name('borrar_reservacion');

    Route::get('/control_de_ingreso', 'reservasController@control_de_ingreso')->name('admin.checkpoint.ingreso');
    Route::get('/info_control_de_ingreso', 'reservasController@info_control_de_ingreso')->name('info_control_de_ingreso');
    Route::get('/ingreso_sin_lista/{id_evento?}', 'reservasController@ingreso_sin_lista')->name('ingreso_sin_lista');

    Route::get('/marcar_ingreso/{id_invitado?}/{valor?}', 'reservasController@marcar_ingreso')->name('admin.reservas.ingresos');
    
    Route::get('/auto_complete_ingreso/{valor_busqueda?}', 'reservasController@auto_complete_ingreso')->name('auto_complete_ingreso');
  
  Route::get('/acreditaciones/{filtro?}/{valor?}', 'reservasController@acreditaciones')->name('acreditaciones');
  Route::get('/marcar_equipo_usado/{id_colaborador?}/{valor?}/{campo?}', 'reservasController@marcar_equipo_usado')->name('marcar_equipo_usado');

    Route::get('/reporte_pull/{id_evento?}', 'reservasController@reporte_pull')->name('reporte_pull');
    Route::get('/reporte_meseros_sin_asignar/{id_evento?}', 'reservasController@reporte_meseros_sin_asignar')->name('reporte_meseros_sin_asignar');
  // *---- RUTAS PARA RESERVAS ----*

  // *---- RUTAS SIN INICIAR SESIÓN ----*
    Route::get('/informacion_invitado/{id?}', 'shareController@informacion_invitado')->name('informacion_invitado');
    Route::get('/invitado_perfil_actualizado/{id?}/{nombre?}/{correo?}/{telefono?}/{fecha_nacimiento?}', 'shareController@invitado_perfil_actualizado')->name('invitado_perfil_actualizado');

  Route::get('/cargar_formulario_pago/{id_invitado?}', 'shareController@cargar_formulario_pago')->name('cargar_formulario_pago');
  Route::post('/emitir_pago', 'shareController@emitir_pago')->name('emitir_pago');
  Route::get('/enviar_pago_powertranz', 'shareController@enviar_pago_powertranz')->name('enviar_pago_powertranz');
  
  Route::get('/click_patrocinio/{id_patrocinio?}/{id_lider?}/{id_invitado?}', 'shareController@click_patrocinio')->name('click_patrocinio');

  Route::get('/unificar_invitados', 'shareController@unificar_invitados')->name('unificar_invitados');
  Route::get('/eliminar_duplicados', 'shareController@eliminar_duplicados')->name('eliminar_duplicados');
  // *---- RUTAS SIN INICIAR SESIÓN ----*


  Route::get('/limpiar-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    Cache::store("file")->flush();
    return redirect('admin/lista_invitados');
  });

  Route::get('/limpiar-vista', function() {
    \Artisan::call('view:clear');
    return redirect('admin/lista_invitados');
  });

  Route::get('/optimize', function() {
    \Artisan::call('optimize:clear');
    return redirect('admin/lista_invitados');
  });
});

Route::get('/', function () {
  return redirect('/admin/login');
});
