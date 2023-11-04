<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\Controller;
use App\adminModels\pedidosPagosModel;

class gerenciaController extends Controller
{
  protected $redirecUlr;
  public function __construct()
  {
      $this->middleware('auth:admin');
      $this->back = 'admin/_';
  }

  public function rep_ventas($fecha = null){
    $fecha_inicial = date("Y-m-d " . config('global.horario_apertura'), strtotime(@$_POST['fecha_inicial'] ?: date('Y-m-d')));
    $fecha_final   = date("Y-m-d " . config('global.horario_cierre'), strtotime((@$_POST['fecha_final'] ?: date('Y-m-d')) . ' +1 day'));

    $str = "select ap.id, ap.nombre, ap.precio, ap.costo, sum(producto_vendido.cantidad) vendido, (producto_vendido.cantidad * ap.precio) total_vendido from admin_productos ap left join (select id_producto, sum(cantidad) cantidad, admin_pedidos.id_tipo from admin_pedidos_detalle left join admin_pedidos on (admin_pedidos_detalle.id_pedido = admin_pedidos.id and admin_pedidos.id_tipo < 4) where aprobado and despachado and admin_pedidos_detalle.estado and admin_pedidos_detalle.created_at between '$fecha_inicial' and '$fecha_final' and contable group by id_producto, admin_pedidos.id_tipo) producto_vendido on (ap.id = producto_vendido.id_producto) where ap.estado and producto_vendido.id_tipo < 4 group by ap.id, ap.nombre, ap.precio order by ap.id_tipo, ap.orden;";
    // echo $str; exit();
    $inventario = DB::select($str);

    $str = "select ap.id, ap.nombre, ap.precio, producto_vendido.cantidad from admin_productos ap left join (select id_producto, sum(cantidad) cantidad, admin_pedidos.id_tipo from admin_pedidos_detalle left join admin_pedidos on (admin_pedidos_detalle.id_pedido = admin_pedidos.id and admin_pedidos.id_tipo = 4) where aprobado and despachado and admin_pedidos_detalle.estado and admin_pedidos_detalle.created_at between '$fecha_inicial' and '$fecha_final' group by id_producto, admin_pedidos.id_tipo) producto_vendido on (ap.id = producto_vendido.id_producto) where ap.estado and producto_vendido.id_tipo = 4 order by ap.id_tipo, ap.orden;";
    // echo $str; exit();
    $cortesias = DB::select($str);

    $fecha_final   = date("Y-m-d " . config('global.horario_cierre'), strtotime($fecha_final . ' -1 day'));

    return view('admin.gerencia.rep_ventas', [
      'menubar'    => $this->list_sidebar(),
      'inventario' => $inventario,
      'cortesias'  => $cortesias,
      'fecha'      => $fecha,
      'fecha_inicial'      => substr($fecha_inicial, 0, 10),
      'fecha_final'        => substr($fecha_final, 0, 10)
    ]);
  }

  public function pagos($obtner_total = false, $data = null) {
    if ($obtner_total) {
      $_POST = $data;
    }

    $fecha_inicial = date("Y-m-d " . config('global.horario_apertura'), strtotime(@$_POST['fecha_inicial'] ?: date('Y-m-d')));
    $fecha_final   = date("Y-m-d " . config('global.horario_cierre'), strtotime((@$_POST['fecha_final'] ?: date('Y-m-d')) . ' +1 day'));

    $porcentaje_pago = 0.078;
    $porcentaje_propina = 0.82;

    $str = "select substring(created_at, 1, 10) fecha from admin_pedido_pagos where estado and created_at between '$fecha_inicial' and '$fecha_final' group by fecha order by fecha;";
    // echo $str; exit();
    $fechas = DB::select($str);

    foreach($fechas as $key => $item) {
      $fechai = date('Y-m-d ' . config('global.horario_apertura'), strtotime($item->fecha));
      $fechaf   = date('Y-m-d ' . config('global.horario_cierre'), strtotime($item->fecha . ' +1 day'));
      $str = "select * from admin_pedidos where created_at between '$fechai' and '$fechaf' and estado and id_tipo <= 3;";
      $num_filas = DB::select($str);


      if (count($num_filas) <= 0) {
        foreach($fechas as $keyr => $record) {
          if ($record->fecha == $item->fecha) {
            unset($fechas[$keyr]);
            break;
          }
        }
      }
    }

    $txtDias = array(1 => 'Lunes', 2 => 'Martes', 3 => 'Miércoles', 4 => 'Jueves', 5 => 'Viernes', 6 => 'Sábado', 7 => 'Domingo');
    $arrDias = [];
    $data    = [];
    $totales = [];
    foreach($fechas as $key => $item) {
      $nSemana   = date('N', strtotime($item->fecha));
      $dMes      = date('j', strtotime($item->fecha));
      $arrDias[$dMes] = $txtDias[$nSemana];

      $fechai = date('Y-m-d ' . config('global.horario_apertura'), strtotime($item->fecha));
      $fechaf   = date('Y-m-d ' . config('global.horario_cierre'), strtotime($item->fecha . ' +1 day'));
      $str = "select au.id, au.name, au.pago_minimo, ap.id_tipo, sum(if (ap.id_tipo = 6, 0, apd.subtotal)) monto, sum(if (ap.id_tipo = 6, ap.monto, 0)) propina, alm.id id_asignacion from admin_users au left join admin_pedidos ap on (au.id = ap.id_usuario and ap.estado and ap.created_at between '$fechai' and '$fechaf') left join admin_pedidos_detalle apd on (ap.id = apd.id_pedido and apd.estado and apd.contable = 1) left join admin_lista_meseros alm on (alm.id_mesero = au.id and alm.fecha = '" . $item->fecha . "') where au.roleUS = 4 group by au.name;";
      // echo $str; exit();

      $dataTemp = [];
      foreach(DB::select($str) as $keyc => $itemc) {
        @$totales[$itemc->id] += $itemc->monto;
        $data[$itemc->id]['nombre']           = $itemc->name;
        $data[$itemc->id]['monto']            = $totales[$itemc->id];
        $data[$itemc->id]['vendio_' . $dMes]  = $itemc->monto;
        $data[$itemc->id]['propina_' . $dMes] = $itemc->propina;
        $data[$itemc->id]['lista_' . $dMes]   = $itemc->id_asignacion;
        $data[$itemc->id]['pago_minimo']      = $itemc->pago_minimo;
      }
    }

    $columnas = (count($fechas) * 2) + 2;

    $fecha_final   = date("Y-m-d " . config('global.horario_cierre'), strtotime($fecha_final . ' -1 day'));

    if (!$obtner_total) {
      return view('admin.gerencia.pagos', [
        'menubar'            => $this->list_sidebar(),
        'data'               => $data,
        'arrDias'            => $arrDias,
        'columnas'           => $columnas,
        'col_width'          => 100 / $columnas,
        'porcentaje_pago'    => $porcentaje_pago,
        'porcentaje_propina' => $porcentaje_propina,
        'fecha_inicial'      => substr($fecha_inicial, 0, 10),
        'fecha_final'        => substr($fecha_final, 0, 10)
      ]);
    } else {
      foreach ($data as $key => $item) {
        foreach ($arrDias as $keyd => $itemd) {
          $pago = (($item['vendio_' . $keyd]  * $porcentaje_pago) + $item['propina_' . $keyd]  * $porcentaje_propina);
          $pago = $pago > 0 ? ($pago > $item['pago_minimo'] ? $pago : $item['pago_minimo']) : ($item['lista_' . $keyd] ? $item['pago_minimo'] : 0) ;
          @$total += $pago;
        }
      }
      return @$total;
    }
  }

  public function resumen(){
    $fecha_inicial = date("Y-m-d " . config('global.horario_apertura'), strtotime(@$_POST['fecha_inicial'] ?: date('Y-m-d')));
    $fecha_final   = date("Y-m-d " . config('global.horario_cierre'), strtotime((@$_POST['fecha_final'] ?: date('Y-m-d')) . ' +1 day'));

    //RESUMEN VENTAS TOTALES

    $fecha  = @$fecha ?: date('Y-m-d');
    $str = "select sum(producto_vendido.cantidad * ap.costo) total_costo, sum(producto_vendido.cantidad * ap.precio) total_vendido from admin_productos ap left join (select id_producto, sum(cantidad) cantidad, admin_pedidos.id_tipo from admin_pedidos_detalle left join admin_pedidos on (admin_pedidos_detalle.id_pedido = admin_pedidos.id and admin_pedidos.id_tipo < 4) where aprobado and despachado and admin_pedidos_detalle.estado and admin_pedidos_detalle.created_at between '$fecha_inicial' and '$fecha_final' and contable group by id_producto, admin_pedidos.id_tipo) producto_vendido on (ap.id = producto_vendido.id_producto) where ap.estado and producto_vendido.id_tipo < 4;";
    // echo $str; exit();
    $ventas = DB::select($str)[0];

    //RESUMEN COMISION MESEROS

    $str = "select sum(ap.precio * producto_vendido.cantidad) monto from admin_productos ap left join (select id_producto, sum(cantidad) cantidad, admin_pedidos.id_tipo from admin_pedidos_detalle left join admin_pedidos on (admin_pedidos_detalle.id_pedido = admin_pedidos.id and admin_pedidos.id_tipo = 4) where aprobado and despachado and admin_pedidos_detalle.estado and admin_pedidos_detalle.created_at between '$fecha_inicial' and '$fecha_final' group by id_producto, admin_pedidos.id_tipo) producto_vendido on (ap.id = producto_vendido.id_producto) where ap.estado and producto_vendido.id_tipo = 4;";
    // echo $str; exit();
    $cortesias = DB::select($str)[0];

    $comix_meseros = $this->pagos(true, array(
      'fecha_inicial' => $fecha_inicial,
      'fecha_final'   => $fecha_final
    ));

    $str = "select sum(efectivo) efectivo, sum(tarjeta) tarjeta from admin_inventario_cierre where created_at between '$fecha_inicial' and '$fecha_final';";
    $pagos = DB::select($str)[0];

    $fecha_final   = date("Y-m-d " . config('global.horario_cierre'), strtotime($fecha_final . ' -1 day'));

    //RESUMEN INVENTARIO

    $fechaf = substr($fecha_final, 0, 10);
    $str = "select fecha from admin_inventario where fecha < '$fechaf' order by fecha desc limit 1;";
    $ultima_fecha_invent = @DB::select($str)[0]->fecha;
    $dia_anterior = date('Y-m-d', strtotime("-1 day", strtotime($fechaf)));
    $dia_despues  = date('Y-m-d', strtotime("+1 day", strtotime($fechaf)));

    if (strtotime(date('H:i:s')) < strtotime(config('global.horario_cierre'))) {
      $fecha_inicio  = date('Y-m-d ' . config('global.horario_apertura'), strtotime($dia_anterior));
    } else {
      $fecha_inicio  = date('Y-m-d ' . config('global.horario_apertura'), strtotime($fechaf));
    }
    $fecha_final = date('Y-m-d ' . config('global.horario_cierre'), strtotime($dia_despues));
    $str = "select sum((if (ai.cantidad_final is null, ai.cantidad_inicial + ai.recarga + if (aii.ingreso is null, 0, aii.ingreso), ai.cantidad_final + if (aii.ingreso is null, 0, aii.ingreso))) * ap.precio) total_inicial, sum(((ai2.cantidad_inicial + ai2.recarga) - if (producto_vendido.cantidad is null, 0, producto_vendido.cantidad)) * ap.precio) total_actual, sum(ai2.cantidad_final * ap.precio) total_final from admin_productos ap left join admin_inventario ai on (ap.id = ai.id_producto and ai.fecha = '$ultima_fecha_invent') left join admin_inventario_ingresos aii on (ap.id = aii.id_producto and aii.fecha between '$dia_anterior' and '$fechaf') left join admin_inventario ai2 on (ap.id = ai2.id_producto and ai2.fecha = '$fechaf') left join (select id_producto, sum(cantidad) cantidad from admin_pedidos_detalle left join admin_pedidos on (admin_pedidos_detalle.id_pedido = admin_pedidos.id and admin_pedidos.id_tipo <= 5) where aprobado and despachado and admin_pedidos_detalle.estado and admin_pedidos_detalle.created_at between '$fecha_inicio' and '$fecha_final' group by id_producto) producto_vendido on (ap.id = producto_vendido.id_producto) where ap.estado order by ap.id_tipo, ap.id;";
    // echo $str; exit();
    $inventario = DB::select($str)[0];

    $fecha_final   = date("Y-m-d " . config('global.horario_cierre'), strtotime($fecha_final . ' -1 day'));
    
    return view('admin.gerencia.resumen', [
      'menubar'       => $this->list_sidebar(),
      'ventas'        => $ventas,
      'cortesias'     => $cortesias,
      'comix_meseros' => $comix_meseros,
      'fecha'         => $fecha,
      'pagos'         => $pagos,
      'inventario'    => $inventario,
      'fecha_inicial' => substr($fecha_inicial, 0, 10),
      'fecha_final'   => substr($fecha_final, 0, 10)
    ]);
  }
}
