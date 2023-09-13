<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\adminModels\pedidosPagosModel;

class gerenciaController extends Controller
{
  protected $redirecUlr;
  public function __construct()
  {
      $this->middleware('auth:admin');
      $this->back = 'admin/_';
  }

  public function resumen($fecha = null){
    $fecha        = $fecha ?: date('Y-m-d');
    $dia_despues  = date('Y-m-d', strtotime("+1 day", strtotime($fecha)));
      // ((ai.cantidad_inicial + ai.recarga) - ai.cantidad_final) bodega 
    $str = "select ap.id, ap.nombre, ap.precio, sum(producto_vendido.cantidad) meseros, 
    0 bodega
      from admin_productos ap left join admin_inventario ai on (ap.id = ai.id_producto and ai.fecha = '$fecha') left join (select id_producto, sum(cantidad) cantidad, admin_pedidos.id_tipo from admin_pedidos_detalle left join admin_pedidos on (admin_pedidos_detalle.id_pedido = admin_pedidos.id and admin_pedidos.id_tipo < 4) where aprobado and despachado and admin_pedidos_detalle.estado and admin_pedidos_detalle.created_at between '$fecha 18:00:00' and '$dia_despues 06:00:00' and contable group by id_producto, admin_pedidos.id_tipo) producto_vendido on (ap.id = producto_vendido.id_producto) where ap.estado and producto_vendido.id_tipo < 4 group by ap.id, ap.nombre, ap.precio order by ap.id_tipo, ap.id;";
    // echo $str; exit();
    $inventario = DB::select($str);

    $str = "select ap.id, ap.nombre, ap.precio, producto_vendido.cantidad from admin_productos ap left join (select id_producto, sum(cantidad) cantidad, admin_pedidos.id_tipo from admin_pedidos_detalle left join admin_pedidos on (admin_pedidos_detalle.id_pedido = admin_pedidos.id and admin_pedidos.id_tipo = 4) where aprobado and despachado and admin_pedidos_detalle.estado and admin_pedidos_detalle.created_at between '$fecha 18:00:00' and '$dia_despues 06:00:00' group by id_producto, admin_pedidos.id_tipo) producto_vendido on (ap.id = producto_vendido.id_producto) where ap.estado and producto_vendido.id_tipo = 4 order by ap.id_tipo, ap.id;";
    // echo $str; exit();
    $cortesias = DB::select($str);

    $str = "select sum(efectivo) efectivo, sum(tarjeta) tarjeta from admin_inventario_cierre where created_at between '$fecha 18:00:00' and '$dia_despues 06:00:00';";
    $pagos = DB::select($str)[0];

    return view('admin.gerencia.resumen', [
      'menubar'    => $this->list_sidebar(),
      'inventario' => $inventario,
      'cortesias'  => $cortesias,
      'fecha'      => $fecha,
      'pagos'      => $pagos
    ]);
  }
}
