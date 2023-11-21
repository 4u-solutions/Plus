<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use App\adminModels\ProductosModel;
use App\adminModels\tipoPedidoModel;
use App\adminModels\pedidosModel;
use App\adminModels\tipoWaroModel;
use App\adminModels\pedidosDetalleModel;
use App\adminModels\UserAdmin;

class meseroController extends Controller
{
  protected $redirecUlr;
  public function __construct()
  {
      $this->middleware('auth:admin');
      $this->back = 'admin/_';
  }

  public function toma_de_pedidos(){
    $data   = tipoPedidoModel::where('es_admin', 0)->where('estado', 1)->get();
    
    Config::set('extra_button', true);

    return view('admin.mesero.toma_de_pedidos', [
      'menubar' => $this->list_sidebar(),
      'data' => $data,
      'eb_data'   => (object) array(
        array(
          'feather' => 'arrow-left',
          'tooltip' => 'Regresar',
          'route'   => 'admin.mesero.pedidos',
          'params'  => []
        )
      )
    ]);
  }

  public function propinas(Request $request) {
    $model = pedidosModel::create([
      'id_tipo'    => 6,
      'id_usuario' => session('global_id_mesero'),
      'cliente'    => 'Propina',
      'fecha'      => date('Y-m-d'),
      'hora'       => date('h:i:s'),
      'id_estado'  => 3,
    ]);
    $model->save();

    return redirect('admin/pedido_detallado/' . $model->id);
  }

  public function store(Request $request) {
    $model = pedidosModel::create([
      'id_tipo'    => $_POST['id_tipo'],
      'id_usuario' => session('global_id_mesero'),
      'cliente'    => @$_POST['nombre-cliente'],
      'fecha'      => date('Y-m-d'),
      'hora'       => date('h:i:s'),
      'id_estado'  => $_POST['id_tipo'] == 1 ? 3 : ($_POST['id_tipo'] == 6 ? 3 : 2),
    ]);
    $model->save();

    if ($_POST['id_tipo'] == 1 || $_POST['id_tipo'] == 6) {
      return redirect('admin/pedido_detallado/' . $model->id);
    } elseif ($_POST['id_tipo'] == 2 || $_POST['id_tipo'] == 3) {
      return redirect('admin/agregar_productos/' . $model->id);
    }
  }

  public function pedidos(){
    $fecha_inicial = date('Y-m-d H:i:s');
    $fecha_final   = date('Y-m-d H:i:s', strtotime($fecha_inicial . ' +1 day'));
    $fecha_inicial = date('Y-m-d H:i:s', strtotime($fecha_inicial . ' -18 hours'));
    $str = "select ap.id, ap.id_tipo, atp.nombre tipo, au.name mesero, ap.cliente, atpe.nombre estado, atpe.color, ap.id_estado, ap.monto, ap.saldo, pedidos_pagados.monto pagado from admin_pedidos ap left join admin_tipo_pedido atp on (ap.id_tipo = atp.id) left join admin_users au on (ap.id_usuario = au.id) left join admin_tipo_pedido_estado atpe on (ap.id_estado = atpe.id) left join (select id_pedido, sum(monto) monto from admin_pedido_pagos where estado group by id_pedido) pedidos_pagados on (ap.id = pedidos_pagados.id_pedido) where ap.created_at between '$fecha_inicial' and '$fecha_final' and ap.id_tipo and ap.id_estado and ap.id_usuario = " . session('global_id_mesero') . " and ap.estado order by ap.id_tipo, ap.created_at desc;";
    // echo $str; exit();
    $data = DB::select($str);

    $str = "select * from admin_users where roleUS = 4 and statusUs order by name";
    $meseros = DB::select($str);
    
    Config::set('extra_button', true);

    return view('admin.mesero.pedidos', [
      'menubar' => $this->list_sidebar(),
      'data'    => $data,
      'fecha'   => date('Y-m-d'),
      'session_id_mesero' => session('global_id_mesero'),
      'meseros' => $meseros,
      'eb_data' => (object) array(
        array(
          'feather' => 'dollar-sign',
          'tooltip' => 'Descarga de efectivo',
          'route'   => 'admin.cobrador.descarga_efectivo',
          'params'  => []
        ),
        array(
          'feather' => 'plus-circle',
          'tooltip' => 'Nuevo pedido',
          'route'   => 'admin.mesero.toma_de_pedidos',
          'params'  => []
        ),
        array(
          'feather' => 'user-minus',
          'tooltip' => 'Cambiar mesero',
          'route'   => '#',
          'params'  => [],
          'attr'    => array(
            'attr'  => 'onclick',
            'value' => 'cambiarMesero();'
          )
        ),
        array(
          'feather' => 'x-square',
          'color'   => 'danger',
          'tooltip' => 'Cierre',
          'route'   => 'admin.cobrador.cierre',
          'params'  => []
        )
      )
    ]);
  }

  public function agregar_productos($id_pedido) {
    $fecha       = date('Y-m-d');
    $dia_despues = date('Y-m-d', strtotime("+1 day", strtotime($fecha)));

    $ultima_fecha_invent = @DB::select("select fecha from admin_inventario where fecha < '$fecha' order by fecha desc limit 1;")[0]->fecha;

    $ultima_fecha_compra = @DB::select("select fecha from admin_inventario_ingresos where fecha < '$fecha' order by fecha desc limit 1;")[0]->fecha;


    $fecha_inicio  = date('Y-m-d ' . config('global.horario_apertura'), strtotime($fecha));
    $fecha_final = date('Y-m-d ' . config('global.horario_cierre'), strtotime($dia_despues));
    $str = "select distinct ap.id, ap.nombre, ap.precio, ap.mixers, ap.id_tipo, atw.color, if ( ai2.cantidad_inicial is not null, if ( aii.ingreso is not null, aii.ingreso + ( if ( ai2.recarga is not null, ai2.recarga, 0 ) ), if ( ai2.recarga is not null, ai2.recarga, if ( ai.cantidad_final is not null, ai.cantidad_final, 0 ) ) ), ai2.cantidad_inicial ) - if (producto_vendido.cantidad is null, 0, producto_vendido.cantidad) stock, producto_mas_vendido.cantidad mas_vendido from admin_productos ap left join admin_tipo_waro atw on (ap.id_tipo = atw.id) left join admin_inventario ai on (ap.id = ai.id_producto and ai.fecha = '$ultima_fecha_invent') left join admin_inventario ai2 on (ap.id = ai2.id_producto and ai2.fecha = '$fecha') left join admin_inventario_ingresos aii on (ap.id = aii.id_producto and aii.fecha = '$ultima_fecha_compra') left join (select id_producto, sum(cantidad) cantidad from admin_pedidos_detalle where aprobado and estado and created_at between '$fecha " . config('global.horario_apertura') . "' and '$dia_despues " . config('global.horario_cierre') . "' group by id_producto) producto_vendido on (ap.id = producto_vendido.id_producto) left join (select id_producto, sum(cantidad) cantidad from admin_pedidos_detalle where aprobado and despachado and contable and estado group by id_producto) producto_mas_vendido on (ap.id = producto_mas_vendido.id_producto) where ap.estado and ap.id_tipo not in (8) order by mas_vendido desc;";
    $productos = DB::select($str);

    $str = "select distinct ap.id, ap.nombre, ap.precio, ap.mixers, ap.id_tipo, atw.color, if ( ai2.cantidad_inicial is not null, if ( aii.ingreso is not null, aii.ingreso + ( if ( ai2.recarga is not null, ai2.recarga, 0 ) ), if ( ai2.recarga is not null, ai2.recarga, if ( ai.cantidad_final is not null, ai.cantidad_final, 0 ) ) ), ai2.cantidad_inicial ) - if (producto_vendido.cantidad is null, 0, producto_vendido.cantidad) stock, producto_mas_vendido.cantidad mas_vendido from admin_productos ap left join admin_tipo_waro atw on (ap.id_tipo = atw.id) left join admin_inventario ai on (ap.id = ai.id_producto and ai.fecha = '$ultima_fecha_invent') left join admin_inventario ai2 on (ap.id = ai2.id_producto and ai2.fecha = '$fecha') left join admin_inventario_ingresos aii on (ap.id = aii.id_producto and aii.fecha = '$ultima_fecha_compra') left join (select id_producto, sum(cantidad) cantidad from admin_pedidos_detalle where aprobado and estado and created_at between '$fecha " . config('global.horario_apertura') . "' and '$dia_despues " . config('global.horario_cierre') . "' group by id_producto) producto_vendido on (ap.id = producto_vendido.id_producto) left join (select id_producto, sum(cantidad) cantidad from admin_pedidos_detalle where aprobado and despachado and estado group by id_producto) producto_mas_vendido on (ap.id = producto_mas_vendido.id_producto) where ap.estado and ap.id_tipo in (8) order by mas_vendido desc;";
    $mixers = DB::select($str);

    $data = [];
    foreach($productos as $key => $item) {
      $data[] = $item;
    }
    foreach($mixers as $key => $item) {
      $data[] = $item;
    }

    $str      = "select apd.id, apd.id_pedido, ap.nombre, apd.cantidad, apd.subtotal, ap.mixers, ap.id_tipo from admin_pedidos_detalle apd left join admin_productos ap on (apd.id_producto = ap.id) where apd.estado and apd.id_pedido = $id_pedido and apd.contable = 1 and apd.estado and apd.aprobado in (0) and apd.despachado in (0) order by id desc;";
    // echo $str; exit();
    $bdetalle = DB::select($str);

    $str     = "select apd.id, apd.id_pedido, ap.nombre, apd.cantidad, apd.subtotal, ap.mixers, ap.id_tipo from admin_pedidos_detalle apd left join admin_productos ap on (apd.id_producto = ap.id) where apd.estado and apd.id_pedido = $id_pedido and apd.contable = 0 and apd.estado and apd.aprobado in (0) and apd.despachado in (0) order by id;";
    $mdetalle = DB::select($str);

    $pedido = pedidosModel::where('id', $id_pedido)->get();
    if (session('global_id_mesero')) {
      $mesero = UserAdmin::where('id', session('global_id_mesero'))->get()[0];
    } else {
      $mesero = UserAdmin::where('id', Auth::id())->get()[0];
    }

    Config::set('extra_button', true);

    return view('admin.mesero.agregar_productos', [
      'menubar'   => $this->list_sidebar(),
      'data'      => $data,
      'mixers'    => $mixers,
      'id_pedido' => $id_pedido,
      'bdetalle'  => $bdetalle,
      'mdetalle'  => $mdetalle,
      'pedido'    => $pedido,
      'mesero'    => $mesero,
      'eb_data'   => (object) array(
        array(
          'feather' => 'arrow-left',
          'tooltip' => 'Regresar',
          'route'   => 'admin.mesero.pedidos',
          'params'  => []
        )
      )
    ]);
  }

  public function cargar_productos($id_pedido, $id_producto, $cantidad, $contable) {
    $fecha       = date('Y-m-d');
    $dia_despues = date('Y-m-d', strtotime("+1 day", strtotime($fecha)));

    $ultima_fecha_invent = @DB::select("select fecha from admin_inventario where fecha < '$fecha' order by fecha desc limit 1;")[0]->fecha;

    $ultima_fecha_compra = @DB::select("select fecha from admin_inventario_ingresos where fecha < '$fecha' order by fecha desc limit 1;")[0]->fecha;


    $fecha_inicio  = date('Y-m-d ' . config('global.horario_apertura'), strtotime($fecha));
    $fecha_final = date('Y-m-d ' . config('global.horario_cierre'), strtotime($dia_despues));

    $str = "select distinct ap.id, ap.nombre, if ( ai2.cantidad_inicial is not null, if ( aii.ingreso is not null, aii.ingreso + ( if ( ai2.recarga is not null, ai2.recarga, 0 ) ), if ( ai2.recarga is not null, ai2.recarga, if ( ai.cantidad_final is not null, ai.cantidad_final, 0 ) ) ), ai2.cantidad_inicial ) - if (producto_vendido.cantidad is null, 0, producto_vendido.cantidad) stock from admin_productos ap left join admin_tipo_waro atw on (ap.id_tipo = atw.id) left join admin_inventario ai on (ap.id = ai.id_producto and ai.fecha = '$ultima_fecha_invent') left join admin_inventario ai2 on (ap.id = ai2.id_producto and ai2.fecha = '$fecha') left join admin_inventario_ingresos aii on (ap.id = aii.id_producto and aii.fecha = '$ultima_fecha_compra') left join (select id_producto, sum(cantidad) cantidad from admin_pedidos_detalle where aprobado and estado and created_at between '$fecha " . config('global.horario_apertura') . "' and '$dia_despues " . config('global.horario_cierre') . "' group by id_producto) producto_vendido on (ap.id = producto_vendido.id_producto) where ap.estado and ap.id = $id_producto;";
    $stock = DB::select($str)[0];

    if ($cantidad <= $stock->stock) {
      $str     = "select * from admin_productos where id = $id_producto;";
      $prod = DB::select($str)[0];

      $prod_asoc = array();
      for ($i = 1; $i <= $cantidad; $i++) {
        $prod_asoc[] = $prod->productos_asociados;
      }
      $prod->productos_asociados = implode(',', $prod_asoc);

      $prod_cant = array();
      foreach(explode(',', $prod->productos_asociados) as $key => $value) {
        @$prod_cant[$value] += 1;
      }
      $prod_asoc = array();
      foreach($prod_cant as $key => $value) {
        $prod_asoc[] = array(
          'id'  => $key,
          'cnt' => $value
        );
      }

      $model = pedidosDetalleModel::create([
        'id_pedido'   => $id_pedido,
        'id_producto' => $id_producto,
        'cantidad'    => $cantidad,
        'subtotal'    => $prod->precio * $cantidad,
        'contable'    => $contable,
      ]);
      $guardado = $model->save() ? true : false;

      $data = array(
        'id_det'   => $model->id,
        'nombre'   => $prod->nombre,
        'mixers'   => $prod->mixers,
        'subtotal' => number_format($prod->precio * $cantidad, 2,".",","),
        'cantidad' => $cantidad,
        'guardado' => $guardado,
        'contable' => $contable,
        'id_tipo'  => $prod->id_tipo,
        'prod_asoc' => json_encode($prod_asoc),
        'error'    => false
      );
    } else {
      $data = array(
        'error' => true,
        'stock' => $stock->stock
      );
    }

    return json_encode($data);
  }

  public function borrar_productos($id_detalle) {
    DB::table('admin_pedidos_detalle')->where('id', $id_detalle)->update([
      'estado' => 0
    ]);

    return true;
  }

  public function enviar_cobro($id_pedido) {
    $pedido = pedidosModel::where('id', $id_pedido)->get()[0];

    $str = "select sum(subtotal) monto from admin_pedidos_detalle where contable and estado " . ($pedido->id_tipo != 3 ? ('and aprobado = 0 and despachado = 0') : ($pedido->id_tipo == 3 ? 'and pagado = 0' : '')) . " and id_pedido = $id_pedido;";
    $monto = DB::select($str)[0];

    // echo 'pedido->id_tipo: ' . $pedido->id_tipo . '<br>';
    // echo 'pedido->saldo: ' . $pedido->saldo . '<br>';
    // echo 'pedido->monto: ' . $pedido->monto . '<br>';
    // echo 'monto->monto: ' . $monto->monto . '<br>';
    // echo 'pedido->id_tipo: ' . $pedido->id_tipo . '<br>';
    // exit();
    
    $saldo     = $pedido->id_tipo == 2 ? 0 : ($pedido->id_tipo == 1 ? ($pedido->saldo <= 0 ? $pedido->monto - $monto->monto : $pedido->saldo - $monto->monto) : 0);
    $aprobar   = $pedido->id_tipo == 2 ? $monto->monto : ($pedido->id_tipo == 1 ? $monto->monto : 0);
    $monto     = $pedido->id_tipo == 2 ? $monto->monto : ($pedido->id_tipo == 1 ? $pedido->monto : $monto->monto);

    // echo 'monto: ' . $monto . '<br>';
    // echo 'saldo: ' . $saldo . '<br>';
    // echo 'aprobar: ' . $aprobar . '<br>';
    // echo 'monto: ' . $monto . '<br>';
    // exit();

    if ($pedido->id_tipo != 2) {
      $str = "select * from admin_pedidos_detalle where id_pedido = $id_pedido and aprobado = 0;";
      $detalle = DB::select($str);
      foreach($detalle as $item) {
        DB::table('admin_pedidos_detalle')->where('id', $item->id)->update([
          'aprobado' => 1
        ]);
      }
    }

    if (session('global_id_mesero')) {
      $mesero = UserAdmin::where('id', session('global_id_mesero'))->get()[0];
    } else {
      $mesero = UserAdmin::where('id', Auth::id())->get()[0];
    }

    if ($pedido->id_tipo == 3) {
      $str = "select sum(subtotal) monto from admin_pedidos_detalle where contable and estado and id_pedido = $id_pedido;";
      $cobro = DB::select($str)[0];

      $str = "select sum(subtotal) monto from admin_pedidos_detalle where contable and estado and pagado = 1 and contable and id_pedido = $id_pedido;";
      $por_pagar = DB::select($str)[0];
    }
    
    DB::table('admin_pedidos')->where('id', $id_pedido)->update([
      'id_estado' => $pedido->id_tipo == 2 ? 3 : 4,
      'monto'     => $pedido->id_tipo == 3 ? ($cobro->monto) : $monto,
      'saldo'     => $pedido->id_tipo == 1 ? $saldo : ($pedido->id_tipo == 2 ? 0 : ($cobro->monto - $por_pagar->monto)),
      'aprobar'   => 0
    ]);


    if ($mesero->roleUS == 10) {
      return redirect('admin/pedido_detallado/' . $id_pedido);
    } else {
      if ($pedido->id_tipo == 2) {
        return redirect('admin/pedido_detallado/' . $id_pedido);
      } else {
        return redirect('admin/pedidos/');
      }
    }
  }

  public function pedido_recibido($id_pedido) {
    $pedido = pedidosModel::where('id', $id_pedido)->get()[0];

    DB::table('admin_pedidos')->where('id', $id_pedido)->update([
      'id_estado' => $pedido->id_tipo == 1 ? ($pedido->saldo <= 0 ? 6 : 2) : 6
    ]);

    $str = "select * from admin_pedidos_detalle where id_pedido = $id_pedido and estado and aprobado = 1 and despachado = 1 and recibido = 0;";
    $detalle = DB::select($str);
    foreach($detalle as $item) {
      DB::table('admin_pedidos_detalle')->where('id', $item->id)->update([
        'recibido' => 1
      ]);
    }

    return redirect('admin/pedidos')->with('success','Guardado correctamente!');
  }

  public function balance($fecha = null){
    $mesero        = UserAdmin::where('id', Auth::id())->get()[0];    
    $fecha         = $fecha ?: date('Y-m-d');
    $fecha_inicial = date('Y-m-d ' . config('global.horario_apertura'), strtotime($fecha));
    $fecha_final   = date('Y-m-d ' . config('global.horario_cierre'), strtotime($fecha . ' +1 day'));
    $str = "select ap.id, ap.nombre, ap.precio, sum(apd.cantidad) cantidad, sum(subtotal) subtotal from admin_productos ap left join admin_pedidos_detalle apd on (ap.id = apd.id_producto and apd.estado) left join admin_pedidos ape on (apd.id_pedido = ape.id) where ap.estado and apd.created_at between '$fecha_inicial' and '$fecha_final' and ape.id_usuario = " . Auth::id() . " and apd.contable = 1 and apd.aprobado and apd.despachado group by ap.id, ap.nombre, ap.precio order by ap.id_tipo, ap.nombre;";
    // echo $str; exit();
    $balance = DB::select($str);

    $str = "select ap.id, ap.monto from admin_pedidos ap where ap.estado and ap.created_at between '$fecha_inicial' and '$fecha_final' and ap.id_usuario = " . Auth::id() . " and ap.id_tipo = 6;";
    $propinas = DB::select($str);

    $porcentaje_pago = 0.078;
    $porcentaje_propina = 0.82;

    return view('admin.mesero.balance', [
      'menubar'  => $this->list_sidebar(),
      'balance'  => $balance,
      'propinas' => $propinas,
      'fecha'    => $fecha,
      'porcentaje_pago' => $porcentaje_pago,
      'porcentaje_propina' => $porcentaje_propina,
      'mesero'   => $mesero
    ]);
  }

  public function cargar_pull($id_pedido) {
    $actualizado = DB::table('admin_pedidos')->where('id', $id_pedido)->update([
      'id_estado' => 7,
      'id_cobrador' => 0
    ]);

    return redirect('admin/pedido_detallado/' . $id_pedido);
  }

  public function cambiar_mesero($id_mesero) {
    Session::put('global_id_mesero', $id_mesero);


    return redirect('admin/pedidos');
  }
}
