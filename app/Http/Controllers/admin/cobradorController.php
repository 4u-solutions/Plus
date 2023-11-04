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
use App\adminModels\listaMeserosModel;

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

    return view('admin.cobrador.pedidos', [
      'menubar' => $this->list_sidebar(),
      'data'    => $data,
      'fecha'   => $fecha
    ]);
  }

  public function pedido_detallado($id_pedido) {
    $edit     = false;
    $pedido = pedidosModel::where('id', $id_pedido)->get()[0];

    $bdetalle = DB::select("select apd.id, apd.id_pedido, ap.nombre, apd.cantidad, apd.subtotal, ap.mixers, apd.pagado from admin_pedidos_detalle apd left join admin_productos ap on (apd.id_producto = ap.id) where apd.estado and apd.id_pedido = $id_pedido and apd.contable = 1 " . ($pedido->id_tipo == 1 ? ' and apd.aprobado = 0' : '') ." order by id desc;");

    $mdetalle = DB::select("select apd.id, apd.id_pedido, ap.nombre, apd.cantidad, apd.subtotal, ap.mixers, apd.pagado from admin_pedidos_detalle apd left join admin_productos ap on (apd.id_producto = ap.id) where apd.estado and apd.id_pedido = $id_pedido and apd.contable = 0 " . ($pedido->id_tipo == 1 ? ' and apd.aprobado = 0' : '') ." order by id;");

    $str = "select sum(monto) monto from admin_pedido_pagos where id_pedido = $id_pedido and estado and id_tipo = 1 union select sum(monto) monto from admin_pedido_pagos where id_pedido = $id_pedido and estado and id_tipo = 2;";
    // echo $str; exit();
    $pagos = DB::select($str);
    if (count($pagos) > 0) {
      $edit = true;
    }

    Config::set('extra_button', true);

    return view('admin.cobrador.pedido_detallado', [
      'menubar'   => $this->list_sidebar(),
      'asignado'  => false,
      'id_pedido' => $id_pedido,
      'bdetalle'  => $bdetalle,
      'mdetalle'  => $mdetalle,
      'pagos'     => $pagos,
      'pedido'    => $pedido,
      'edit'      => $edit,
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

  public function detalle_pedido($id_pedido) {
    $edit     = false;
    $pedido = pedidosModel::where('id', $id_pedido)->get()[0];

    $str = "select apd.id, apd.id_pedido, ap.nombre, apd.cantidad, apd.subtotal, ap.mixers from admin_pedidos_detalle apd left join admin_productos ap on (apd.id_producto = ap.id) where apd.estado and apd.id_pedido = $id_pedido and apd.contable = 1 order by id desc;";
    $bdetalle = DB::select($str);

    $mdetalle = DB::select("select apd.id, apd.id_pedido, ap.nombre, apd.cantidad, apd.subtotal, ap.mixers from admin_pedidos_detalle apd left join admin_productos ap on (apd.id_producto = ap.id) where apd.estado and apd.id_pedido = $id_pedido and apd.contable = 0 order by id;");

    $str = "select sum(monto) monto from admin_pedido_pagos where id_pedido = $id_pedido and estado and id_tipo = 1 union select sum(monto) monto from admin_pedido_pagos where id_pedido = $id_pedido and estado and id_tipo = 2;";
    $pagos = DB::select($str);

    return view('admin.cobrador.detalle_pedido', [
      'menubar'   => $this->list_sidebar(),
      'asignado'  => false,
      'id_pedido' => $id_pedido,
      'bdetalle'  => $bdetalle,
      'pagos'     => $pagos,
      'mdetalle'  => $mdetalle,
      'pedido'    => $pedido,
      'edit'      => $edit
    ]);
  }

  public function enviar_pago($id_pedido) {
    $pedido = pedidosModel::where('id', $id_pedido)->get()[0];
    $total  = $pedido->id_tipo == 1 ? $pedido->monto : 0;
    $saldo  = $pedido->id_tipo == 1 ? $pedido->saldo : 0;

    $str = "select sum(subtotal) monto from admin_pedidos_detalle where contable and estado and aprobado = 0 and despachado = 0 and id_pedido = $id_pedido;";
    $pagado = DB::select($str)[0];

    // echo '$pedido->id_tipo: ' .al

    if ($pedido->id_tipo == 1) {
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

    } elseif ($pedido->id_tipo ==  2) {
      for ($id_tipo = 1; $id_tipo <= 2; $id_tipo++) {
        $monto = $_POST['pago-' . ($id_tipo == 1 ? 'efectivo' : 'tarjeta')];
        $model = pedidosPagosModel::create([
          'id_usuario' => Auth::id(),
          'id_pedido' => $id_pedido,
          'id_tipo'   => $id_tipo,
          'monto'     => $monto
        ]);

        $mesero = UserAdmin::where('id', Auth::id())->get()[0];

        $total += $monto;
        if ($model->save()) {
          $str = "select * from admin_pedidos_detalle where id_pedido = $id_pedido and estado and aprobado = 0 and despachado = 0;";
          $detalle = DB::select($str);
          foreach($detalle as $item) {
            DB::table('admin_pedidos_detalle')->where('id', $item->id)->update([
              'aprobado' => 1
            ]);
          }

          DB::table('admin_pedidos')->where('id', $id_pedido)->update([
            'id_estado' => $mesero->roleUS == 10 ? ($mesero->roleUS == 10 ? 5 : 4) : 4,
            'monto'     => $total,
            'saldo'     => 0,
            'aprobar'   => 0
          ]);
        }
      }
    } elseif ($pedido->id_tipo == 3) {
      $_POST['pagado'] = @$_POST['pagado'] ?: [];
      if (count($_POST['pagado'])) {
        foreach(@$_POST['pagado'] as $key => $value) {
          DB::table('admin_pedidos_detalle')->where('id', $value)->where('pagado', 0)->update([
            'pagado' => 1
          ]);
        }
      }

      for ($id_tipo = 1; $id_tipo <= 2; $id_tipo++) {
        $monto = $_POST['pago-' . ($id_tipo == 1 ? 'efectivo' : 'tarjeta')];
        $model = pedidosPagosModel::create([
          'id_usuario' => Auth::id(),
          'id_pedido' => $id_pedido,
          'id_tipo'   => $id_tipo,
          'monto'     => $monto,
        ]);

        $total += $monto;
      }

      $str = "select sum(subtotal) monto from admin_pedidos_detalle where contable and estado and pagado = 1 and id_pedido = $id_pedido;";
      $aprobados = DB::select($str)[0];

      $str = "select sum(monto) monto from admin_pedido_pagos where id_pedido = $id_pedido and estado;";
      $pagado = DB::select($str)[0];

      // echo $pagado->monto; exit();

      DB::table('admin_pedidos')->where('id', $id_pedido)->update([
        'id_estado' => $pedido->id_estado,
        'saldo'     => $pedido->monto - $pagado->monto,
        'aprobar'   => 0
      ]);
    } elseif ($pedido->id_tipo == 6) {
      for ($id_tipo = 1; $id_tipo <= 2; $id_tipo++) {
        $monto = $_POST['pago-' . ($id_tipo == 1 ? 'efectivo' : 'tarjeta')];
        $model = pedidosPagosModel::create([
          'id_usuario' => Auth::id(),
          'id_pedido' => $id_pedido,
          'id_tipo'   => $id_tipo,
          'monto'     => $monto,
        ]);

        $total += $monto;
      }

      DB::table('admin_pedidos')->where('id', $id_pedido)->update([
        'id_estado' => 5,
        'monto'     => $monto,
      ]);
    }

    $mesero = UserAdmin::where('id', Auth::id())->get()[0];
    if ($mesero->roleUS == 10 || $mesero->roleUS == 12) {
      return redirect('admin/despachar/');
    } else {
      return redirect('admin/pedidos');
    }
  }

  public function cierre($fecha = null) {
    $action = @$_POST['action'];
    $fecha  = $fecha ?: (@$_POST['fecha'] ?: date('Y-m-d'));
    $cierre = inventarioCierreModel::where('fecha', $fecha)->where('id_cobrador', Auth::id())->get();

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

    return view('admin.cobrador.cierre', [
      'menubar' => $this->list_sidebar(),
      'monto'   => @$monto[0]->monto + @$monto[1]->monto,
      'efectivo'=> @$monto[0]->monto,
      'tarjeta' => @$monto[1]->monto,
      'descarga_efectivo' => $descarga_efectivo,
      'edit'    => $edit,
      'fecha'   => $fecha,
      'action'  => $action,
      'cierre'  => $cierre
    ]);
  }

  public function descarga_efectivo() {
    $action = @$_POST['action'];
    $fecha  = @$_POST['fecha'] ?: date('Y-m-d');

    // dd($_POST);
    if ($action) {
      $edit   = true;

      if ($action == 1) {
        // dd($_POST['descarga-efectivo']);
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

      return redirect('admin/descarga_efectivo/');
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

    return view('admin.cobrador.descarga_efectivo', [
      'menubar'   => $this->list_sidebar(),
      'descargas' => @$descargas,
      'edit'    => $edit,
      'fecha'   => $fecha,
      'action'  => $action
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

  public function borrar_orden($id_pedido) {
    DB::table('admin_pedidos_detalle')->where('id_pedido', $id_pedido)->update([
      'estado' => 0
    ]);

    DB::table('admin_pedido_pagos')->where('id_pedido', $id_pedido)->update([
      'estado' => 0
    ]);

    DB::table('admin_pedidos')->where('id', $id_pedido)->update([
      'estado' => 0
    ]);

    return redirect('admin/pedidos');
  }

  public function lista_meseros($fecha = null) {
    $fecha = $fecha ?: (@$_POST['fecha'] ?: date('Y-m-d'));
    $edit  = false;

    if (@$_POST['_token']) {
      $actualizado = DB::table('admin_lista_meseros')->where('fecha', $_POST['fecha'])->delete();

      listaMeserosModel::where('fecha', $fecha)->delete();
      foreach($_POST['meseros'] as $key => $value) {
        $model = listaMeserosModel::create([
          'id_mesero' => $value,
          'fecha'       => $fecha
        ])->save();
      }
    }

    $str = "select admin_users.*, admin_lista_meseros.id id_asignacion from admin_users left join admin_lista_meseros on (admin_users.id = admin_lista_meseros.id_mesero and fecha = '$fecha') where roleUS = 4 and statusUS order by name;";
    $meseros = DB::select($str);

    return view('admin.cobrador.lista_meseros', [
      'menubar'       => $this->list_sidebar(),
      'meseros'       => $meseros,
      'fecha'         => $fecha,
      'edit'          => $edit
    ]);
  }
}
