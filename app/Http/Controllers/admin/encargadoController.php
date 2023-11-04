<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\Controller;
use App\adminModels\tipoPedidoModel;
use App\adminModels\pedidosModel;
use App\adminModels\ProductosModel;
use App\adminModels\tipoWaroModel;
use App\adminModels\pedidosDetalleModel;
use App\adminModels\parametrosModel;
use App\adminModels\UserAdmin;
use App\adminModels\asignacionModel;
use App\adminModels\inventarioCierreModel;

class encargadoController extends Controller
{
  protected $redirecUlr;
  public function __construct()
  {
      $this->middleware('auth:admin');
      $this->back = 'admin/_';
  }

  public function cortesias(){
    // return redirect('admin/agregar_cortesia/0');

    $str = "select ap.id, ap.id_tipo, atp.nombre tipo, au.name mesero, ap.cliente, atpe.nombre estado, ap.id_estado, ap.monto, ap.saldo from admin_pedidos ap left join admin_tipo_pedido atp on (ap.id_tipo = atp.id) left join admin_users au on (ap.id_usuario = au.id) left join admin_tipo_pedido_estado atpe on (ap.id_estado = atpe.id) where ap.created_at >= (NOW() - INTERVAL 24 HOUR) and ap.id_tipo = 4 and ap.id_usuario = " . Auth::guard('admin')->id() . " order by ap.created_at;";
    $data = DB::select($str);

    return view('admin.encargado.cortesias', [
      'menubar' => $this->list_sidebar(),
      'data'    => $data,
      'fecha'   => date('Y-m-d')
    ]);
  }

  public function agregar_cortesia($id_pedido = 0) {
    if (strtotime(config('global.horario_cierre')) > strtotime(date('H:i:s'))) {
      $fecha = date('Y-m-d', strtotime("-1 day", strtotime(date('Y-m-d'))));
    } else {
      $fecha = date('Y-m-d');
    }

    $dia_despues = date('Y-m-d', strtotime("+1 day", strtotime($fecha)));
    $str = "select distinct ap.id, ap.nombre, ap.precio, ap.mixers, ap.id_tipo, atw.color, (if (ai.cantidad_inicial is null, 0, ai.cantidad_inicial) + if (ai.recarga is null, 0, ai.recarga)) - if (producto_vendido.cantidad is null, 0, producto_vendido.cantidad) stock,  producto_mas_vendido.cantidad mas_vendido from admin_productos ap left join admin_tipo_waro atw on (ap.id_tipo = atw.id) left join admin_inventario ai on (ap.id = ai.id_producto and ai.fecha = '$fecha') left join (select id_producto, sum(cantidad) cantidad from admin_pedidos_detalle where aprobado and despachado and estado and created_at between '$fecha " . config('global.horario_apertura') . "' and '$dia_despues " . config('global.horario_cierre') . "' group by id_producto) producto_vendido on (ap.id = producto_vendido.id_producto) left join (select id_producto, sum(cantidad) cantidad from admin_pedidos_detalle where aprobado and despachado and estado group by id_producto) producto_mas_vendido on (ap.id = producto_mas_vendido.id_producto) where ap.estado and ap.id_tipo <> 8 order by mas_vendido desc;";
    $productos = DB::select($str);

    $str = "select distinct ap.id, ap.nombre, ap.precio, ap.mixers, ap.id_tipo, atw.color, (if (ai.cantidad_inicial is null, 0, ai.cantidad_inicial) + if (ai.recarga is null, 0, ai.recarga)) - if (producto_vendido.cantidad is null, 0, producto_vendido.cantidad) stock,  producto_mas_vendido.cantidad mas_vendido from admin_productos ap left join admin_tipo_waro atw on (ap.id_tipo = atw.id) left join admin_inventario ai on (ap.id = ai.id_producto and ai.fecha = '$fecha') left join (select id_producto, sum(cantidad) cantidad from admin_pedidos_detalle where aprobado and despachado and estado and created_at between '$fecha " . config('global.horario_apertura') . "' and '$dia_despues " . config('global.horario_cierre') . "' group by id_producto) producto_vendido on (ap.id = producto_vendido.id_producto) left join (select id_producto, sum(cantidad) cantidad from admin_pedidos_detalle where aprobado and despachado and estado group by id_producto) producto_mas_vendido on (ap.id = producto_mas_vendido.id_producto) where ap.estado and ap.id_tipo = 8 order by mas_vendido desc;";
    $mixers = DB::select($str);

    $data = [];
    foreach($productos as $key => $item) {
      $data[] = $item;
    }
    foreach($mixers as $key => $item) {
      $data[] = $item;
    }

    $detalle = DB::select("select apd.id, apd.id_pedido, ap.nombre, apd.cantidad, apd.subtotal, ap.mixers from admin_pedidos_detalle apd left join admin_productos ap on (apd.id_producto = ap.id) where apd.estado and apd.id_pedido = '$id_pedido' and apd.contable in (2) and apd.estado and apd.aprobado = 0 and apd.despachado = 0 order by id desc;");

    $pedido = pedidosModel::where('id', $id_pedido)->get();

    Config::set('extra_button', true);

    return view('admin.encargado.agregar_cortesias', [
      'menubar'   => $this->list_sidebar(),
      'data'      => $data,
      'id_pedido' => $id_pedido,
      'detalle'   => $detalle,
      'pedido'    => $pedido,
      'eb_data'   => (object) array(
        array(
          'feather' => 'arrow-left',
          'tooltip' => 'Regresar',
          'route'   => 'admin.encargado.cortesias',
          'params'  => []
        )
      )
    ]);
  }

  public function cargar_cortesia($id_pedido, $id_producto, $cantidad) {
    if (!$id_pedido) {
      $pedido = pedidosModel::create([
        'id_tipo'    => 4,
        'id_usuario' => Auth::id(),
        'fecha'      => date('Y-m-d'),
        'hora'       => date('h:i:s'),
        'id_estado'  => 2,
      ]);
      $pedido->save();
      $id_pedido = $pedido->id;
    }
    $str = "select * from admin_productos where id = $id_producto;";
    $prod = DB::select($str);

    $model = pedidosDetalleModel::create([
      'id_pedido'   => $id_pedido,
      'id_producto' => $id_producto,
      'cantidad'    => $cantidad,
      'subtotal'    => $prod[0]->precio * $cantidad,
      'contable'    => 0
    ]);
    $guardado = $model->save() ? true : false;

    $data = array(
      'id_det'   => $model->id,
      'nombre'   => $prod[0]->nombre,
      'subtotal' => number_format($prod[0]->precio * $cantidad, 2,".",","),
      'cantidad' => $cantidad,
      'guardado' => $guardado,
      'id_pedido'   => $id_pedido
    );

    return json_encode($data);
  }

  public function aprobar_cortesia($id_pedido, $cliente) {
    $pedido = pedidosModel::where('id', $id_pedido)->get()[0];
    $str = "select * from admin_pedidos_detalle where id_pedido = $id_pedido and estado and aprobado = 0 and despachado = 0;";
    $detalle = DB::select($str);
    foreach($detalle as $item) {
        DB::table('admin_pedidos_detalle')->where('id', $item->id)->update([
          'aprobado'   => 1,
          'despachado' => 1
        ]);
    }

    DB::table('admin_pedidos')->where('id', $id_pedido)->update([
      'id_estado' => 6,
      'cliente'    => $cliente
    ]);

    $mesero = UserAdmin::where('id', Auth::id())->get()[0];

    if ($mesero->roleUS == 10) {
      return redirect('admin/despachar');
    } else {
      return redirect('admin/cortesias');
    }
  }

  public function pedido_recibido($id_pedido) {
    DB::table('admin_pedidos')->where('id', $id_pedido)->update([
      'id_estado' => 6
    ]);

    return redirect('admin/pedidos');
  }

  public function asignar_mesero($id_mesero, $id_cobrador, $columna, $fecha) {
    $model = asignacionModel::create([
      'id_cobrador' => $id_cobrador,
      'id_mesero'   => $id_mesero,
      'columna'     => $columna,
      'fecha'       => $fecha
    ]);

    $mesero = UserAdmin::where('id', $id_mesero)->get()[0];
    $data = array(
      'nombre' => $mesero->name,
      'id'     => $mesero->id
    );

    return json_encode($data);
  }


  public function asignar_cobrador($id_cobrador, $columna, $fecha) {
    DB::table('admin_asignacion')->where('columna', $columna)->where('fecha', $fecha)->update([
      'id_cobrador' => $id_cobrador
    ]);

    return true;
  }

  public function cierre_total($fecha = null) {
    $fecha  = $fecha ?: (@$_POST['fecha'] ?: date('Y-m-d'));
    $edit   = true;

    if (@$_POST['action']) {
      DB::table('admin_inventario_cierre')->where('fecha', $fecha)->where('id_cobrador', $_POST['id_cobrador'])->update([
        'aprobado' => 1
      ]);

      return redirect('admin/cierre_total/' . $fecha)->with('success','Guardado correctamente!');
    }

    if (strtotime(config('global.horario_apertura')) < strtotime(date('H:i:s'))) {
      $fecha_actual = $fecha;
    } else {
      $fecha_actual   = date('Y-m-d', strtotime("-1 day", strtotime($fecha)));
    }
    $dia_despues = date('Y-m-d', strtotime("+1 day", strtotime($fecha)));
    $str = "select aic.id, au.id id_cobrador, au.name, aic.efectivo, aic.tarjeta, total_cobrado.monto, aic.fecha, aic.aprobado from admin_inventario_cierre aic left join admin_users au on (aic.id_cobrador = au.id) left join (select id_cobrador, sum(efectivo) + sum(tarjeta) monto, fecha from admin_inventario_cierre group by id_cobrador, fecha) total_cobrado on (aic.id_cobrador = total_cobrado.id_cobrador and aic.fecha = total_cobrado.fecha) where aic.created_at between '$fecha_actual " . config('global.horario_apertura') . "' and '$dia_despues " . config('global.horario_cierre') . "' and au.statusUs;";
    // echo $str; exit();
    $cierres = DB::select($str);

    return view('admin.encargado.cierre_total', [
      'menubar' => $this->list_sidebar(),
      'edit'    => $edit,
      'fecha'   => $fecha,
      'cierres' => $cierres
    ]);
  }
}
