<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\Controller;
use App\adminModels\pedidosPagosModel;
use App\adminModels\pedidosModel;
use App\adminModels\inventarioCierreModel;
use App\adminModels\UserAdmin;
use App\adminModels\descargasEfectivoModel;

class cobradorController extends Controller
{
  protected $redirecUlr;
  public function __construct()
  {
      $this->middleware('auth:admin');
  }

  public function pedidos_por_cobrar(){
    $fecha = date('Y-m-d');
    $fecha_final   = date('Y-m-d');
    $fecha_inicial = strtotime("-18 hour", strtotime($fecha_final));
    $fecha_inicial = date('Y-m-d', $fecha_inicial);

    $fecha_ap_inicial = date('Y-m-d H:i:s');
    $fecha_ap_final   = date('Y-m-d H:i:s', strtotime($fecha_ap_inicial . ' +1 day'));
    $fecha_ap_inicial = date('Y-m-d H:i:s', strtotime($fecha_ap_inicial . ' -18 hours'));
    $str = "select ap.id, ap.id_tipo, atp.nombre tipo, au.name mesero, ap.cliente, atpe.nombre estado, atpe.color, ap.id_estado, ap.monto, ap.saldo, ap.aprobar, admin_pedido_pagos.pagado from admin_pedidos ap left join admin_tipo_pedido atp on (ap.id_tipo = atp.id) left join admin_users au on (ap.id_usuario = au.id) left join admin_tipo_pedido_estado atpe on (ap.id_estado = atpe.id) left join (select id_pedido, sum(monto) pagado from admin_pedido_pagos where estado group by id_pedido) admin_pedido_pagos on (ap.id = admin_pedido_pagos.id_pedido) where ap.created_at between '$fecha_ap_inicial' and '$fecha_ap_final' and ap.id_tipo != 4 and ap.id_estado in (1, 3, 4, 5, 7) and (ap.id_cobrador = 0 or ap.id_cobrador = " . Auth::id() . ") and ap.estado order by ap.id_estado, ap.created_at desc;";
    // echo $str; exit();
    $data = DB::select($str);

    Config::set('extra_button', true);

    return view('admin.cobrador.pedidos', [
      'menubar' => $this->list_sidebar(),
      'data'    => $data,
      'fecha'   => $fecha,
      'eb_data'   => (object) array(
        array(
          'titulo'  => 'DEF',
          'route'   => 'admin.cobrador.descarga_efectivo',
          'params'  => []
        ),
        array(
          'titulo'  => 'CIE',
          'route'   => 'admin.cobrador.cierre',
          'params'  => []
        )
      )
    ]);
  }

  public function pedido_detallado($id_pedido) {
    $edit     = false;
    $pedido = pedidosModel::where('id', $id_pedido)->get()[0];

    if (!$pedido->id_cobrador) {
      DB::table('admin_pedidos')->where('id', $id_pedido)->update([
        'id_cobrador' => Auth::id(),
      ]);

      $bdetalle = DB::select("select apd.id, apd.id_pedido, ap.nombre, apd.cantidad, apd.subtotal, ap.mixers from admin_pedidos_detalle apd left join admin_productos ap on (apd.id_producto = ap.id) where apd.estado and apd.id_pedido = $id_pedido and apd.contable = 1 " . ($pedido->id_tipo == 1 ? ' and apd.aprobado = 0' : '') ." order by id desc;");

      $mdetalle = DB::select("select apd.id, apd.id_pedido, ap.nombre, apd.cantidad, apd.subtotal, ap.mixers from admin_pedidos_detalle apd left join admin_productos ap on (apd.id_producto = ap.id) where apd.estado and apd.id_pedido = $id_pedido and apd.contable = 0 " . ($pedido->id_tipo == 1 ? ' and apd.aprobado = 0' : '') ." order by id;");

      $pagos = pedidosPagosModel::where('id_pedido', $id_pedido)->get();
      if (count($pagos) > 0) {
        $edit = true;
      }

      return view('admin.cobrador.pedido_detallado', [
        'menubar'   => $this->list_sidebar(),
        'asignado'  => false,
        'id_pedido' => $id_pedido,
        'bdetalle'  => $bdetalle,
        'mdetalle'  => $mdetalle,
        'pagos'     => $pagos,
        'pedido'    => $pedido,
        'edit'      => $edit
      ]);
    } else {
      $str = "select admin_users.name from admin_pedidos left join admin_users on (admin_pedidos.id_cobrador = admin_users.id) where admin_users.statusUs and admin_pedidos.estado and admin_pedidos.id = $id_pedido;";
      $cobrador = DB::select($str)[0]->name;
    
      Config::set('extra_button', true);

      return view('admin.cobrador.pedido_detallado', [
        'menubar'  => $this->list_sidebar(),
        'asignado' => true,
        'cobrador' => strtoupper($cobrador),
        'eb_data'   => (object) array(
          array(
            'titulo'  => 'REGRESAR',
            'route'   => 'admin.cobrador.pedidos_por_cobrar',
            'params'  => []
          ),
          array(
            'titulo'  => 'CIE',
            'route'   => 'admin.cobrador.cierre',
            'params'  => []
          )
        )
      ]);
    }
  }

  public function enviar_pago($id_pedido) {
    $pedido = pedidosModel::where('id', $id_pedido)->get()[0];
    $total  = $pedido->id_tipo == 1 ? $pedido->monto : 0;
    $saldo  = $pedido->id_tipo == 1 ? $pedido->saldo : 0;

    $str = "select sum(subtotal) monto from admin_pedidos_detalle where contable and estado and aprobado = 0 and despachado = 0 and id_pedido = $id_pedido;";
    $pagado = DB::select($str)[0];

    // echo '$pedido->id_tipo: ' . $pedido->id_tipo . '<br>';
    // echo '$pedido->id_estado: ' . $pedido->id_estado . '<br>';
    // echo '$pagado->monto: ' . $pagado->monto . '<br>';
    // echo '$total: ' . $total . '<br>';
    // exit();

    if ($pedido->id_tipo == 1 && ($pedido->id_estado == 3 || $pedido->id_estado == 7) && $pagado->monto <= 0) {
      for ($id_tipo = 1; $id_tipo <= 2; $id_tipo++) {
        $monto = $_POST['pago-' . ($id_tipo == 1 ? 'efectivo' : 'tarjeta')];
        $model = pedidosPagosModel::create([
          'id_usuario' => Auth::id(),
          'id_pedido' => $id_pedido,
          'id_tipo'   => $id_tipo,
          'monto'     => $monto,
        ]);

        $total += $monto;
        $saldo += $monto;
        if ($model->save()) {

          DB::table('admin_pedidos')->where('id', $id_pedido)->update([
            'id_estado' => 2,
            'monto'     => $total,
            'saldo'     => $pedido->id_estado == 7 ? $saldo : $total,
            'aprobar'   => 0
          ]);
        }
      }
    } elseif ($pedido->id_tipo > 1 && $pedido->id_estado == 3 && $pagado->monto > 0) {
      $str = "select * from admin_pedidos_detalle where id_pedido = $id_pedido and estado and aprobado = 0 and despachado = 0;";
      $detalle = DB::select($str);
      foreach($detalle as $item) {
        DB::table('admin_pedidos_detalle')->where('id', $item->id)->update([
          'aprobado' => 1
        ]);
      }

      for ($id_tipo = 1; $id_tipo <= 2; $id_tipo++) {
        $monto = $_POST['pago-' . ($id_tipo == 1 ? 'efectivo' : 'tarjeta')];
        $model = pedidosPagosModel::create([
          'id_usuario' => Auth::id(),
          'id_pedido' => $id_pedido,
          'id_tipo'   => $id_tipo,
          'monto'     => $monto
        ]);

        $total += $monto;
        if ($model->save()) {
          DB::table('admin_pedidos')->where('id', $id_pedido)->update([
            'id_estado' => 4,
            'monto'     => $total,
            'saldo'     => 0,
            'aprobar'   => 0
          ]);
        }
      }
    } elseif ($pedido->id_tipo == 1 && $pedido->id_estado == 1 && $pagado->monto > 0) {
      $str = "select * from admin_pedidos_detalle where id_pedido = $id_pedido and estado and aprobado = 0 and despachado = 0;";
      $detalle = DB::select($str);
      foreach($detalle as $item) {
        DB::table('admin_pedidos_detalle')->where('id', $item->id)->update([
          'aprobado' => 1
        ]);
      }

      DB::table('admin_pedidos')->where('id', $id_pedido)->update([
        'id_estado' => 4,
        'aprobar'   => 0
      ]);
    }

    $mesero = UserAdmin::where('id', Auth::id())->get()[0];
    if ($mesero->roleUS == 3) {
      return redirect('admin/pedido_para_despachar/' . $id_pedido);
    } else {
      return redirect('admin/pedidos_por_cobrar')->with('success','Guardado correctamente!');
    }
  }

  public function cierre($fecha = null) {
    $action = @$_POST['action'];
    $fecha  = $fecha ?: (@$_POST['fecha'] ?: date('Y-m-d'));
    $cierre = inventarioCierreModel::where('fecha', $fecha)->where('id_cobrador', Auth::id())->get();

    echo $action;
    if ($action) {
      $edit   = true;

      if ($action == 1) {
        $model = inventarioCierreModel::create([
          'id_cobrador' => Auth::id(),
          'fecha'       => $fecha,
          'efectivo'    => $_POST['pago-efectivo'] ?: 0,
          'tarjeta'     => $_POST['pago-tarjeta'] ?: 0,
        ]);
        $model->save();

        $action = 2;
      } elseif ($action == 2) {
        DB::table('admin_inventario_cierre')->where('fecha', $fecha)->update([
          'efectivo' => $_POST['pago-efectivo'] ?: 0,
          'tarjeta'  => $_POST['pago-tarjeta'] ?: 0,
        ]);
      }

      return redirect('admin/cierre/' . $fecha)->with('success','Guardado correctamente!');
    } else {
      $edit   = false;
      $action = 1;
    }

    $fecha_final   = $fecha ? $fecha . ' ' . date('H:i:s') : date('Y-m-d H:i:s');
    $fecha_inicial = strtotime("-24 hour", strtotime($fecha_final));
    $fecha_inicial = date('Y-m-d H:i:s', $fecha_inicial);
    $str = "select id_tipo, sum(monto) monto from admin_pedido_pagos where estado and created_at between '$fecha_inicial' and '$fecha_final' and id_usuario = " . Auth::id() . " group by id_tipo;";
    // echo $str; exit();
    $monto = DB::select($str);

    $str = "select sum(monto) monto from admin_descargas_efectivo where fecha between '$fecha_inicial' and '$fecha_final' and id_cobrador = " . Auth::id() . ";";
    // echo $str; exit();
    $descarga_efectivo = DB::select($str)[0]->monto;

    if (count($cierre) > 0) {
      $action = 2;
    }

    Config::set('extra_button', true);

    return view('admin.cobrador.cierre', [
      'menubar' => $this->list_sidebar(),
      'monto'   => @$monto[0]->monto + @$monto[1]->monto,
      'efectivo'=> @$monto[0]->monto,
      'tarjeta' => @$monto[1]->monto,
      'descarga_efectivo' => $descarga_efectivo,
      'edit'    => $edit,
      'fecha'   => $fecha,
      'action'  => $action,
      'cierre'  => $cierre,
      'eb_data'   => (object) array(
        array(
          'titulo'  => 'DEF',
          'route'   => 'admin.cobrador.descarga_efectivo',
          'params'  => []
        ),
        array(
          'titulo'  => 'PPC',
          'route'   => 'admin.cobrador.pedidos_por_cobrar',
          'params'  => []
        )
      )
    ]);
  }

  public function descarga_efectivo($fecha = null) {
    $action = @$_POST['action'];
    $fecha  = $fecha ?: (@$_POST['fecha'] ?: date('Y-m-d'));

    if (@$_POST['action']) {
      dd($_POST);
    }
    if ($action) {
      $edit   = true;

      if ($action == 1) {
        dd($_POST['descarga-efectivo']);
        descargasEfectivoModel::where('fecha', $fecha)->where('id_cobrador', Auth::id())->delete();
        foreach($_POST['descarga-efectivo'] as $key => $value) {
          $model = descargasEfectivoModel::create([
            'id_cobrador' => Auth::id(),
            'fecha'       => $fecha,
            'monto'       => $value,
          ]);
          $model->save();
        }
      }

      return redirect('admin/descarga_efectivo/' . $fecha);
    } else {
      $edit   = false;
      $action = 1;
    }

    $fecha_final   = $fecha ? $fecha . ' ' . date('H:i:s') : date('Y-m-d H:i:s');
    $fecha_inicial = strtotime("-24 hour", strtotime($fecha_final));
    $fecha_inicial = date('Y-m-d H:i:s', $fecha_inicial);
    $str = "select id, monto from admin_descargas_efectivo where fecha between '$fecha_inicial' and '$fecha_final' and id_cobrador = " . Auth::id() . ";";
    // echo $str; exit();
    $descargas = DB::select($str);


    $action = @$_POST['action'] ?: 1;
    // echo $action; exit();

    Config::set('extra_button', true);

    return view('admin.cobrador.descarga_efectivo', [
      'menubar'   => $this->list_sidebar(),
      'descargas' => @$descargas,
      'edit'    => $edit,
      'fecha'   => $fecha,
      'action'  => $action,
      'eb_data'   => (object) array(
        array(
          'titulo'  => 'PPC',
          'route'   => 'admin.cobrador.pedidos_por_cobrar',
          'params'  => []
        ),
        array(
          'titulo'  => 'CIE',
          'route'   => 'admin.cobrador.cierre',
          'params'  => []
        )
      )
    ]);
  }

  public function editar_pedido($id_pedido) {
    DB::table('admin_pedidos')->where('id', $id_pedido)->update([
      'id_estado' => 2,
    ]);

    $str = "select * from admin_pedidos_detalle where id_pedido = $id_pedido and estado and aprobado = 1 and recibido = 0;";
    $detalle = DB::select($str);
    foreach($detalle as $item) {
      DB::table('admin_pedidos_detalle')->where('id', $item->id)->update([
        'aprobado' => 0
      ]);
    }

    DB::table('admin_pedidos')->where('id', $id_pedido)->update([
      'id_cobrador' => 0
    ]);

    return redirect('admin/pedidos_por_cobrar');
  }
}
