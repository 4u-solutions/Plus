<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
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
          'titulo'  => 'REGRESAR',
          'route'   => 'admin.mesero.pedidos',
          'params'  => []
        )
      )
    ]);
  }

  public function store(Request $request) {
    $model = pedidosModel::create([
      'id_tipo'    => $_POST['id_tipo'],
      'id_usuario' => Auth::id(),
      'cliente'    => $_POST['nombre-cliente'],
      'fecha'      => date('Y-m-d'),
      'hora'       => date('h:i:s'),
      'id_estado'  => $_POST['id_tipo'] == 2 ? 2 : 1,
    ]);
    $model->save();

    return redirect($_POST['id_tipo'] == 1 ? '/admin/pedidos' : 'admin/agregar_productos/' . $model->id)->with('success','Guardado correctamente!');
  }

  public function pedidos(){
    $fecha_inicial = date('Y-m-d 12:00:00');
    $fecha_final   = date('Y-m-d 06:00:00', strtotime($fecha_inicial . ' +1 day'));
    $str = "select ap.id, ap.id_tipo, atp.nombre tipo, au.name mesero, ap.cliente, atpe.nombre estado, atpe.color, ap.id_estado, ap.monto, ap.saldo from admin_pedidos ap left join admin_tipo_pedido atp on (ap.id_tipo = atp.id) left join admin_users au on (ap.id_usuario = au.id) left join admin_tipo_pedido_estado atpe on (ap.id_estado = atpe.id) where ap.created_at between '$fecha_inicial' and '$fecha_final' and ap.id_tipo in (1,2,3) and ap.id_estado not in (6) and ap.id_usuario = " . Auth::id() . " order by ap.id_tipo, ap.created_at desc;";
    // echo $str; exit();
    $data = DB::select($str);
    
    Config::set('extra_button', true);

    return view('admin.mesero.pedidos', [
      'menubar' => $this->list_sidebar(),
      'data'    => $data,
      'fecha'   => date('Y-m-d'),
      'eb_data'   => (object) array(
        array(
          'titulo'  => 'NUEVO PEDIDO',
          'route'   => 'admin.mesero.toma_de_pedidos',
          'params'  => []
        )
      )
    ]);
  }

  public function agregar_productos($id_pedido) {
    $fecha       = date('Y-m-d');
    $dia_despues = date('Y-m-d', strtotime("+1 day", strtotime($fecha)));
    $dia_antes   = date('Y-m-d', strtotime("-1 day", strtotime($fecha)));
    $str = "select distinct ap.id, ap.nombre, ap.precio, ap.mixers, ap.id_tipo, atw.color, (ai.cantidad_inicial + ai.recarga) - if (producto_vendido.cantidad is null, 0, producto_vendido.cantidad) stock,  producto_mas_vendido.cantidad mas_vendido from admin_productos ap left join admin_tipo_waro atw on (ap.id_tipo = atw.id) left join admin_inventario ai on (ap.id = ai.id_producto and ai.created_at between '$dia_antes 18:00:00' and '$dia_despues 06:00:00') left join (select id_producto, sum(cantidad) cantidad from admin_pedidos_detalle where aprobado and despachado and estado and created_at between '$fecha 18:00:00' and '$dia_despues 06:00:00' group by id_producto) producto_vendido on (ap.id = producto_vendido.id_producto) left join (select id_producto, sum(cantidad) cantidad from admin_pedidos_detalle where aprobado and despachado and estado group by id_producto) producto_mas_vendido on (ap.id = producto_mas_vendido.id_producto) where ap.estado and ap.id_tipo <> 8 order by mas_vendido desc;";
    // echo $str; exit();
    $productos = DB::select($str);

    $str = "select distinct ap.id, ap.nombre, ap.precio, ap.mixers, ap.id_tipo, atw.color, (ai.cantidad_inicial + ai.recarga) - if (producto_vendido.cantidad is null, 0, producto_vendido.cantidad) stock,  producto_mas_vendido.cantidad mas_vendido from admin_productos ap left join admin_tipo_waro atw on (ap.id_tipo = atw.id) left join admin_inventario ai on (ap.id = ai.id_producto and ai.created_at between '$dia_antes 18:00:00' and '$dia_despues 06:00:00') left join (select id_producto, sum(cantidad) cantidad from admin_pedidos_detalle where aprobado and despachado and estado and created_at between '$fecha 18:00:00' and '$dia_despues 06:00:00' group by id_producto) producto_vendido on (ap.id = producto_vendido.id_producto) left join (select id_producto, sum(cantidad) cantidad from admin_pedidos_detalle where aprobado and despachado and estado group by id_producto) producto_mas_vendido on (ap.id = producto_mas_vendido.id_producto) where ap.estado and ap.id_tipo = 8 order by mas_vendido desc;";
    $mixers = DB::select($str);

    $data = [];
    foreach($productos as $key => $item) {
      $data[] = $item;
    }
    foreach($mixers as $key => $item) {
      $data[] = $item;
    }

    $str      = "select apd.id, apd.id_pedido, ap.nombre, apd.cantidad, apd.subtotal, ap.mixers, ap.id_tipo from admin_pedidos_detalle apd left join admin_productos ap on (apd.id_producto = ap.id) where apd.estado and apd.id_pedido = $id_pedido and apd.contable = 1 and apd.estado and apd.despachado in (1, 0) order by id desc;";
    // echo $str; exit();
    $bdetalle = DB::select($str);

    $str     = "select apd.id, apd.id_pedido, ap.nombre, apd.cantidad, apd.subtotal, ap.mixers, ap.id_tipo from admin_pedidos_detalle apd left join admin_productos ap on (apd.id_producto = ap.id) where apd.estado and apd.id_pedido = $id_pedido and apd.contable = 0 and apd.estado and apd.despachado in (1, 0) order by id;";
    $mdetalle = DB::select($str);

    $pedido = pedidosModel::where('id', $id_pedido)->get();
    $mesero = UserAdmin::where('id', Auth::id())->get()[0];

    Config::set('extra_button', true);

    return view('admin.mesero.agregar_productos', [
      'menubar'   => $this->list_sidebar(),
      'data'      => $data,
      'id_pedido' => $id_pedido,
      'bdetalle'  => $bdetalle,
      'mdetalle'  => $mdetalle,
      'pedido'    => $pedido,
      'mesero'    => $mesero,
      'eb_data'   => (object) array(
        array(
          'titulo'  => 'REGRESAR',
          'route'   => 'admin.mesero.pedidos',
          'params'  => []
        )
      )
    ]);
  }

  public function selecionar_botella($id_pedido, $id_tipo) {
    $data = productosModel::where('id_tipo', $id_tipo)->get();
    $tipo = tipoWaroModel::where('id', $id_tipo)->get();

    return view('admin.mesero.selecionar_botella', [
      'menubar'   => $this->list_sidebar(),
      'data'      => $data,
      'id_pedido' => $id_pedido,
      'tipo'      => $tipo
    ]);
  }

  public function cargar_productos($id_pedido, $id_producto, $cantidad, $max_mixers, $contable) {
    $str     = "select * from admin_productos where id = $id_producto;";
    $prod = DB::select($str);
    // $prod = ProductosModel::where('id', $id_producto)->get();

    $model = pedidosDetalleModel::create([
      'id_pedido'   => $id_pedido,
      'id_producto' => $id_producto,
      'cantidad'    => $cantidad,
      'subtotal'    => $prod[0]->precio * $cantidad,
      'contable'    => $contable,
    ]);
    $guardado = $model->save() ? true : false;

    $data = array(
      'id_det'   => $model->id,
      'nombre'   => $prod[0]->nombre,
      'mixers'   => $prod[0]->mixers,
      'subtotal' => number_format($prod[0]->precio * $cantidad, 2,".",","),
      'cantidad' => $cantidad,
      'guardado' => $guardado,
      'contable' => $contable,
      'id_tipo'  => $prod[0]->id_tipo
    );

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

    $str = "select sum(subtotal) monto from admin_pedidos_detalle where contable and estado and aprobado = 0 and despachado = 0 and id_pedido = $id_pedido;";
    $monto = DB::select($str)[0];

    // echo 'pedido->id_tipo: ' . $pedido->id_tipo . '<br>';
    // echo 'pedido->saldo: ' . $pedido->saldo . '<br>';
    // echo 'monto->monto: ' . $monto->monto . '<br>';
    // echo 'pedido->id_tipo: ' . $pedido->id_tipo . '<br>';
    // exit();
    
    $saldo   = $pedido->id_tipo == 2 ? 0 : ($pedido->id_tipo == 1 ? ($pedido->saldo <= 0 ? $pedido->monto - $monto->monto : $pedido->saldo - $monto->monto) : 0);
    $aprobar = $pedido->id_tipo == 2 ? $monto->monto : ($pedido->id_tipo == 1 ? $monto->monto : 0);
    $monto   = $pedido->id_tipo == 2 ? $monto->monto : ($pedido->id_tipo == 1 ? $pedido->monto : 0);

    // echo 'monto: ' . $monto . '<br>';
    // echo 'saldo: ' . $saldo . '<br>';
    // echo 'aprobar: ' . $aprobar . '<br>';
    // exit();

    $mesero = UserAdmin::where('id', Auth::id())->get()[0];
    
    DB::table('admin_pedidos')->where('id', $id_pedido)->update([
      'id_estado' => $pedido->id_tipo == 2 ? 3 : 1,
      'monto'     => $monto,
      'saldo'     => $saldo,
      'aprobar'   => $aprobar
    ]);

    if ($mesero->roleUS == 3) {
      return redirect('admin/pedido_detallado/' . $id_pedido);
    } else {
      return redirect('admin/pedidos');
    }
  }

  public function pedido_recibido($id_pedido) {
    $pedido = pedidosModel::where('id', $id_pedido)->get()[0];

    DB::table('admin_pedidos')->where('id', $id_pedido)->update([
      'id_estado' => $pedido->id_tipo == 1 ? ($pedido->saldo <= 0 ? 6 : 2) : 6
    ]);

    return redirect('admin/pedidos')->with('success','Guardado correctamente!');
  }

  public function balance(){
    $mesero        = UserAdmin::where('id', Auth::id())->get()[0];
    $fecha_final   = date('Y-m-d');
    $fecha_inicial = strtotime("-18 hour", strtotime($fecha_final));
    $fecha_inicial = date('Y-m-d', $fecha_inicial);

    
    $fecha_inicial = date('Y-m-d 12:00:00');
    $fecha_final   = date('Y-m-d 06:00:00', strtotime($fecha_inicial . ' +1 day'));
    $str = "select ap.id, ap.nombre, ap.precio, sum(apd.cantidad) cantidad, sum(subtotal) subtotal from admin_productos ap left join admin_pedidos_detalle apd on (ap.id = apd.id_producto and apd.estado) left join admin_pedidos ape on (apd.id_pedido = ape.id) where ap.estado and apd.created_at between '$fecha_inicial 12:00:00' and '$fecha_final 06:00:00' and ape.id_usuario = " . Auth::id() . " and apd.contable = 1 and apd.aprobado and apd.despachado group by ap.id, ap.nombre, ap.precio order by ap.id_tipo, ap.nombre;";
    // echo $str; exit();
    $balance = DB::select($str);

    $porcentaje_pago = $mesero->roleUS == 3 ? 0 : 7.8;

    return view('admin.mesero.balance', [
      'menubar' => $this->list_sidebar(),
      'balance' => $balance,
      'porcentaje_pago' => $porcentaje_pago,
      'mesero'  => $mesero
    ]);
  }

  public function cargar_pull($id_pedido) {
    $actualizado = DB::table('admin_pedidos')->where('id', $id_pedido)->update([
      'id_estado' => 7
    ]);

    return redirect('admin/pedidos')->with('success','Guardado correctamente!');
  }
}
