<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\Controller;
use App\adminModels\eventosModel;
use App\adminModels\eventosMesasModel;
use App\adminModels\eventosMesasInvitadosModel;
use App\adminModels\UserAdmin;
use App\adminModels\eventosMesasLideresModel;
use App\adminModels\eventosMesasPagosModel;
use App\adminModels\eventosMesasVenuesModel;
use App\adminModels\eventosMesasVenuesUbicaciones;
use App\adminModels\eventosVenuesSectoresMesasModel;
use App\adminModels\eventosMesasInvitadosSinListaModel;
use App\Http\Controllers\exports\reportePorPax;
use Excel;

class reservasController extends Controller
{
  protected $redirecUlr;
  public function __construct()
  {
      $this->middleware('auth:admin');
      $this->middleware('auth')->except(['informacion_para_evento']);
      $this->middleware('auth:admin')->except(['informacion_para_evento']);
      $this->back = 'admin/_';
  }

  public function venues(){
    $str  = "select admin_eventos_venues.* from admin_eventos_venues where estado order by nombre;";
    $data = DB::select($str);

    return view('admin.reservas.venues', [
      'menubar' => $this->list_sidebar(),
      'data'    => $data
    ]);
  }

  public function agregar_venue($id = null){
    if (!$id) {
      $venue = eventosMesasVenuesModel::create([
        'estado'    => 0
      ]);
      $venue->save();
      $id = $venue->id;
    }

    $str  = "select * from admin_eventos_venues where id = '$id';";
    $data = @DB::select($str)[0];

    if (@$_POST['_token']) {
      DB::table('admin_eventos_venues')->where('id', $id)->update(
        $data = [
          'nombre'    => $_POST['nombre'],
          'link_waze' => $_POST['link_waze'],
          'max_pax'   => $_POST['max_pax'],
          'estado'    => 1
        ]
      );

      return redirect('admin/venues');
      exit();
    }

    $str  = "select * from admin_eventos_venues_ubicaciones where id_venue = '$id' and estado;";
    $venues_ubicaciones = @DB::select($str);

    return view('admin.reservas.agregar_venue', [
      'menubar' => $this->list_sidebar(),
      'data'    => $data,
      'venues_ubicaciones' => $venues_ubicaciones
    ]);
  }

  public function agregar_venue_ubicacion($id_venue, $id_tipo, $max, $nombre){
    $venue = eventosMesasVenuesUbicaciones::create([
      "id_venue"      => $id_venue,
      "id_tipo"       => $id_tipo,
      "pax_porcent"   => $id_tipo == 1 ? 70 : 30,
      "max_ubaciones" => $max,
      "nombre" => $nombre
    ]);
    $venue->save();
    $id = $venue->id;

    return json_encode(
      array(
        'nombre' => $nombre,
        'id' => $id
      )
    );
  }

  public function borrar_area_de_venue($id = null){
    DB::table('admin_eventos_venues_ubicaciones')->where('id', $id)->update([
      'estado' => 0
    ]);

    return json_encode(
      array(
        'id' => $id
      )
    );
  }

  public function eventos($fecha = null){
    $fecha = $fecha ?: date('Y-m');
    $mes   = substr($fecha, strpos($fecha, '-') + 1);
    $anio  = substr($fecha, 0, 4);

    $str  = "select admin_eventos.*, mesas.mesas, barras.barras from admin_eventos left join (select count(id) mesas, id_evento from admin_eventos_mesas where estado and id_area = 1 group by id_evento ) mesas on (mesas.id_evento = admin_eventos.id and admin_eventos.estado) left join (select count(id) barras, id_evento from admin_eventos_mesas where estado and id_area = 2 group by id_evento ) barras on (barras.id_evento = admin_eventos.id and admin_eventos.estado) where admin_eventos.fecha like '%$anio-$mes%' order by fecha;";
    $data = DB::select($str);
    $edit = false;
    $action = false;

    return view('admin.reservas.eventos', [
      'menubar' => $this->list_sidebar(),
      'data'    => $data,
      'edit'    => $edit,
      'action'  => $action,
      'fecha'     => $fecha
    ]);
  }

  public function agregar_evento($id = null){
    if ($id) {
      $str  = "select * from admin_eventos where id = $id;";
      $data = DB::select($str)[0];
      $edit = true;
    } else {
      $data = [];
      $edit = false;
    }

    $str  = "select * from admin_eventos_venues where estado;";
    $venues = DB::select($str);

    if (@$_POST['_token']) {
      if ($id) {
        DB::table('admin_eventos')->where('id', $id)->update([
          'nombre' => $_POST['nombre'],
          'fecha'  => $_POST['fecha'],
          'pagado'  => @$_POST['pagado'] ?: 0
        ]);
      } else {
        $evento = eventosModel::create([
          'nombre' => $_POST['nombre'],
          'fecha'  => $_POST['fecha'],
          'pagado'  => @$_POST['pagado'] ?: 0,
          'link_waze'  => $_POST['link_waze']
        ]);
        $evento->save();
      }

      $target_path = substr(getcwd(), 0, strrpos(getcwd(), '/')) . '/';

      if (@$_FILES['fondo']) {
        $upload_path = $target_path . "archivos/fondo/" . $id . '.jpg';
        @move_uploaded_file($_FILES['fondo']['tmp_name'], $upload_path);
      }

      if (@$_FILES['isometrico']) {
        $upload_path = $target_path . "archivos/eventos/" . 'iso_' . $id . '.jpg';
        @move_uploaded_file($_FILES['isometrico']['tmp_name'], $upload_path);
      }

      if (@$_FILES['mapa']) {
        $upload_path = $target_path . "archivos/mapas/" . $id . '.jpg';
        @move_uploaded_file($_FILES['mapa']['tmp_name'], $upload_path);
      }

      if (@$_FILES['ingreso']) {
        $upload_path = $target_path . "archivos/ingreso/" . $id . '.jpg';
        @move_uploaded_file($_FILES['ingreso']['tmp_name'], $upload_path);
      }

      if (@$_FILES['bienvenida']) {
        $upload_path = $target_path . "archivos/bienvenida/" . $id . '.jpg';
        @move_uploaded_file($_FILES['bienvenida']['tmp_name'], $upload_path);
      }

      if (@$_FILES['ubicacion']) {
        $upload_path = $target_path . "archivos/waze/" . $id . '.jpg';
        @move_uploaded_file($_FILES['ubicacion']['tmp_name'], $upload_path);
      }

      if (@$_FILES['boleta']) {
        $upload_path = $target_path . "archivos/boleta/" . $id . '.jpg';
        @move_uploaded_file($_FILES['boleta']['tmp_name'], $upload_path);
      }

      for ($i = 1; $i <= 10; $i++) {
        if (@$_FILES['patrocinador_' . $i]) {
          $upload_path = $target_path . "archivos/patrocinadores/" . $i . '_' . $id . '.jpg';
          @move_uploaded_file($_FILES['patrocinador_' . $i]['tmp_name'], $upload_path);
        }
      }

      for ($i = 1; $i <= 10; $i++) {
        if (@$_FILES['menu_' . $i]) {
          $upload_path = $target_path . "archivos/menu/" . $i . '_' . $id . '.jpg';
          @move_uploaded_file($_FILES['menu_' . $i]['tmp_name'], $upload_path);
        }
      }

      if (@$_FILES['video']) {
        $upload_path = $target_path . "archivos/video/" . $id . '.mp4';
        @move_uploaded_file($_FILES['video']['tmp_name'], $upload_path);
      }

      for ($i = 1; $i <= 10; $i++) {
        if (@$_FILES['dj_' . $i]) {
          $target_path = $target_path . "archivos/dj/" . $i . '_' . $id . '.jpg';
          @move_uploaded_file($_FILES['dj_' . $i]['tmp_name'], $target_path);
        }
      }

      return redirect('admin/eventos/' . substr($_POST['fecha'], 0, 7));
      exit();
    }

    return view('admin.reservas.agregar_evento', [
      'menubar' => $this->list_sidebar(),
      'data'    => $data,
      'edit'    => $edit,
      'venues'  => $venues
    ]);
  }

  public function mesas($id_evento = null) {
    echo config('app.fecha_evento');
    if (!$id_evento) {
      $str = "select * from admin_eventos where estado and fecha >= '" . date('Y-m-d') . "' order by fecha limit 1;";
      $evento = DB::select($str)[0];  
      $id_evento = $evento->id;
    }

    // if () {
      // $id_evento = 11;
    // }

    $str  = "select admin_eventos_mesas.*, admin_eventos_venues_ubicaciones.nombre area, admin_eventos.pagado, admin_eventos.id id_evento, admin_eventos.nombre evento, if (invitados.invitados is null, 0, invitados.invitados) invitados, if(mujeres.mujeres is null, 0, mujeres.mujeres) mujeres, if (hombres.hombres is null, 0, hombres.hombres) hombres, if (pagados.pagados is null, 0, pagados.pagados) pagados, if (duplicados.duplicados is null, 0 , duplicados.duplicados) duplicados, meseros.name mesero, cobradores.name cobrador, jefes.name jefe, ingresados.ingresados from admin_eventos_mesas left join admin_eventos on (admin_eventos.id = admin_eventos_mesas.id_evento and admin_eventos.estado) left join admin_eventos_venues_ubicaciones on (admin_eventos_venues_ubicaciones.id = admin_eventos_mesas.id_area and admin_eventos_venues_ubicaciones.estado) left join (select count(*) invitados, id_mesa from admin_eventos_mesas_invitados where estado group by id_mesa) invitados on (admin_eventos_mesas.id = invitados.id_mesa) left join (select count(mesas_invitados.id) mujeres, id_mesa from admin_eventos_mesas_invitados mesas_invitados left join admin_eventos_invitados invitados on (mesas_invitados.id_invitado = invitados.id) where invitados.sexo = 0 and invitados.estado and mesas_invitados.estado group by id_mesa) mujeres on (admin_eventos_mesas.id = mujeres.id_mesa) left join (select count(mesas_invitados.id) hombres, id_mesa from admin_eventos_mesas_invitados mesas_invitados left join admin_eventos_invitados invitados on (mesas_invitados.id_invitado = invitados.id) where invitados.sexo = 1 and invitados.estado and mesas_invitados.estado group by id_mesa) hombres on (admin_eventos_mesas.id = hombres.id_mesa) left join (select count(*) pagados, id_mesa from admin_eventos_mesas_invitados where pagado and estado group by id_mesa) pagados on (admin_eventos_mesas.id = pagados.id_mesa) left join (select id_mesa, count(repetido) duplicados from admin_eventos_mesas_invitados where repetido = 1 group by id_mesa) duplicados on (admin_eventos_mesas.id = duplicados.id_mesa) left join admin_users meseros on (meseros.id = admin_eventos_mesas.id_mesero) left join admin_users cobradores on (cobradores.id = admin_eventos_mesas.id_cobrador_1) left join admin_users jefes on (jefes.id = admin_eventos_mesas.id_jefe_1)left join (select count(mesas_invitados.id) ingresados, id_mesa from admin_eventos_mesas_invitados mesas_invitados left join admin_eventos_invitados invitados on (mesas_invitados.id_invitado = invitados.id) where ingreso and invitados.estado and mesas_invitados.estado group by id_mesa) ingresados on (admin_eventos_mesas.id = ingresados.id_mesa) where admin_eventos.id = $id_evento  and admin_eventos_mesas.estado order by admin_eventos_mesas.nombre;";
    $data = DB::select($str);
    $edit = false;

    $array_mes = array(1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',7=>'Julio',8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre');

    $str  = "select * from admin_eventos where estado and fecha >= '" . date('Y-m-d') . "' order by fecha;";
    $eventos = DB::select($str); 

    foreach($data as $key => $item) {
      @$data[$key]->nombre_sin_acento = $this->eliminar_acentos($item->nombre);

      $str  = "select admin_eventos_mesas_lideres.id, admin_users.name, admin_users.usersys, admin_users.id id_lider from admin_eventos_mesas_lideres left join admin_users on (admin_eventos_mesas_lideres.id_lider = admin_users.id) where admin_users.statusUs and admin_eventos_mesas_lideres.id_mesa = '" . $item->id . "';";
      $mesas_lideres = DB::select($str);
      // $array_ml = array();
      // foreach($mesas_lideres as $key_ml => $item_ml) {
      //   $array_ml[] => array('id' => $item_ml->id_lider, 'nombre' => $item_ml)
      // }
      // dd($mesas_lideres);
    }

    foreach($data as $key => $item) {
      $str = "select admin_users.usersys from admin_eventos_mesas_lideres left join admin_users on (admin_users.id = admin_eventos_mesas_lideres.id_lider) where id_mesa = '" . $item->id . "' limit 1;";
      $usersys = @DB::select($str)[0]->usersys;

      $data[$key]->usersys = @$usersys;
    }

    return view('admin.reservas.mesas', [
      'menubar' => $this->list_sidebar(),
      'data'    => $data,
      'edit'    => $edit,
      'eventos'       => $eventos,
      'array_mes'     => $array_mes,
      'id_evento' => $id_evento,
      'mesas_lideres' => @$mesas_lideres
    ]);
  }

  public function agregar_mesa($id_evento = 0, $id = null){
    $str  = "select id_venue, mesas.*, id_evento, id_venue from admin_eventos_mesas mesas left join admin_eventos eventos on (mesas.id_evento = eventos.id and eventos.estado) left join admin_eventos_venues venues on (eventos.id_venue = venues.id and venues.estado) where mesas.id = '$id';";
    $data = @DB::select($str)[0];

    if (@$_POST['_token']) {
      $data = [
        'id_evento'     => $_POST['id_evento'],
        'id_area'       => $_POST['id_area'],
        'nombre'        => @$_POST['nombre'],
        'id_mesero'     => @$_POST['id_mesero'] ?: 0,
        'id_cobrador_1' => @$_POST['id_cobrador_1'] ?: 0,
        'id_cobrador_2' => @$_POST['id_cobrador_2'] ?: 0,
        'id_jefe_1'     => @$_POST['id_jefe_1'] ?: 0,
        'id_jefe_2'     => @$_POST['id_jefe_2'] ?: 0,
        'pax'           => @$_POST['pax'] ?: 0,
        'estado'        => 1
      ];

      if ($id) {
        DB::table('admin_eventos_mesas')->where('id', $id)->update($data);
      } else {
        $mesa = eventosMesasModel::create($data);
        $mesa->save();
        $id = $mesa->id;
      }

      return redirect("admin/agregar_mesa/$id_evento/$id");
      exit();
    }

    $str  = "select * from admin_users where roleUS = 2 and statusUs;";
    $lideres = DB::select($str);

    $str  = "select admin_eventos.*, admin_eventos_venues.id id_venue from admin_eventos left join admin_eventos_venues on (admin_eventos_venues.id = admin_eventos.id_venue) where fecha >= '" . date('Y-m-d') . "' and admin_eventos.estado and admin_eventos_venues.estado order by fecha;";
    $fechas = DB::select($str);

    $str = "select colaboradores.* from admin_eventos_venues_colaboradores venuesc left join admin_users colaboradores on (venuesc.id_colaborador = colaboradores.id) where id_venue = '" . @$data->id_venue . "' and roleUS = 4 order by es_plus desc, prioridad, name;";
    $meseros = DB::select($str);

    $str = "select colaboradores.* from admin_eventos_venues_colaboradores venuesc left join admin_users colaboradores on (venuesc.id_colaborador = colaboradores.id) where id_venue = '" . @$data->id_venue . "' and roleUS = 5 order by es_plus desc, prioridad, name;";
    $cobradores = DB::select($str);

    $str = "select colaboradores.* from admin_eventos_venues_colaboradores venuesc left join admin_users colaboradores on (venuesc.id_colaborador = colaboradores.id) where id_venue = '" . @$data->id_venue . "' and roleUS = 8 order by es_plus desc, prioridad, name;";
    $jefes = DB::select($str);

    $str  = "select admin_eventos_mesas_lideres.id, admin_users.name, admin_users.id id_lider from admin_eventos_mesas_lideres left join admin_users on (admin_eventos_mesas_lideres.id_lider = admin_users.id) where admin_users.statusUs and admin_eventos_mesas_lideres.id_mesa = '$id';";
    $mesas_lideres = DB::select($str);

    $str = "select * from admin_eventos_venues_sectores where id_venue = '" . @$data->id_venue . "' and estado;";
    $sectores = DB::select($str);

    $str = "select * from admin_eventos_venues_ubicaciones where id_venue = '" . @$data->id_venue . "' and estado;";
    $areas = DB::select($str);

    $str = "select smesas.id, sectores.nombre, smesas.no_mesa from admin_eventos_venues_sectores_mesas smesas inner join admin_eventos_venues_sectores sectores on (smesas.id_sector = sectores.id) and smesas.id_mesa = '$id' order by sectores.nombre, smesas.no_mesa;";
    $sectores_mesas = DB::select($str);

    foreach($sectores_mesas as $key => $item) {
      $json_sm[$item->nombre][] = $item->no_mesa;
    }

    // dd($data);

    return view('admin.reservas.agregar_mesa', [
      'menubar'        => $this->list_sidebar(),
      'data'           => $data,
      'lideres'        => $lideres,
      'fechas'         => $fechas,
      'meseros'        => $meseros,
      'cobradores'     => $cobradores,
      'jefes'     => $jefes,
      'mesas_lideres'  => $mesas_lideres,
      'areas'          => $areas,
      'sectores'       => $sectores,
      'sectores_mesas' => $sectores_mesas,
      'json_sm'        => @$json_sm ?: [],
      'id_evento'      => $id_evento
    ]);
  }

  public function cargar_mesas_asignadas($id_mesa = 0) {
    $str = "select smesas.id, sectores.nombre, smesas.no_mesa from admin_eventos_venues_sectores_mesas smesas left join admin_eventos_venues_sectores sectores on (smesas.id_sector = sectores.id) and smesas.id_mesa = $id_mesa order by sectores.nombre, smesas.no_mesa;";
    $sectores_mesas = DB::select($str);

    foreach($sectores_mesas as $key => $item) {
      $json_sm[$item->nombre][] = $item->no_mesa;
    }

    return json_encode(
      array(
        'json_sm' => json_encode(@$json_sm ?: [])
      )
    );
  }

  public function borrar_mesas_asignadas($id = null) {
    eventosVenuesSectoresMesasModel::where('id', $id)->delete();

    return json_encode(
      array(
        'borrado' => true
      )
    );
  }

  public function borrar_reservacion($id_mesa = null) {
    DB::table('admin_eventos_mesas')->where('id', $id_mesa)->update([
      'estado' => 0
    ]);

    return json_encode(
      array(
        'id' => $id_mesa
      )
    );
  }

  public function asignar_mesa_lider($id_mesa = 0, $id_sector = 0, $no_mesa = 0) {
    $data = eventosVenuesSectoresMesasModel::create([
      'id_mesa'   => $id_mesa,
      'id_sector' => $id_sector,
      'no_mesa'   => $no_mesa
    ]);

    $str = "select * from admin_eventos_venues_sectores where id = $id_sector;";
    $sector = DB::select($str)[0];

    return json_encode(
      array(
        'id' => @$data->id ?: 1,
        'nombre' => $sector->nombre
      )
    );
  }

  public function actualizar_mesa($id = null, $cantidad = null, $id_celebracion = null, $pull = 0, $id_pull = 0){
    $data = [
      'pull'    => $pull,
      'id_pull' => $id_pull
    ];

    if ($cantidad > 0) {
      $data['pax'] = $cantidad;
    }
    if ($id_celebracion) {
      $data['id_celebracion'] = $id_celebracion;
    }


    DB::table('admin_eventos_mesas')->where('id', $id)->update($data);

    return json_encode(
      array(
        'id' => $id
      )
    );
  }


  public function agregar_lider_a_mesa($id_mesa = 0, $id_lider = 0, $accion = 0){
    $evento = eventosMesasLideresModel::create([
      'id_mesa'  => $id_mesa,
      'id_lider' => $id_lider
    ]);
    $evento->save();

    $str = "select * from admin_users where id = $id_lider;";
    $lider = DB::select($str)[0];

    if ($accion) {
      $id_invitado = DB::table('admin_eventos_invitados')->insertGetId([
        'nombre'   => $lider->name,
        'sexo'     => $lider->sexo,
        'id_lider' => $lider->id
      ]);
    } else {
      $str = "select * from admin_eventos_invitados where id_lider = " . $lider->id . ";";
      $id_invitado = DB::select($str)[0]->id;
    }

    $id_invitado = DB::table('admin_eventos_mesas_invitados')->insertGetId([
      'id_mesa'     => $id_mesa,
      'id_invitado' => $id_invitado
    ]);

    return json_encode(
      array(
        'nombre' => $lider->name,
        'id'     => $lider->id
      )
    );
  }

  public function cerrar_lista($id_mesa = null, $accion = null){
    DB::table('admin_eventos_mesas')->where('id', $id_mesa)->update([
      'abierta' => $accion
    ]);

    return json_encode(
      array(
        'id_mesa' => $id_mesa
      )
    );
  }

  public function borrar_lider_de_mesa($id_lider = 0, $id_mesa = 0){
    eventosMesasLideresModel::where('id_lider', $id_lider)->where('id_mesa', $id_mesa)->delete();

    return json_encode(
      array(
        'id' => $id_lider
      )
    );
  }

  public function borrar_invitado_de_mesa($id = null){
    DB::table('admin_eventos_mesas_invitados')->where('id', $id)->update([
      'estado' => 0
    ]);

    $str = "select * from admin_eventos_mesas_invitados where id = '$id';";
    $id_mesa = DB::select($str)[0]->id_mesa;

    for($i = 0; $i <= 1; $i++) {
      $str = "select mesas_invitados.* from admin_eventos_mesas_invitados mesas_invitados left join admin_eventos_invitados invitados on (mesas_invitados.id_invitado = invitados.id) where mesas_invitados.estado and sexo = $i and id_mesa = $id_mesa order by nombre;";
      $listado = DB::select($str);
      $fila = 1;
      foreach($listado as $key2 => $item2) {
        DB::table('admin_eventos_mesas_invitados')->where('id', $item2->id)->update([
          'fila' => $fila
        ]);

        $fila++;
      }
    }

    return json_encode(
      array(
        'id' => $id_mesa
      )
    );
  }

  public function destroy($id) {
    $model = new _Model;
    $model::find($id)->delete();

    return redirect()->back()->with('success','Borrado correctamente!');
  }

  public function lista_eventos($vacio = 0) {
    $str = "select * from admin_users where id = '" . Auth::id() . "';";
    $lider = DB::select($str)[0];

    $str = "select eventos.*, lideres.id_mesa from admin_eventos eventos left join (select mesas.id_evento, lideres.id_lider, lideres.id_mesa from admin_eventos_mesas_lideres lideres left join admin_eventos_mesas mesas on (mesas.id = lideres.id_mesa) where lideres.estado and mesas.estado) lideres on (eventos.id = lideres.id_evento and lideres.id_lider = '" . $lider->id . "') where eventos.fecha >= '" . date('Y-m-d') . "' and eventos.estado order by eventos.fecha;";
    // echo $str; exit();
    $eventos = DB::select($str);

    $data_eventos = array();
    foreach($eventos as $key => $item) {
      $data_eventos[substr($item->fecha, 5, 2)][substr($item->fecha, 8, 2)] = $item;
    }
    $eventos = $data_eventos;
    // dd($eventos);
    
    $array_mes = array(1=>'enero',2=>'febrero',3=>'marzo',4=>'abril',5=>'mayo',6=>'junio',7=>'julio',8=>'agosto',9=>'septiembre',10=>'octubre',11=>'noviembre',12=>'diciembre');

    return view('admin.reservas.lista_eventos', [
      'menubar' => $this->list_sidebar(),
      'eventos' => $eventos,
      'array_mes' => $array_mes,
      'lider' => $lider,
      'vacio' => $vacio
    ]);
  }

  public function lista_invitados($id_mesa = null) {
    $str = "select * from admin_users where id = '" . Auth::id() . "';";
    $lider = DB::select($str)[0];


    if ($lider->roleUS == 1) {
      return redirect('/admin/roles');
    } elseif ($lider->roleUS == 3) {
      return redirect('/admin/mesas');
    } elseif ($lider->roleUS == 4) {
      return redirect('/admin/balance');
    } elseif ($lider->roleUS == 5) {
      redirect('admin/pedidos');
    } elseif ($lider->roleUS == 8) {
      return redirect('/admin/acreditaciones');
    } elseif ($lider->roleUS == 9) {
      return redirect('/admin/control_de_ingreso');
    } elseif ($lider->roleUS == 10) {
      return redirect('admin/inventario');
    } elseif ($lider->roleUS == 13) {
      return redirect('/admin/resumen-g');
    }

    if (!$id_mesa) {
      $str = "select eventos.*, mesas.id id_mesa from admin_eventos_mesas_lideres lideres left join admin_eventos_mesas mesas on (mesas.id = lideres.id_mesa) left join admin_eventos eventos on (eventos.id = mesas.id_evento and eventos.estado) where mesas.estado and eventos.estado and lideres.id_lider = " . $lider->id . " and eventos.fecha >= substr(now(), 1, 10) order by eventos.fecha limit 1;";
      $mesas = DB::select($str);
      if (@count($mesas) > 0) {
        $id_mesa = $mesas[0]->id_mesa;
      } else {
        return redirect('admin/lista_eventos/1');
      }
    }

    $str = "select admin_eventos_mesas.*, admin_eventos.pagado, admin_eventos.listas_cerradas, admin_users.name mesero, admin_users.id id_mesero, admin_users2.name cobrador_1, admin_users2.id id_cobrador_1, admin_users4.name cobrador_2, admin_users5.id id_cobrador_2, admin_users3.name jefe_1, admin_users3.id id_jefe_1, admin_users4.name jefe_2, admin_users4.id id_jefe_2, admin_eventos_venues.link_waze, admin_eventos_mesas_pull.nombre pull from admin_eventos_mesas left join admin_eventos on (admin_eventos_mesas.id_evento = admin_eventos.id and admin_eventos.estado) left join admin_users on (admin_users.id = admin_eventos_mesas.id_mesero) left join admin_users admin_users2 on (admin_users2.id = admin_eventos_mesas.id_cobrador_1) left join admin_users admin_users5 on (admin_users5.id = admin_eventos_mesas.id_cobrador_2) left join admin_users admin_users3 on (admin_users3.id = admin_eventos_mesas.id_jefe_1) left join admin_users admin_users4 on (admin_users4.id = admin_eventos_mesas.id_jefe_2) left join admin_eventos_venues on (admin_eventos_venues.id = admin_eventos.id_venue and admin_eventos_venues.estado) left join admin_eventos_mesas_pull on (admin_eventos_mesas.id_pull = admin_eventos_mesas_pull.id) where admin_eventos_mesas.id = '$id_mesa';";
    $mesa = DB::select($str)[0];

    $str = "select eventos.*, venues.id id_venue from admin_eventos eventos left join admin_eventos_venues venues on (eventos.id_venue = venues.id) where eventos.id = '" . $mesa->id_evento  . "';";
    $evento = DB::select($str)[0];

    $str  = "select count(id) no_pagados from admin_eventos_mesas_invitados where estado and id_mesa = $id_mesa;";
    $total_invitados = DB::select($str)[0]->no_pagados;  

    $str  = "select count(id) pagados from admin_eventos_mesas_invitados where estado and id_mesa = $id_mesa and pagado;";
    $total_pagados = DB::select($str)[0]->pagados;  

    $str  = "select count(id) no_pagados from admin_eventos_mesas_invitados where estado and id_mesa = $id_mesa and pagado = 0;";
    $total_no_pagados = DB::select($str)[0]->no_pagados; 

    $str  = "select count(mesas_invitados.id) pagados from admin_eventos_mesas_invitados mesas_invitados left join admin_eventos_invitados invitados on (mesas_invitados.id_invitado = invitados.id) where mesas_invitados.estado and invitados.estado and id_mesa = $id_mesa and sexo = 0;";
    $total_mujeres = DB::select($str)[0]->pagados; 

    $str  = "select count(mesas_invitados.id) pagados from admin_eventos_mesas_invitados mesas_invitados left join admin_eventos_invitados invitados on (mesas_invitados.id_invitado = invitados.id) where mesas_invitados.estado and invitados.estado and id_mesa = $id_mesa and sexo = 1;";
    $total_hombres = DB::select($str)[0]->pagados;

    $str  = "select count(mesas_invitados.id) pagados from admin_eventos_mesas_invitados mesas_invitados left join admin_eventos_invitados invitados on (mesas_invitados.id_invitado = invitados.id) where mesas_invitados.estado and invitados.estado and id_mesa = $id_mesa and sexo = 0 and pagado;";
    $total_mujeres_pagado = DB::select($str)[0]->pagados; 

    $str  = "select count(mesas_invitados.id) pagados from admin_eventos_mesas_invitados mesas_invitados left join admin_eventos_invitados invitados on (mesas_invitados.id_invitado = invitados.id) where mesas_invitados.estado and invitados.estado and id_mesa = $id_mesa and sexo = 1 and pagado;";
    $total_hombres_pagado = DB::select($str)[0]->pagados; 

    $str  = "select count(mesas_invitados.id) pagados from admin_eventos_mesas_invitados mesas_invitados left join admin_eventos_invitados invitados on (mesas_invitados.id_invitado = invitados.id) where mesas_invitados.estado and invitados.estado and id_mesa = $id_mesa and sexo = 0 and pagado = 0;";
    $total_mujeres_no_pagado = DB::select($str)[0]->pagados; 

    $str  = "select count(mesas_invitados.id) pagados from admin_eventos_mesas_invitados mesas_invitados left join admin_eventos_invitados invitados on (mesas_invitados.id_invitado = invitados.id) where mesas_invitados.estado and invitados.estado and id_mesa = $id_mesa and sexo = 1 and pagado = 0;";
    $total_hombres_no_pagado = DB::select($str)[0]->pagados;

    $str = "select colaboradores.* from admin_eventos_venues_colaboradores venuesc left join admin_users colaboradores on (venuesc.id_colaborador = colaboradores.id) where id_venue = " . $evento->id_venue . " and roleUS = 4 and statusUs order by es_plus desc, prioridad, name;";
    $meseros = DB::select($str);

    $str = "select colaboradores.* from admin_eventos_venues_colaboradores venuesc left join admin_users colaboradores on (venuesc.id_colaborador = colaboradores.id) where id_venue = " . $evento->id_venue . " and roleUS = 6 and statusUs order by es_plus desc, prioridad, name;";
    $bartenders = DB::select($str);

    $str = "select colaboradores.* from admin_eventos_venues_colaboradores venuesc left join admin_users colaboradores on (venuesc.id_colaborador = colaboradores.id) where id_venue = " . $evento->id_venue . " and roleUS = 5 and statusUs order by es_plus desc, prioridad, name;";
    $coordinadores = DB::select($str);

    $str = "select colaboradores.* from admin_eventos_venues_colaboradores venuesc left join admin_users colaboradores on (venuesc.id_colaborador = colaboradores.id) where id_venue = " . $evento->id_venue . " and roleUS = 7 and statusUs order by es_plus desc, prioridad, name;";
    $seguridad = DB::select($str);

    $str = "select colaboradores.* from admin_eventos_venues_colaboradores venuesc left join admin_users colaboradores on (venuesc.id_colaborador = colaboradores.id) where id_venue = " . $evento->id_venue . " and roleUS = 8 and statusUs order by es_plus desc, prioridad, name;";
    $jefes = DB::select($str);

    $str = "select colaboradores.* from admin_eventos_venues_colaboradores venuesc left join admin_users colaboradores on (venuesc.id_colaborador = colaboradores.id) where id_venue = " . $evento->id_venue . " and roleUS = 10 and statusUs order by es_plus desc, prioridad, name;";
    $bodegas = DB::select($str);

    $str = "select colaboradores.* from admin_eventos_venues_colaboradores venuesc left join admin_users colaboradores on (venuesc.id_colaborador = colaboradores.id) where id_venue = " . $evento->id_venue . " and roleUS = 11 and statusUs order by es_plus desc, prioridad, name;";
    $banos = DB::select($str);

    $str = "select colaboradores.* from admin_eventos_venues_colaboradores venuesc left join admin_users colaboradores on (venuesc.id_colaborador = colaboradores.id) where id_venue = " . $evento->id_venue . " and roleUS = 12 and statusUs order by es_plus desc, prioridad, name;";
    $food = DB::select($str);

    $str = "select id_venue, sectores.nombre, sectores_mesas.no_mesa from admin_eventos_venues_sectores_mesas sectores_mesas left join admin_eventos_venues_sectores sectores on (sectores_mesas.id_sector = sectores.id) where sectores_mesas.id_mesa = $id_mesa order by no_mesa;";
    $mesas_asignadas = DB::select($str);

    DB::table('admin_eventos_mesas_invitados')->update([
      'repetido' => 0
    ]);
    $str = "select count(admin_eventos_mesas_invitados.id_invitado) cantidad, admin_eventos_mesas_invitados.id_invitado, admin_eventos_mesas_invitados.id, admin_eventos_mesas.id_evento, admin_eventos_mesas_invitados.id_mesa, admin_eventos_invitados.nombre from admin_eventos_mesas_invitados left join admin_eventos_mesas on (admin_eventos_mesas_invitados.id_mesa = admin_eventos_mesas.id) left join admin_eventos_invitados on (admin_eventos_mesas_invitados.id_invitado = admin_eventos_invitados.id) where admin_eventos_mesas_invitados.estado and admin_eventos_mesas.estado and admin_eventos_mesas.id_evento = " . $mesa->id_evento . " group by admin_eventos_mesas_invitados.id_invitado, admin_eventos_mesas.id_evento having cantidad > 1 order by nombre;";
    $duplicados = DB::select($str);
    foreach($duplicados as $key => $item) {
      $str = "select admin_eventos_mesas_invitados.id from admin_eventos_mesas_invitados left join admin_eventos_mesas on (admin_eventos_mesas.id = admin_eventos_mesas_invitados.id_mesa) where admin_eventos_mesas.id_evento = " . $mesa->id_evento . " and id_invitado = '" . $item->id_invitado . "';";
      $repetidos = DB::select($str);
      foreach($repetidos as $key2 => $item2) {
        DB::table('admin_eventos_mesas_invitados')->where('id', $item2->id)->update([
          'repetido' => 1
        ]);
      }
    }

    for($i = 0; $i <= 1; $i++) {
      $str = "select mesas_invitados.* from admin_eventos_mesas_invitados mesas_invitados left join admin_eventos_invitados invitados on (mesas_invitados.id_invitado = invitados.id) where mesas_invitados.estado and invitados.estado and sexo = $i and id_mesa = $id_mesa " . ($mesa->listas_cerradas ? ' and (pagado or cortesia)' : '') . " order by nombre;";
      $listado = DB::select($str);
      $fila = 1;
      foreach($listado as $key2 => $item2) {
        DB::table('admin_eventos_mesas_invitados')->where('id', $item2->id)->update([
          'fila' => $fila
        ]);

        $fila++;
      }
    }

    for($i = 1; $i <= ceil(($mesa->listas_cerradas ? 100 : ($mesa->pax ?: 20)) * 0.6); $i++) {
      @$data_m[$i . '-' . 0] = 0;
    }

    $str  = "select mesas_invitados.*, invitados.nombre, invitados.sexo, invitados.telefono, invitados.correo, invitados.fecha_nacimiento from admin_eventos_mesas_invitados mesas_invitados left join admin_eventos_invitados invitados on (mesas_invitados.id_invitado = invitados.id) where id_mesa = $id_mesa and sexo = 0 and mesas_invitados.estado " . ($mesa->listas_cerradas ? ' and (pagado or cortesia)' : '') . " order by nombre;";
    $invitados = DB::select($str);

    $data = array();
    foreach($invitados as $key => $item) {
      @$data_m[$item->fila . '-' . $item->sexo] = $item;
    }

    for($i = 1; $i <= (($mesa->listas_cerradas ? 100 : ($mesa->pax ?: 20)) - ceil(($mesa->listas_cerradas ? 100 : ($mesa->pax ?: 20)) * 0.6)); $i++) {
      @$data_h[$i . '-' . 1] = 0;
    }

    $str  = "select mesas_invitados.*, invitados.nombre, invitados.sexo, invitados.telefono, invitados.correo, invitados.fecha_nacimiento from admin_eventos_mesas_invitados mesas_invitados left join admin_eventos_invitados invitados on (mesas_invitados.id_invitado = invitados.id) where id_mesa = $id_mesa and sexo = 1 and mesas_invitados.estado " . ($mesa->listas_cerradas ? ' and (pagado or cortesia)' : '') . " order by nombre;";
    $invitados = DB::select($str);

    $data = array();
    foreach($invitados as $key => $item) {
      @$data_h[$item->fila . '-' . $item->sexo] = $item;
    }

    // dd($data_h);

    $str = "select pull.* from admin_eventos_mesas mesas left join admin_eventos_mesas_pull pull on (mesas.id_pull = pull.id) where mesas.id = " . $id_mesa . ";";
    $pull = DB::select($str)[0];

    $str = "select count(pull_pagado) pull_pagado from admin_eventos_mesas_invitados mesas_invitados left join admin_eventos_invitados invitados on (mesas_invitados.id_invitado = invitados.id) where mesas_invitados.estado and invitados.estado and id_mesa = $id_mesa and pull_pagado = 1 and sexo = 0;";
    $pull_pagado_mujeres = DB::select($str)[0]->pull_pagado;

    $str = "select count(pull_pagado) pull_pagado from admin_eventos_mesas_invitados mesas_invitados left join admin_eventos_invitados invitados on (mesas_invitados.id_invitado = invitados.id) where mesas_invitados.estado and invitados.estado and id_mesa = $id_mesa and pull_pagado = 1 and sexo = 1;";
    $pull_pagado_hombres = DB::select($str)[0]->pull_pagado;

    return view('admin.reservas.lista_invitados', [
      'menubar'                 => $this->list_sidebar(),
      'mesa'                    => $mesa,
      'data'                    => $data,
      'lider'                   => $lider,
      'total_invitados'         => $total_invitados,
      'total_pagados'           => $total_pagados,
      'total_no_pagados'        => $total_no_pagados,
      'total_mujeres'           => $total_mujeres,
      'total_hombres'           => $total_hombres,
      'total_mujeres_pagado'    => $total_mujeres_pagado,
      'total_hombres_pagado'    => $total_hombres_pagado,
      'total_mujeres_no_pagado' => $total_mujeres_no_pagado,
      'total_hombres_no_pagado' => $total_hombres_no_pagado,
      'evento'                  => $evento,
      'mayor_edad'              => $lider->mayor_edad,
      'data_m'                  => @$data_m,
      'data_h'                  => @$data_h,
      'meseros'                 => $meseros,
      'seguridad'               => $seguridad,
      'bartenders'              => $bartenders,
      'coordinadores'           => $coordinadores,
      'jefes'                   => $jefes,
      'bodegas'                   => $bodegas,
      'banos'                   => $banos,
      'food' => $food,
      'mesas_asignadas'         => $mesas_asignadas,
      'pull'         => $pull,
      'pull_pagado_mujeres'         => $pull_pagado_mujeres,
      'pull_pagado_hombres'         => $pull_pagado_hombres
    ]);
  }

  public function agregar_invitado($id_mesa, $nombre = null, $sexo = null, $fila = null, $accion = null, $id = 0) {
    $nombre = urldecode($nombre);

    $str = "select * from admin_eventos_invitados where nombre = '" . $nombre . "';";
    $invitado = DB::select($str);

    $duplicado = false;
    if (count($invitado) <= 0) {
      $id_invitado = DB::table('admin_eventos_invitados')->insertGetId([
        'nombre'  => $nombre,
        'sexo'    => $sexo
      ]);

      if ($accion == 2) {
        DB::table('admin_eventos_mesas_invitados')->where('id', $id)->update([
          'id_invitado' => $id_invitado,
        ]);
        $id_invitado = $id;
      } else {
        $id_invitado = DB::table('admin_eventos_mesas_invitados')->insertGetId([
          'id_mesa'     => $id_mesa,
          'id_invitado' => $id_invitado,
          'fila'        => $fila
        ]);
      }
    } else {
      $duplicado = true;

      $str = "select * from admin_eventos_mesas where id = $id_mesa;";
      $id_evento = DB::select($str)[0]->id_evento;

      $str = "select invitados.*, mesas_invitados.id_mesa, mesas.nombre nombre_mesa from admin_eventos_invitados invitados left join admin_eventos_mesas_invitados mesas_invitados on (invitados.id = mesas_invitados.id_invitado) left join admin_eventos_mesas mesas on (mesas_invitados.id_mesa = mesas.id) where mesas_invitados.estado and mesas.id_evento = $id_evento and invitados.nombre = '$nombre';";
      $registros = DB::select($str);

      $idd_mesa = 0;
      if (count($registros) > 0) {
        $duplicadoInfo = $registros[0];
      }
    }

    return json_encode(
      array(
        'id_mesa' => $id_mesa,
        'nombre'  => $nombre,
        'sexo'    => $sexo,
        'fila'    => $fila,
        'id'      => @$id_invitado,
        'duplicado' => $duplicado,
        'idd_mesa' => @$duplicadoInfo->id_mesa ?: 0,
        'idd_nombre' => @$duplicadoInfo->nombre_mesa ?: 0
      )
    );
  }

  public function detalle_invitados($id_evento = 0, $id_mesa = null, $filtro = 1) {
    $str  = "select mesas_invitados.*, mesas.pull, mesas.id_pull, pagos.id id_pago, invitados.nombre, invitados.sexo, eventos.pagado evento_pagado from admin_eventos_mesas_invitados mesas_invitados left join admin_eventos_invitados invitados on (mesas_invitados.id_invitado = invitados.id) left join admin_eventos_mesas mesas on (mesas_invitados.id_mesa = mesas.id) left join admin_eventos_pagos pagos on (mesas_invitados.id = pagos.id_invitado) left join admin_eventos eventos on (mesas.id_evento = eventos.id) where id_mesa = $id_mesa and sexo = 0 and mesas_invitados.estado " . ($filtro == 3 ? "in (1, 0)" : "= $filtro") . " and mesas.estado order by nombre;";
    $mujeres = DB::select($str);

    $str  = "select mesas_invitados.*, mesas.pull, mesas.id_pull, pagos.id id_pago, invitados.nombre, invitados.sexo, eventos.pagado evento_pagado from admin_eventos_mesas_invitados mesas_invitados left join admin_eventos_invitados invitados on (mesas_invitados.id_invitado = invitados.id) left join admin_eventos_mesas mesas on (mesas_invitados.id_mesa = mesas.id) left join admin_eventos_pagos pagos on (mesas_invitados.id = pagos.id_invitado) left join admin_eventos eventos on (mesas.id_evento = eventos.id) where id_mesa = $id_mesa and sexo = 1 and mesas_invitados.estado " . ($filtro == 3 ? "in (1, 0)" : "= $filtro") . " and mesas.estado order by nombre;";
    $hombres = DB::select($str);  

    $str  = "select count(id) total from admin_eventos_mesas_invitados where id_mesa = $id_mesa and estado;";
    $invitados = DB::select($str)[0]->total;  

    $str  = "select count(id) total from admin_eventos_mesas_invitados where id_mesa = $id_mesa and pagado and estado;";
    $pagados = DB::select($str)[0]->total;  

    $str  = "select count(id) total from admin_eventos_mesas_invitados where id_mesa = $id_mesa and pagado and estado;";
    $no_pagados = DB::select($str)[0]->total; 

    $str  = "select count(mesas_invitados.id) total from admin_eventos_mesas_invitados mesas_invitados left join admin_eventos_invitados invitados on (mesas_invitados.id_invitado = invitados.id) where mesas_invitados.estado and id_mesa = $id_mesa and sexo = 0;";
    $mujeres_total = DB::select($str)[0]->total; 

    $str  = "select count(mesas_invitados.id) total from admin_eventos_mesas_invitados mesas_invitados left join admin_eventos_invitados invitados on (mesas_invitados.id_invitado = invitados.id) where mesas_invitados.estado and id_mesa = $id_mesa and sexo = 1;";
    $hombres_total = DB::select($str)[0]->total;

    $str  = "select count(mesas_invitados.id) total from admin_eventos_mesas_invitados mesas_invitados left join admin_eventos_invitados invitados on (mesas_invitados.id_invitado = invitados.id) where mesas_invitados.estado and id_mesa = $id_mesa and sexo = 0 and pagado;";
    $mujeres_pagado = DB::select($str)[0]->total; 

    $str  = "select count(mesas_invitados.id) total from admin_eventos_mesas_invitados mesas_invitados left join admin_eventos_invitados invitados on (mesas_invitados.id_invitado = invitados.id) where mesas_invitados.estado and id_mesa = $id_mesa and sexo = 1 and pagado;";
    $hombres_pagado = DB::select($str)[0]->total; 

    $str    = "select * from admin_eventos_mesas where id = $id_mesa;";
    $mesa = DB::select($str)[0];

    // dd($data);

    return view('admin.reservas.detalle_invitados', [
      'menubar' => $this->list_sidebar(),
      'mesa'    => $mesa,
      'mujeres' => $mujeres,
      'hombres' => $hombres,
      'invitados' => $invitados,
      'pagados' => $pagados,
      'no_pagados' => $no_pagados,
      'mujeres_total' => $mujeres_total,
      'hombres_total' => $hombres_total,
      'mujeres_pagado' => $mujeres_pagado,
      'hombres_pagado' => $hombres_pagado,
      'filtro'         => $filtro,
      'id_evento'      => $id_evento
    ]);
  }

  public function todos_los_invitados($id_evento = null) {
    $busqueda = @$_POST['busqueda'] ?: '';
    $filtro   = @$_POST['filtro'] ?: 1;
    $str  = "select mesas_invitados.*, invitados.nombre, admin_eventos_mesas.nombre mesa, admin_eventos_mesas.pull, admin_eventos_mesas.id_area, admin_eventos.nombre evento from admin_eventos_mesas_invitados mesas_invitados left join admin_eventos_invitados invitados on (mesas_invitados.id_invitado = invitados.id) left join admin_eventos_mesas on (admin_eventos_mesas.id = mesas_invitados.id_mesa) left join admin_eventos on (admin_eventos.id = admin_eventos_mesas.id_evento) where mesas_invitados.estado " . ($filtro == 3 ? "in (1, 0)" : "= $filtro") . " and invitados.sexo = 0 and invitados.nombre like '%" . $busqueda . "%' " . ($id_evento ? ('and admin_eventos.id = ' . $id_evento) : '') . " order by id desc limit 30;";
    $mujeres = DB::select($str);

    $str  = "select mesas_invitados.*, invitados.nombre, admin_eventos_mesas.nombre mesa, admin_eventos_mesas.pull, admin_eventos_mesas.id_area, admin_eventos.nombre evento from admin_eventos_mesas_invitados mesas_invitados left join admin_eventos_invitados invitados on (mesas_invitados.id_invitado = invitados.id) left join admin_eventos_mesas on (admin_eventos_mesas.id = mesas_invitados.id_mesa) left join admin_eventos on (admin_eventos.id = admin_eventos_mesas.id_evento) where mesas_invitados.estado " . ($filtro == 3 ? "in (1, 0)" : "= $filtro") . " and invitados.sexo = 1 and invitados.nombre like '%" . $busqueda . "%' " . ($id_evento ? ('and admin_eventos.id = ' . $id_evento) : '') . " order by id desc limit 30;";
    $hombres = DB::select($str);

    // dd($invitados);

    return view('admin.reservas.todos_los_invitados', [
      'menubar'   => $this->list_sidebar(),
      'mujeres'   => $mujeres,
      'hombres'   => $hombres,
      'busqueda'  => $busqueda,
      'id_evento' => $id_evento,
      'filtro'    => $filtro
    ]);
  }

  public function pago_invitado($id, $pagado = null) {
    DB::table('admin_eventos_mesas_invitados')->where('id', $id)->update([
      'pagado'   => $pagado ? 1 : 0,
      'cortesia' => $pagado ? 0 : 1
    ]);

    $pago = eventosMesasPagosModel::create([
      'id_invitado' => $id,
      'fecha'       => date('Y-m-d'),
      'hora'        => date('H:i:s')
    ]);
    $pago->save();

    return json_encode(
      array(
        'id' => $id,
        'pagado' => $pagado
      )
    );
  }

  public function cargar_invitados($id = 0, $id_mesa = 0) {
    if ($id) {
      $str = "select * from admin_eventos_mesas_invitados where id = $id;";
      $id_invitado = DB::select($str)[0]->id_invitado;
    }

    $id_evento = 0;
    if ($id_mesa) {
      $str = "select * from admin_eventos_mesas where id = $id_mesa;";
      $id_evento = DB::select($str)[0]->id_evento;
    }

    $str = "select * from admin_eventos_invitados where estado and id not in (select mesas_invitados.id_invitado from admin_eventos_mesas_invitados mesas_invitados left join admin_eventos_mesas mesas on (mesas.id = mesas_invitados.id_mesa) where mesas_invitados.estado and mesas.id_evento = $id_evento) and nombre like '%" . @$_REQUEST['q'] . "%' limit 10;";
    $invitados = DB::select($str);  

    if (count($invitados) <= 0) {
      $invitados = [array('nombre' => 'Agregar nuevo invitado', 'telefono' => null, 'id' => '+')];
    }

    return json_encode(
      array(
        'data' => $invitados,
        'error' => 0
      )
    );
  }

  public function es_menor($id, $es_menor = null) {
    DB::table('admin_eventos_mesas_invitados')->where('id', $id)->update([
      'es_menor' => $es_menor,
    ]);

    return json_encode(
      array(
        'id' => $id,
        'es_menor' => $es_menor
      )
    );
  }

  public function activar_invitado($id, $estado = null) {
    DB::table('admin_eventos_mesas_invitados')->where('id', $id)->update([
      'estado' => $estado,
    ]);

    return json_encode(
      array(
        'id' => $id,
        'estado' => $estado
      )
    );
  }

  public function pull_pagado($id, $pull_pagado = 0) {
    DB::table('admin_eventos_mesas_invitados')->where('id', $id)->update([
      'pull_pagado'   => $pull_pagado
    ]);

    return json_encode(
      array(
        'id' => $id,
        'pagado' => $pull_pagado
      )
    );
  }

  public function invitado_info($id) {
    $str = "select * from admin_eventos_mesas_invitados where id = $id;";
    $id_invitado = DB::select($str)[0]->id_invitado;

    $str  = "select invitados.* from admin_eventos_mesas_invitados mesas_invitados left join admin_eventos_invitados invitados on (mesas_invitados.id_invitado = invitados.id) where invitados.id = $id_invitado and mesas_invitados.estado;";
    $invitado = DB::select($str)[0];

    return json_encode($invitado);
  }

  public function lider_info() {
    $str  = "select * from admin_users where id = " .  Auth::id() . " and statusUs;";
    $invitado = DB::select($str)[0];

    return json_encode(
      array(
        'id' => Auth::id(),
        'nombre' => $invitado->name,
        'correo' => $invitado->mail,
        'telefono' => $invitado->telefono,
        'id_lider' => $invitado->id,
        'fecha_nacimiento' => $invitado->fecha_nacimiento
      )
    );
  }

  public function lider_actualizado($id, $nombre = '', $correo = '', $telefono = '', $fecha_nacimiento = '') {
    DB::table('admin_users')->where('id', $id)->update([
      'name' => $nombre ?: '',
      'mail' => $correo ?: '',
      'telefono' => $telefono ?: '',
      'fecha_nacimiento' => $fecha_nacimiento ?: ''
    ]);

    return json_encode(
      array(
        'id' => $id,
        'nombre'  => $nombre
      )
    );
  }


  public function cambio_invitado($id, $id_invitado, $id_mesa) {
    if ($id) {
      DB::table('admin_eventos_mesas_invitados')->where('id', $id)->update([
        'id_invitado' => $id_invitado
      ]);
    } else {
      DB::table('admin_eventos_mesas_invitados')->insertGetId([
        'id_mesa' => $id_mesa,
        'id_invitado' => $id_invitado
      ]);
    }

    $str  = "select * from admin_eventos_invitados where id = $id_invitado;";
    $invitado = DB::select($str)[0];

    return json_encode(
      array(
        'id'          => $id,
        'id_invitado' => $id_invitado,
        'invitado'    => $invitado
      )
    );
  }

  public function invitado_actualizado($id, $nombre = '', $sexo = '', $correo = '', $telefono = '', $fnacimiento = '') {
    $str = "select * from admin_eventos_mesas_invitados where id = $id;";
    $id_invitado = DB::select($str)[0]->id_invitado;

    $data = array(
      'nombre' => $nombre ?: ''
    );
    if ($correo) {
      $data['correo'] = $correo ?: '';
    }
    if ($telefono) {
      $data['telefono'] = $telefono ?: '';
    }
    if ($correo) {
      $data['fecha_nacimiento'] = $fnacimiento ?: '';
    }
    if ($sexo != '') {
      $data['sexo'] = $sexo ?: 0;
    }
    DB::table('admin_eventos_invitados')->where('id', $id_invitado)->update($data);

    return json_encode(
      array(
        'id' => $id,
        'nombre'  => $nombre,
        'sexo' => $sexo
      )
    );
  }

  public function lideres(){
    $str  = "select * from admin_users where roleUS = 9 and statusUs;";
    $data = DB::select($str);

    return view('admin.reservas.lideres', [
      'menubar' => $this->list_sidebar(),
      'data'    => $data
    ]);
  }

  public function agregar_lider($id = null, $nombre = null, $sexo = 0, $mayor = 0){
    if ($id) {
      $str  = "select * from admin_users where id = $id;";
      $data = DB::select($str)[0];
      $edit = true;
    } else {
      $data = [];
      $edit = false;
    }

    $str = "select id from admin_users order by id desc limit 1;";
    $uid = DB::select($str)[0]->id;

    if (@$_POST['_token'] || $nombre) {
      $data = [
        'name'       => @$_POST['nombre'] ?: $nombre,
        'sexo'       => @$_POST['sexo'] ?: $sexo,
        'mayor_edad' => @$_POST['mayor_edad'] ?: ($mayor ?: 0),
        'password'   => Hash::make('123'),
        'statusUs'   => 1,
        'roleUS'     => 2
      ];

      // dd ($data);

      $accion = 1;
      if ($id) {
        DB::table('admin_users')->where('id', $id)->update($data);
      } else {
        $data['usersys'] = 'themanor' . ($uid + 1);
        $lider = UserAdmin::create($data);

        $str = "select * from admin_eventos_invitados where nombre = '" . $lider->name . "' and estado;";
        $invitado = @DB::select($str)[0];
        if (@$invitado->id) {
          $accion = 0;

          DB::table('admin_eventos_invitados')->where('id', $invitado->id)->update([
            'id_lider' => $lider->id
          ]);
        }
      }

      if ($nombre) {
        return json_encode(
          array(
            'nombre' => $lider->name,
            'id'     => $lider->id,
            'accion' => $accion
          )
        );
      } else {
        return redirect('admin/mesas/');
      }
      exit();
    }

    return view('admin.reservas.agregar_lider', [
      'menubar' => $this->list_sidebar(),
      'data'    => $data,
      'edit'    => $edit,
    ]);
  }

  function mantenerInvitado($id = null) {
    DB::table('admin_eventos_mesas_invitados')->where('id', $id)->update([
      'repetido' => 0,
      'estado'   => 1
    ]);

    $str   = "select * from admin_eventos_mesas_invitados where id = $id;";
    $invitado = DB::select($str)[0];

    $str = "select * from admin_eventos_mesas_invitados where nombre like '%" . trim($invitado->nombre) . "%' and id not in (" . $id . ")";
    $duplicados = DB::select($str);
    foreach($duplicados as $key => $item) {
      DB::table('admin_eventos_mesas_invitados')->where('id', $item->id)->update([
        'repetido' => 0,
        'estado'   => 0
      ]);
    }

    // select * from admin_eventos_mesas_invitados where nombre like '%Fabrizio Rosmo%' and id not in (693);

    return json_encode(
      array(
        'nombre' => $invitado->nombre,
        'id'     => $invitado->id,
        'fila'   => $invitado->fila,
        'sexo'   => $invitado->sexo,
        'pagado' => $invitado->pagado,
        'repetido' => $invitado->repetido
      )
    );
  }

  function reportes() {
    return view('admin.reservas.reportes', [
      'menubar'       => $this->list_sidebar()
    ]);
  }

  private function obtener_evento_sin_asignar($id_evento = 0) {
    if (!$id_evento) {
      $str = "select * from admin_eventos where estado and fecha >= '" . date('Y-m-d') . "' order by fecha limit 1;";
      $evento = DB::select($str)[0];  
    } else {
      $str = "select * from admin_eventos where estado and id = $id_evento;";
      $evento = DB::select($str)[0];  
    }

    return $evento;
  }

  function reporte_pull($id_evento = 0) {
    $evento = $this->obtener_evento_sin_asignar($id_evento);
    $id_evento = $evento->id;

    $str = "select mesas.*, pull.nombre pull, (cantidad_invitados.mujeres * pull.monto_mujeres) total_pull_mujeres, (cantidad_invitados.hombres * pull.monto_hombres) total_pull_hombres, (pull_pagado.mujeres * pull.monto_mujeres) pull_mujeres, (pull_pagado.hombres * pull.monto_hombres) pull_hombres from admin_eventos_mesas mesas left join admin_eventos eventos on (mesas.id_evento = eventos.id) left join admin_eventos_mesas_pull pull on (mesas.id_pull = pull.id) left join (select mesas_invitados.id_mesa, sum(if(invitados.sexo = 1, 1, 0)) hombres, sum(if(invitados.sexo = 0, 1, 0)) mujeres from admin_eventos_mesas_invitados mesas_invitados left join admin_eventos_invitados invitados on (mesas_invitados.id_invitado = invitados.id) where invitados.estado and pull_pagado group by mesas_invitados.id_mesa) pull_pagado on (mesas.id = pull_pagado.id_mesa) left join (select id_mesa, sum(if(invitados.sexo = 1, 1, 0)) hombres, sum(if(invitados.sexo = 0, 1, 0)) mujeres from admin_eventos_mesas_invitados mesas_invitados left join admin_eventos_invitados invitados on (mesas_invitados.id_invitado = invitados.id) where mesas_invitados.estado and invitados.estado and mesas_invitados.estado group by id_mesa) cantidad_invitados on (mesas.id = cantidad_invitados.id_mesa) where mesas.estado and mesas.id_pull and mesas.pull and eventos.id = $id_evento;";
    $data = DB::select($str);  

    $str  = "select * from admin_eventos where estado and fecha >= '" . date('Y-m-d') . "' order by fecha;";
    $eventos = DB::select($str); 

    $array_mes = array(1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',7=>'Julio',8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre');

    return view('admin.reportes.pull_por_reservacion', [
      'menubar'   => $this->list_sidebar(),
      'data'      => $data,
      'eventos'   => $eventos,
      'array_mes' => $array_mes,
      'evento'    => $evento,
    ]);
  }

  function reporte_meseros_sin_asignar($id_evento = null) {
    $evento = $this->obtener_evento_sin_asignar($id_evento);
    $id_evento = $evento->id;

    $str = "select name, id from admin_users where statusUs and roleUs = 4 and id not in (select mesas.id_mesero from admin_eventos_venues_sectores_mesas sectores_mesas left join admin_eventos_mesas mesas on (sectores_mesas.id_mesa = mesas.id) left join admin_eventos eventos on (mesas.id_evento = eventos.id) where mesas.estado and eventos.id = $id_evento);";
    $data = DB::select($str); 

    $str  = "select * from admin_eventos where estado and fecha >= '" . date('Y-m-d') . "' order by fecha;";
    $eventos = DB::select($str); 

    $array_mes = array(1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',7=>'Julio',8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre');

    return view('admin.reservas.reporte_no_meseros', [
      'menubar'   => $this->list_sidebar(),
      'data'      => $data,
      'eventos'   => $eventos,
      'array_mes' => $array_mes,
      'evento'    => $evento,
      'id_evento' => $id_evento
    ]);
  }

  function reporte_por_pax($id_evento = null, $reporte = false) {
    $evento = $this->obtener_evento_sin_asignar($id_evento);
    $id_evento = $evento->id;

    $str = "select distinct venues.tot_ubicaciones, eventos.pax, eventos.pagado de_pago, mesas.nombre lider, mesas.id, if (invitados.invitados is null, 0, invitados.invitados) invitados, if (mesas.id_area = 1, if (invitados.invitados is null, 0, invitados.invitados), 0) invitados_mesas, if (mesas.id_area = 2, if (invitados.invitados is null, 0, invitados.invitados), 0) invitados_barras, if(mujeres.mujeres is null, 0, mujeres.mujeres) mujeres, if (hombres.hombres is null, 0, hombres.hombres) hombres, if (pagados.pagados is null, 0, pagados.pagados) pagados, if (duplicados.duplicados is null, 0 , duplicados.duplicados) duplicados, max_pax_area, mesas.id_area, if (mesas.id_area = 1, if (round(invitados / max_pax_area) is null, 1, if (round(invitados / max_pax_area) <= 0, 1, round(invitados / max_pax_area))), 0) tot_mesas, if (mesas.id_area = 2, 1, 0) tot_barras, celebraciones.celebracion from admin_eventos_mesas mesas left join admin_eventos eventos on (eventos.id = mesas.id_evento and eventos.estado) left join admin_eventos_venues venues on (eventos.id_venue = venues.id and venues.estado) left join (select count(*) invitados, id_mesa from admin_eventos_mesas_invitados where estado group by id_mesa) invitados on (mesas.id = invitados.id_mesa) left join (select count(mesas_invitados.id) mujeres, id_mesa from admin_eventos_mesas_invitados mesas_invitados left join admin_eventos_invitados invitados on (mesas_invitados.id_invitado = invitados.id) where sexo = 0 and mesas_invitados.estado group by id_mesa) mujeres on (mesas.id = mujeres.id_mesa) left join (select count(mesas_invitados.id) hombres, id_mesa from admin_eventos_mesas_invitados mesas_invitados left join admin_eventos_invitados invitados on (mesas_invitados.id_invitado = invitados.id) where sexo = 1 and mesas_invitados.estado group by id_mesa) hombres on (mesas.id = hombres.id_mesa) left join (select count(*) pagados, id_mesa from admin_eventos_mesas_invitados where pagado and estado group by id_mesa) pagados on (mesas.id = pagados.id_mesa) left join (select id_mesa, count(repetido) duplicados from admin_eventos_mesas_invitados where repetido = 1 group by id_mesa) duplicados on (mesas.id = duplicados.id_mesa) left join admin_eventos_mesas_celebraciones celebraciones on (mesas.id_celebracion = celebraciones.id)  where mesas.estado and mesas.id_evento = " . $id_evento . " order by mesas.id_area, invitados desc;";
    // echo $str; exit();
    $reporte_pax = DB::select($str); 

    $data = [];
    foreach($reporte_pax as $key => $item) {
      @$tot_mesas        += $item->tot_mesas;
      @$tot_barras       += $item->tot_barras;
      @$tot_invitados    += $item->invitados;
      @$invitados_mesas  += $item->invitados_mesas;
      @$invitados_barras += $item->invitados_barras;
      @$tot_ubicaciones   = $item->tot_ubicaciones;
      $data[$item->id_area][] = $item;
    }

    $str  = "select * from admin_eventos where estado and fecha >= '" . date('Y-m-d') . "' order by fecha;";
    $eventos = DB::select($str); 

    $array_mes = array(1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',7=>'Julio',8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre');

    if ($reporte) {
      $data['mesas_disponibles'] = $tot_ubicaciones - @$tot_mesas;
      $fecha = substr($evento->fecha, 8, 2) . ' de ' . $array_mes[(int)substr($evento->fecha, 5, 2)];
      return Excel::download(new reportePorPax($data, $fecha), 'Reporte de reservaciones ' . $evento->fecha  . ' .xlsx');
    } else {
      return view('admin.reservas.reporte_por_pax', [
        'menubar'       => $this->list_sidebar(),
        'data'          => $data,
        'total_pax'     => $data[1][0]->pax,
        'eventos'       => $eventos,
        'array_mes'     => $array_mes,
        'evento'        => $evento,
        'tot_mesas'     => $tot_ubicaciones - @$tot_mesas,
        'tot_barras'    => @$tot_barras,
        'tot_invitados' => $tot_invitados,
        'invitados_mesas' => $invitados_mesas,
        'invitados_barras'=> $invitados_barras
      ]);
    }
  }

  function reporte_estadisticas($id_evento = null) {
    $evento = $this->obtener_evento_sin_asignar($id_evento);
    $id_evento = $evento->id;

    $str  = "select count(admin_eventos_mesas_invitados.id) total from admin_eventos_mesas_invitados left join admin_eventos_mesas on (admin_eventos_mesas.id = admin_eventos_mesas_invitados.id_mesa)  where admin_eventos_mesas_invitados.estado and admin_eventos_mesas.estado and id_evento = $id_evento;";
    $total_invitados = DB::select($str)[0]->total;  

    $str  = "select count(admin_eventos_mesas_invitados.id) total from admin_eventos_mesas_invitados left join admin_eventos_mesas on (admin_eventos_mesas.id = admin_eventos_mesas_invitados.id_mesa) where admin_eventos_mesas_invitados.estado and admin_eventos_mesas.estado and pagado and id_evento = $id_evento;";
    $total_pagados = DB::select($str)[0]->total;  

    $str  = "select count(admin_eventos_mesas_invitados.id) total from admin_eventos_mesas_invitados left join admin_eventos_mesas on (admin_eventos_mesas.id = admin_eventos_mesas_invitados.id_mesa) where admin_eventos_mesas_invitados.estado and admin_eventos_mesas.estado and pagado = 0 and id_evento = $id_evento;";
    $total_no_pagados = DB::select($str)[0]->total; 

    $str  = "select count(admin_eventos_mesas_invitados.id) total from admin_eventos_mesas_invitados left join admin_eventos_mesas on (admin_eventos_mesas.id = admin_eventos_mesas_invitados.id_mesa) where admin_eventos_mesas_invitados.estado and admin_eventos_mesas.estado and cortesia = 1 and id_evento = $id_evento;";
    $total_cortesias = DB::select($str)[0]->total; 

    $str  = "select count(admin_eventos_mesas_invitados.id) total from admin_eventos_mesas_invitados left join admin_eventos_mesas on (admin_eventos_mesas.id = admin_eventos_mesas_invitados.id_mesa)  where admin_eventos_mesas_invitados.estado and admin_eventos_mesas.estado and sexo = 0 and id_evento = $id_evento;";
    $total_mujeres = DB::select($str)[0]->total; 

    $str  = "select count(admin_eventos_mesas_invitados.id) total from admin_eventos_mesas_invitados left join admin_eventos_mesas on (admin_eventos_mesas.id = admin_eventos_mesas_invitados.id_mesa)  where admin_eventos_mesas_invitados.estado and admin_eventos_mesas.estado and sexo = 1 and id_evento = $id_evento;";
    $total_hombres = DB::select($str)[0]->total;

    $str  = "select count(admin_eventos_mesas_invitados.id) total from admin_eventos_mesas_invitados left join admin_eventos_mesas on (admin_eventos_mesas.id = admin_eventos_mesas_invitados.id_mesa)  where admin_eventos_mesas_invitados.estado and admin_eventos_mesas.estado and sexo = 0 and pagado and id_evento = $id_evento;";
    $total_mujeres_pagado = DB::select($str)[0]->total; 

    $str  = "select count(admin_eventos_mesas_invitados.id) total from admin_eventos_mesas_invitados left join admin_eventos_mesas on (admin_eventos_mesas.id = admin_eventos_mesas_invitados.id_mesa)  where admin_eventos_mesas_invitados.estado and admin_eventos_mesas.estado and sexo = 1 and pagado and id_evento = $id_evento;";
    $total_hombres_pagado = DB::select($str)[0]->total; 

    $str  = "select count(admin_eventos_mesas_invitados.id) total from admin_eventos_mesas_invitados left join admin_eventos_mesas on (admin_eventos_mesas.id = admin_eventos_mesas_invitados.id_mesa)  where admin_eventos_mesas_invitados.estado and admin_eventos_mesas.estado and sexo = 0 and pagado = 0 and id_evento = $id_evento;";
    $total_mujeres_no_pagado = DB::select($str)[0]->total; 

    $str  = "select count(admin_eventos_mesas_invitados.id) total from admin_eventos_mesas_invitados left join admin_eventos_mesas on (admin_eventos_mesas.id = admin_eventos_mesas_invitados.id_mesa)  where admin_eventos_mesas_invitados.estado and admin_eventos_mesas.estado and sexo = 1 and pagado = 0 and id_evento = $id_evento;";
    $total_hombres_no_pagado = DB::select($str)[0]->total; 

    $str  = "select count(admin_eventos_mesas_invitados.id) total from admin_eventos_mesas_invitados left join admin_eventos_mesas on (admin_eventos_mesas.id = admin_eventos_mesas_invitados.id_mesa)  where admin_eventos_mesas_invitados.estado and admin_eventos_mesas.estado and sexo = 0 and cortesia = 1 and id_evento = $id_evento;";
    $total_mujeres_cortesia = DB::select($str)[0]->total; 

    $str  = "select count(admin_eventos_mesas_invitados.id) total from admin_eventos_mesas_invitados left join admin_eventos_mesas on (admin_eventos_mesas.id = admin_eventos_mesas_invitados.id_mesa)  where admin_eventos_mesas_invitados.estado and admin_eventos_mesas.estado and sexo = 1 and cortesia = 1 and id_evento = $id_evento;";
    $total_hombres_cortesia = DB::select($str)[0]->total; 

    $str  = "select count(id) total from admin_eventos_mesas where estado and id_evento = $id_evento;";
    $total_mesas = DB::select($str)[0]->total; 

    $str  = "select count(id) total from admin_eventos_mesas where estado and abierta = 1 and id_evento = $id_evento;";
    $total_abiertas = DB::select($str)[0]->total; 

    $str  = "select count(id) total from admin_eventos_mesas where estado and abierta = 0 and id_evento = $id_evento;";
    $total_cerradas = DB::select($str)[0]->total; 

    $str = "select count(*) total from (select count(admin_eventos_mesas_invitados.nombre) cantidad, id_mesa from admin_eventos_mesas_invitados left join admin_eventos_mesas on (admin_eventos_mesas.id = admin_eventos_mesas_invitados.id_mesa)where admin_eventos_mesas_invitados.estado and admin_eventos_mesas.estado and id_evento = $id_evento group by admin_eventos_mesas_invitados.nombre having cantidad > 1 order by cantidad desc) duplicados;";
    $total_duplicados = DB::select($str)[0]->total; 

    $str  = "select * from admin_eventos where estado and fecha >= '" . date('Y-m-d') . "' order by fecha;";
    $eventos = DB::select($str); 

    return view('admin.reservas.reporte_estadisticas', [
      'menubar' => $this->list_sidebar(),
      'id_evento' => $id_evento,
      'eventos' => $eventos,
      'total_invitados' => $total_invitados,
      'total_pagados' => $total_pagados,
      'total_no_pagados' => $total_no_pagados,
      'total_mujeres' => $total_mujeres,
      'total_hombres' => $total_hombres,
      'total_mujeres_pagado' => $total_mujeres_pagado,
      'total_hombres_pagado' => $total_hombres_pagado,
      'total_mujeres_no_pagado' => $total_mujeres_no_pagado,
      'total_hombres_no_pagado' => $total_hombres_no_pagado,
      'total_cortesias' => $total_cortesias,
      'total_mujeres_cortesia' => $total_mujeres_cortesia,
      'total_hombres_cortesia' => $total_hombres_cortesia,
      'total_duplicados' => $total_duplicados,
      'total_mesas' => $total_mesas,
      'total_abiertas' => $total_abiertas,
      'total_cerradas' => $total_cerradas
    ]);
  }

  public function colaboradores(){
    $str  = "select usuarios.*, roles.nameRole puesto from admin_users usuarios left join admin_roles roles on (usuarios.roleUS = roles.id) where usuarios.statusUs and roleUS > 3;";
    $data = DB::select($str);

    return view('admin.reservas.colaboradores', [
      'menubar' => $this->list_sidebar(),
      'data'    => $data,
    ]);
  }

  public function agregar_colaborador($id = null){
    if ($id) {
      $str  = "select * from admin_users where id = $id;";
      $data = DB::select($str)[0];
      $edit = true;
    } else {
      $data = [];
      $edit = false;
    }

    if (@$_POST['_token']) {
      $data = [
        'name'     => $_POST['nombre'],
        'password' => Hash::make('123'),
        'statusUs' => 1,
        'roleUS'   => $_POST['id_rol']
      ];

      $target_path = substr(getcwd(), 0, strrpos(getcwd(), '/')) . '/';

      $str = "select id from admin_users order by id desc limit 1;";
      $uid = DB::select($str)[0]->id;

      if ($id) {
        DB::table('admin_users')->where('id', $id)->update($data);
      } else {
        $data['usersys'] = 'themanor' . ($uid + 1);
        $colaborador = UserAdmin::create($data);
        $id = $colaborador->id;
      }

      $target_path = substr(getcwd(), 0, strrpos(getcwd(), '/')) . '/';
      if (@$_FILES['foto']) {
        $upload_path = $target_path . "colaboradores/" . $id . '.jpg';
        @move_uploaded_file($_FILES['foto']['tmp_name'], $upload_path);
      }

      return redirect('admin/colaboradores');
      exit();
    }

    $str  = "select * from admin_roles where disponible = 1;";
    $cargos = DB::select($str);

    return view('admin.reservas.agregar_colaborador', [
      'menubar' => $this->list_sidebar(),
      'data'    => $data,
      'edit'    => $edit,
      'cargos'  => $cargos
    ]);
  }

  public function info_control_de_ingreso() {
    $fecha = date('Y-m-d');

    $str = "select count(invitados.id) ingresados from admin_eventos_mesas_invitados invitados left join admin_eventos_mesas mesas on (invitados.id_mesa = mesas.id) left join admin_eventos eventos on (mesas.id_evento = eventos.id) where mesas.estado and invitados.estado and eventos.fecha = '$fecha' and ingreso;";
    $ingresados = DB::select($str)[0]->ingresados;

    $str = "select count(invitados.id) total_invitados from admin_eventos_mesas_invitados invitados left join admin_eventos_mesas mesas on (invitados.id_mesa = mesas.id) left join admin_eventos eventos on (mesas.id_evento = eventos.id) where mesas.estado and invitados.estado and eventos.fecha = '$fecha';";
    $total_invitados = DB::select($str)[0]->total_invitados;

    $str = "select count(invitados.id) pendientes_ingreso from admin_eventos_mesas_invitados invitados left join admin_eventos_mesas mesas on (invitados.id_mesa = mesas.id) left join admin_eventos eventos on (mesas.id_evento = eventos.id) where mesas.estado and invitados.estado and eventos.fecha = '$fecha' and ingreso = 0;";
    $pendientes_ingreso = DB::select($str)[0]->pendientes_ingreso;

    $str = "select count(sin_lista.id) sin_lista from admin_eventos_mesas_invitados_sin_lista sin_lista left join admin_eventos eventos on (sin_lista.id_evento = eventos.id) where eventos.estado and fecha = '$fecha';";
    $sin_lista = DB::select($str)[0]->sin_lista;

    // echo 'total: ' . $total_invitados . '<br>';
    // echo 'ingresados: ' . $ingresados . '<br>';
    // echo 'pendientes: ' . $pendientes_ingreso . '<br>';

    $porcentaje_pendiente = $ingresados == 0 ? '100' : floor((1 - (($total_invitados - $pendientes_ingreso) / $total_invitados)) * 100);
    $data = [
      'ingresados'            => $ingresados,
      'pendientes_ingreso'    => $pendientes_ingreso,
      'total_invitados'       => $total_invitados,
      'porcentaje_pendientes' => $porcentaje_pendiente,
      'porcentaje_ingreso'    => 100 - $porcentaje_pendiente,
      'sin_lista'             => $sin_lista
    ];

    return json_encode($data);
  }

  public function ingreso_sin_lista($id_evento = 0) {
    $ingreso_sin_lista = eventosMesasInvitadosSinListaModel::create([
      'id_evento' => $id_evento
    ]);

    return json_encode($ingreso_sin_lista);
  }

  public function control_de_ingreso() {
    $fecha = date('Y-m-d');
    // $fecha = '2023-11-11';

    $str = "select * from admin_eventos where fecha = '$fecha' and estado;";
    $evento = @DB::select($str)[0];

    $str = "select mesas.id id_mesa, mesas_invitados.id, id_invitado, invitados.nombre, mesas_invitados.ingreso, mesas.nombre mesa, mesas.id_area, meseros.name mesero from admin_eventos_invitados invitados left join admin_eventos_mesas_invitados mesas_invitados on (invitados.id = mesas_invitados.id_invitado) left join admin_eventos_mesas mesas on (mesas_invitados.id_mesa = mesas.id) left join admin_eventos eventos on (mesas.id_evento = eventos.id) left join admin_users meseros on (mesas.id_mesero = meseros.id) where invitados.estado and mesas_invitados.estado and mesas.estado and eventos.estado and eventos.fecha = '$fecha' order by invitados.nombre limit 25;";
    $data = DB::select($str);

    foreach($data as $key => $item) {
      $str = "select smesas.id, sectores.nombre, smesas.no_mesa from admin_eventos_venues_sectores_mesas smesas inner join admin_eventos_venues_sectores sectores on (smesas.id_sector = sectores.id and smesas.id_mesa = '" . $item->id_mesa . "') order by sectores.nombre, smesas.no_mesa;";
        $mesas = DB::select($str);
        $array_mesas = array();
        foreach($mesas as $key2 => $value2) {
          $array_mesas[] = $value2->nombre . $value2->no_mesa;
        }

      @$data[$key]->mesas = implode(',', $array_mesas);
      @$data[$key]->nombre_sin_acento = $this->eliminar_acentos($item->nombre);
    }

    // dd($data);

    return view('admin.reservas.ingresos', [
      'menubar' => $this->list_sidebar(),
      'evento'  => $evento,
      'data'    => $data
    ]);
  }


  public function auto_complete_ingreso($valor_busqueda = '') {
    $fecha = date('Y-m-d');
    // $fecha = '2023-11-11';
    $str = "select mesas.id id_mesa, mesas_invitados.id, id_invitado, invitados.nombre, mesas_invitados.ingreso, mesas.nombre mesa, mesas.id_area, meseros.name mesero from admin_eventos_invitados invitados left join admin_eventos_mesas_invitados mesas_invitados on (invitados.id = mesas_invitados.id_invitado) left join admin_eventos_mesas mesas on (mesas_invitados.id_mesa = mesas.id) left join admin_eventos eventos on (mesas.id_evento = eventos.id) left join admin_users meseros on (mesas.id_mesero = meseros.id) where invitados.estado and mesas_invitados.estado and mesas.estado and eventos.estado and eventos.fecha = '$fecha' and invitados.nombre like '%$valor_busqueda%' order by invitados.nombre limit 25;";
    $data = DB::select($str);

    foreach($data as $key => $item) {
      $str = "select smesas.id, sectores.nombre, smesas.no_mesa from admin_eventos_venues_sectores_mesas smesas inner join admin_eventos_venues_sectores sectores on (smesas.id_sector = sectores.id and smesas.id_mesa = '" . $item->id_mesa . "') order by sectores.nombre, smesas.no_mesa;";
        $mesas = DB::select($str);
        $array_mesas = array();
        foreach($mesas as $key2 => $value2) {
          $array_mesas[] = $value2->nombre . $value2->no_mesa;
        }

      @$data[$key]->mesas = implode(',', $array_mesas);
    }

    $html = '';
    foreach($data as $key => $item) {
      $html .= '
        <div class="row invitado_contenedor ' . ($item->ingreso ? 'bg-success text-light' : '') . '" id="ingreso_' . $item->id . '">
          <div class="col-12 border">
            <div class="row">
              <div class="col-10">
                <label class="pt-1 fs-5 d-block"> 
                  ' . $item->nombre . '
                </label>';

      if ($item->id_area == 1) {
        $html .= '<label class="pb-1 fs-5 d-block"> Mesa: ' . $item->mesa . ' (' . $item->mesas . ') / ' . $item->mesero . '</label>';
      } else {
        $html .= '<label class="pb-1 fs-5 d-block"> Barra: ' . $item->mesa . '</label>';
      }

      $html .= '
              </div>
              <div class="col-2 form-check pt-2">
                <input type="checkbox" class="form-check-input" name="ingreso" id="ingreso" value="1" rel="' . $item->id . '" style="border: 2px solid #000; box-shadow: 2px 2px black; height: 2.5rem; width: 2.5rem;" ' . ($item->ingreso ? 'checked' : '') . ' />
              </div>
            </div>
          </div>
        </div>';
    }

    // dd($data);

    return json_encode(array('html' => $html));
  }

  private function eliminar_acentos($cadena){
    
    //Reemplazamos la A y a
    $cadena = str_replace(
    array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),
    array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
    $cadena
    );

    //Reemplazamos la E y e
    $cadena = str_replace(
    array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
    array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
    $cadena );

    //Reemplazamos la I y i
    $cadena = str_replace(
    array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
    array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
    $cadena );

    //Reemplazamos la O y o
    $cadena = str_replace(
    array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
    array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
    $cadena );

    //Reemplazamos la U y u
    $cadena = str_replace(
    array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
    array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
    $cadena );

    //Reemplazamos la N, n, C y c
    $cadena = str_replace(
    array('Ñ', 'ñ', 'Ç', 'ç'),
    array('N', 'n', 'C', 'c'),
    $cadena
    );
    
    return $cadena;
  }

  public function marcar_ingreso($id_invitado = 0, $valor = 0) {
    DB::table('admin_eventos_mesas_invitados')->where('id', $id_invitado)->update(
      $data = [
        'ingreso' => $valor
      ]
    );

    return json_encode(
      array(
        'id_invitado' => $id_invitado
      )
    );
  }

  public function acreditaciones($filtro = 0, $valor = 0) {
    $str = "select distinct colaboradores.* from admin_users colaboradores right join (select mesas.* from admin_eventos_mesas mesas left join admin_eventos eventos on (mesas.id_evento = eventos.id) where eventos.fecha = '2023-10-31' and (id_jefe_1 = '" . Auth::id() . "' or id_jefe_2 = '" . Auth::id() . "')) mesas on (colaboradores.id = mesas.id_cobrador_1 or colaboradores.id = mesas.id_cobrador_2) where colaboradores.statusUS union select distinct colaboradores.* from admin_users colaboradores right join (select mesas.* from admin_eventos_mesas mesas left join admin_eventos eventos on (mesas.id_evento = eventos.id) where eventos.fecha = '2023-10-31' and (id_jefe_1 = '" . Auth::id() . "' or id_jefe_2 = '" . Auth::id() . "')) mesas2 on (colaboradores.id = mesas2.id_mesero) where colaboradores.statusUS union select distinct colaboradores.* from admin_users colaboradores right join (select mesas.* from admin_eventos_mesas mesas left join admin_eventos eventos on (mesas.id_evento = eventos.id) where eventos.fecha = '2023-10-31' and (id_jefe_1 = '" . Auth::id() . "' or id_jefe_2 = '" . Auth::id() . "')) mesas2 on (colaboradores.id = mesas2.id_seguridad_1 or colaboradores.id = mesas2.id_seguridad_2 or colaboradores.id = mesas2.id_seguridad_3) where colaboradores.statusUS;";
    $acreditaciones = DB::select($str);

    foreach($acreditaciones as $key => $item) {
      @$acreditaciones[$key]->nombre_sin_acento = $this->eliminar_acentos($item->name);
    }

    return view('admin.reservas.lista_equipo', [
      'menubar' => $this->list_sidebar(),
      'acreditaciones' => $acreditaciones
    ]);
  }

  public function marcar_equipo_usado($id_colaborador = 0, $valor, $campo = '') {
    DB::table('admin_users')->where('id', $id_colaborador)->update([
      $campo => $valor
    ]);

    return json_encode(
      array(
        'id' => $id_colaborador
      )
    );
  }

  public function borrar_invitado($id_invitado = 0) {
    DB::table('admin_eventos_mesas_invitados')->where('id', $id_invitado)->update([
      'estado' => 0
    ]);

    return json_encode(
      array(
        'id' => $id_invitado
      )
    );
  }
}
