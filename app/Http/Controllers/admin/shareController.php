<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\adminModels\eventosModel;
use App\adminModels\eventosMesasModel;
use App\adminModels\eventosMesasInvitadosModel;
use App\adminModels\UserAdmin;
use App\adminModels\eventosMesasLideresModel;
use App\adminModels\eventosPagosModel;
use App\adminModels\eventosPagosRubrosModel;
use App\adminModels\eventosClickPatrociniosModel;
use App\adminModels\eventosInvitadosModel;

class shareController extends Controller
{
  
  public function __construct()
  {
  }

  public function informacion_invitado($id) {
    $str  = "select * from admin_eventos_mesas_invitados where id = $id and estado;";
    $id_invitado = DB::select($str)[0]->id_invitado;

    $str  = "select * from admin_eventos_invitados where id = $id_invitado;";
    $invitado = DB::select($str)[0];

    return json_encode(
      array(
        'invitado' => $invitado,
      )
    );
  }

  public function invitado_perfil_actualizado($id, $nombre = '', $correo = '', $telefono = '', $fecha_nacimiento = '') {
    $nombre = urldecode($nombre);

    $str  = "select * from admin_eventos_mesas_invitados where id = $id and estado;";
    $id_invitado = DB::select($str)[0]->id_invitado;

    $str = "select * from admin_eventos_invitados where nombre = '" . $nombre . "' and id not in ($id_invitado);";
    $invitado = DB::select($str);

    $duplicado = false;
    if (count($invitado) <= 0) {
      DB::table('admin_eventos_invitados')->where('id', $id_invitado)->update([
        'nombre' => $nombre ?: '',
        'correo' => $correo ?: '',
        'telefono' => $telefono ?: '',
        'fecha_nacimiento' => $fecha_nacimiento ?: ''
      ]);
    } else {
      $duplicado = true;
    }

    return json_encode(
      array(
        'id' => $id,
        'nombre' => $nombre,
        'duplicado' => $duplicado
      )
    );
  }

  function informacion_para_evento($id_mesa = null) {
    $str = "select admin_eventos_mesas.*, admin_eventos.pagado, admin_eventos.listas_cerradas, admin_users.name mesero, admin_users.id id_mesero, admin_users2.name cobrador_1, admin_users2.id id_cobrador_1, admin_users4.name cobrador_2, admin_users5.id id_cobrador_2, admin_users3.name jefe_1, admin_users3.id id_jefe_1, admin_users4.name jefe_2, admin_users4.id id_jefe_2, admin_eventos_venues.link_waze, admin_eventos_mesas_pull.nombre pull, admin_eventos_mesas_lideres.id_lider from admin_eventos_mesas left join admin_eventos on (admin_eventos_mesas.id_evento = admin_eventos.id and admin_eventos.estado) left join admin_users on (admin_users.id = admin_eventos_mesas.id_mesero) left join admin_users admin_users2 on (admin_users2.id = admin_eventos_mesas.id_cobrador_1) left join admin_users admin_users5 on (admin_users5.id = admin_eventos_mesas.id_cobrador_2) left join admin_users admin_users3 on (admin_users3.id = admin_eventos_mesas.id_jefe_1) left join admin_users admin_users4 on (admin_users4.id = admin_eventos_mesas.id_jefe_2) left join admin_eventos_venues on (admin_eventos_venues.id = admin_eventos.id_venue and admin_eventos_venues.estado) left join admin_eventos_mesas_pull on (admin_eventos_mesas.id_pull = admin_eventos_mesas_pull.id) left join admin_eventos_mesas_lideres on (admin_eventos_mesas_lideres.id_mesa = admin_eventos_mesas.id) where admin_eventos_mesas.id = '$id_mesa';";
    $mesa = DB::select($str)[0];

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

    $mesa->pax = $mesa->pax ?: 20;
    for($i = 1; $i <= ceil(($mesa->listas_cerradas ? 100 : $mesa->pax) * 0.6); $i++) {
      @$data_m[$i . '-' . 0] = 0;
    }

    $str  = "select mesas_invitados.*, invitados.nombre, invitados.sexo, invitados.telefono, invitados.correo, invitados.fecha_nacimiento from admin_eventos_mesas_invitados mesas_invitados left join admin_eventos_invitados invitados on (mesas_invitados.id_invitado = invitados.id) where id_mesa = $id_mesa and sexo = 0 and mesas_invitados.estado " . ($mesa->listas_cerradas ? ' and (pagado or cortesia)' : '') . " order by nombre;";
    $invitados = DB::select($str);

    $data = array();
    foreach($invitados as $key => $item) {
      @$data_m[$item->fila . '-' . $item->sexo] = $item;
    }

    for($i = 1; $i <= (($mesa->listas_cerradas ? 100 : $mesa->pax) - ceil(($mesa->listas_cerradas ? 100 : $mesa->pax) * 0.6)); $i++) {
      @$data_h[$i . '-' . 1] = 0;
    }

    $str  = "select mesas_invitados.*, invitados.nombre, invitados.sexo, invitados.telefono, invitados.correo, invitados.fecha_nacimiento from admin_eventos_mesas_invitados mesas_invitados left join admin_eventos_invitados invitados on (mesas_invitados.id_invitado = invitados.id) where id_mesa = $id_mesa and sexo = 1 and mesas_invitados.estado " . ($mesa->listas_cerradas ? ' and (pagado or cortesia)' : '') . " order by nombre;";
    $invitados = DB::select($str);

    $data = array();
    foreach($invitados as $key => $item) {
      @$data_h[$item->fila . '-' . $item->sexo] = $item;
    }

    $str = "select eventos.*, venues.id id_venue from admin_eventos eventos left join admin_eventos_venues venues on (eventos.id_venue = venues.id) where eventos.id = '" . $mesa->id_evento  . "';";
    $evento = DB::select($str)[0];

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

    $str  = "select count(mesas_invitados.id) pagados from admin_eventos_mesas_invitados mesas_invitados left join admin_eventos_invitados invitados on (mesas_invitados.id_invitado = invitados.id) where mesas_invitados.estado and invitados.estado and id_mesa = $id_mesa and sexo = 0;";
    $total_mujeres = DB::select($str)[0]->pagados; 

    $str  = "select count(mesas_invitados.id) pagados from admin_eventos_mesas_invitados mesas_invitados left join admin_eventos_invitados invitados on (mesas_invitados.id_invitado = invitados.id) where mesas_invitados.estado and invitados.estado and id_mesa = $id_mesa and sexo = 1;";
    $total_hombres = DB::select($str)[0]->pagados;

    $str = "select * from admin_eventos where id = '" . $mesa->id_evento  . "';";
    $evento = DB::select($str)[0];

    $str = "select pull.* from admin_eventos_mesas mesas left join admin_eventos_mesas_pull pull on (mesas.id_pull = pull.id) where mesas.id = " . $id_mesa . ";";
    $pull = DB::select($str)[0];

    $str = "select count(pull_pagado) pull_pagado from admin_eventos_mesas_invitados mesas_invitados left join admin_eventos_invitados invitados on (mesas_invitados.id_invitado = invitados.id) where mesas_invitados.estado and invitados.estado and id_mesa = $id_mesa and pull_pagado = 1 and sexo = 0;";
    $pull_pagado_mujeres = DB::select($str)[0]->pull_pagado;

    $str = "select count(pull_pagado) pull_pagado from admin_eventos_mesas_invitados mesas_invitados left join admin_eventos_invitados invitados on (mesas_invitados.id_invitado = invitados.id) where mesas_invitados.estado and invitados.estado and id_mesa = $id_mesa and pull_pagado = 1 and sexo = 1;";
    $pull_pagado_hombres = DB::select($str)[0]->pull_pagado;

    return view('admin.reservas.informacion_para_evento', [
      'menubar' => $this->list_sidebar(),
      'mesa'    => $mesa,
      'evento' => $evento,
      'data_m' => @$data_m,
      'data_h' => @$data_h,
      'evento'                  => $evento,
      'data_m'                  => @$data_m,
      'data_h'                  => @$data_h,
      'meseros'                 => $meseros,
      'seguridad'               => $seguridad,
      'bartenders'              => $bartenders,
      'coordinadores'           => $coordinadores,
      'jefes'                   => $jefes,
      'bodegas'                   => $bodegas,
      'banos'                   => $banos,
      'food'                   => $food,
      'mesas_asignadas'         => $mesas_asignadas,
      'total_mujeres'         => $total_mujeres,
      'total_hombres'         => $total_hombres,
      'pull'         => $pull,
      'pull_pagado_mujeres' => $pull_pagado_mujeres,
      'pull_pagado_hombres' => $pull_pagado_hombres
    ]);
  }

  public function cargar_formulario_pago($id_invitado = 0) {
    $str      = "select invitados.*, mesas.id id_mesa, mesas.id_evento, eventos.pagado es_pagado, mesas.pull, mesas.id_pull, eventos.nombre evento, eventos.titulo, eventos.precio, eventos.fee from admin_eventos_mesas_invitados invitados left join admin_eventos_mesas mesas on (invitados.id_mesa = mesas.id) left join admin_eventos eventos on (mesas.id_evento = eventos.id) where invitados.id = '$id_invitado';";
    $data = DB::select($str)[0];

    $perfil_completo = true;
    // if (!$data->telefono || !$data->correo || !$data->fecha_nacimiento) {
    //   $perfil_completo = false;
    //   $titulo = 'DEBES ACTUALIZAR LA INFORMACIÓN  DE TU CUENTA PARA CONTINUAR';
    //   $boton  = 'Guardar';
    // } else {
      $titulo = 'PROCESO DE PAGO';
      $boton  = 'Pagar';
    // }

    $str = "select pull.* from admin_eventos_mesas mesas left join admin_eventos_mesas_pull pull on (mesas.id_pull = pull.id) where mesas.id = " . $data->id_mesa . ";";
    $pull = DB::select($str)[0];

    $view_render =  view('admin.reservas.pago', [
                      'data' => $data,
                      'pull' => $pull,
                      'perfil_completo' => $perfil_completo
                    ])->render();

    return json_encode(
      array(
        'html' => $view_render,
        'titulo'      => $titulo,
        'confirmButtonText' => $boton
      )
    );
  }

  public function emitir_pago() {
    $denegado = true;
    try {
      $rubros_pagados = array();

      if ($_POST['_token']) {
        $no_autoriacion = rand(9999999, 99999999);
        if ($no_autoriacion) {
          $ultimos_digitos = substr($_POST['tarjeta_num'], strrpos($_POST['tarjeta_num'], ' ') + 1);
          $fecha_exp       = explode('/', str_replace(' ', '', $_POST['tarjeta_fv']));

          foreach($_POST['rubro'] as $key => $item) {
            $pago = eventosPagosModel::create([
              "id_invitado" => @$_POST['id_invitado'],
              "id_metodo"   => @$_POST['metodo_pago'],
              "monto"       => $item['total'],
              "tarjeta"     => @$_POST['tarjeta_nom'],
              "num_tarjeta" => $ultimos_digitos,
              "mes_exp"     => @$fecha_exp[0],
              "anio_exp"    => @$fecha_exp[1],
              "cvc"         => @$_POST['tarjeta_cvc'],
              "no_trans"    => @$_POST['no_boleta'],
              "no_auto"     => $no_autoriacion,
              "fecha"       => date('Y-m-d'),
              "hora"        => date('H:i:s'),
            ]);

            if (@$_POST['metodo_pago'] == 2) {
              $target_path = (strpos(getcwd(), 'themanorgt') ? getcwd() :  (substr(getcwd(), 0, strrpos(getcwd(), '/')) . '/public')) . '/';
              if (@$_FILES['boleta-pago']) {
                $upload_path = $target_path . "boleta-pago/" . $pago->id . '.jpg';
                @move_uploaded_file($_FILES['boleta-pago']['tmp_name'], $upload_path);
              }
            }

            eventosPagosRubrosModel::create([
              "id_pago"  => $pago->id,
              $key       => $item['id']
            ]);

            DB::table('admin_eventos_mesas_invitados')->where('id', $_POST['id_invitado'])->update([
              ($key == 'id_evento' ? 'pagado' : 'pull_pagado') => $_POST['metodo_pago'] == 2 ? 2 : 1
            ]);


            $rubros_pagados[] = array(
              'id'    => $key,
              'monto' => $item['total']
            );
            $denegado = false;
          }
        }
      }
    } catch(Exception $e) {
      return json_encode(
        array(
          'error' => $e->getMessage()
        )
      );
    }

    return json_encode(
      array(
        'denegado'       => $denegado,
        'rubros_pagados' => $rubros_pagados,
        'id_mesa'        => $_POST['id_mesa'],
        'id_invitado'    => $_POST['id_invitado']
      )
    );
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

  public function click_patrocinio($id_patrocinio, $id_lider = 0, $id_invitado = 0) {
    $click = eventosClickPatrociniosModel::create([
      'id_patrocinio' => $id_patrocinio,
      'id_lider'      => $id_lider,
      'id_invitado'   => $id_invitado,
      'fecha'         => date('Y-m-d'),
      'hora'          => date('H:i:s')
    ]);

    if ($id_patrocinio == 1) {
      header('Location: https://bpi.gytcontinental.com.gt/OnboardingTC/');
    }
  }

  public function unificar_invitados() {
    $str = "select * from admin_eventos_invitados;";
    $invitados = DB::select($str);

    foreach($invitados as $key => $item) {
      $str = "select * from admin_eventos_mesas_invitados where nombre = '" . $item->nombre . "';";
      $invitados2 = DB::select($str);
      foreach($invitados2 as $key2 => $item2) {
        DB::table('admin_eventos_mesas_invitados')->where('id', $item2->id)->update([
          'id_invitado' => $item->id
        ]);
      }
    }

    dd('Registros actualizados');
  }

  public function eliminar_duplicados() {
    $str = "select nombre from admin_eventos_invitados group by nombre order by nombre;";
    $invitados = DB::select($str);

    foreach($invitados as $key => $item) {
      $str = "select * from admin_eventos_invitados where nombre = '" . $item->nombre . "';";
      $invitados2 = DB::select($str);
      if (count($invitados2) > 1) {
        $str = "select * from admin_eventos_invitados where nombre = '" . $item->nombre . "' and correo != '';";
        $id  = DB::select($str);

        if (count($id) == 0) {
          $str = "select * from admin_eventos_invitados where nombre = '" . $item->nombre . "' limit 1;";
          $id  = DB::select($str);
        }
        $id = $id[0]->id;

        eventosInvitadosModel::where('nombre', $item->nombre)->whereNotIn('id', array($id))->delete();
      }
    }

    dd('Duplicados eliminados');
  }
}
