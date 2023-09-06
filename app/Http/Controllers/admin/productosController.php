<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\adminModels\productosModel;
use App\adminModels\tipoWaroModel;

class productosController extends Controller
{
  protected $redirecUlr;
  public function __construct()
  {
      $this->middleware('auth:admin');
      $this->back = 'admin/productos';
  }

  public function index(){
    $data = productosModel::orderBy('id_tipo')->get();
    $tipo = tipoWaroModel::orderBy('id')->get();
    foreach($tipo AS $items){
      $tipoWaro[] = array($items['id'], $items['nombre']);
    }

    $str = "select ap.id, ap.nombre from admin_productos ap left join admin_tipo_waro atw on (ap.id_tipo = atw.id) where ap.estado and atw.especial = 0 order by ap.id_tipo;";
    $arrProducto = DB::select($str);
    $productos[] = array(0, 'No se relaciona producto');
    foreach($arrProducto AS $items){
      $productos[] = array($items->id, $items->nombre);
    }

    return view('admin.productos.index', [
      'menubar'  => $this->list_sidebar(),
      'tipoWaro' => $tipoWaro,
      'productos'=> $productos,
      'data'     => $data,
    ]);
  }

  public function show($id = null) {
    $data = productosModel::find($id);

    return view('admin.productos.editar',[
      'menubar' => $this->list_sidebar(),
      'enlace' => $data->enlace
    ]);
  }

  public function store(Request $request) {
    $model = new productosModel;
    $data = $request->only($model->getFillable());
    $model->fill($data)->save();

    return redirect($this->back)->with('success','Guardado correctamente!');
  }

  public function update(Request $request, $id) {
    $model = new productosModel;
    $finded = $model::find($id);
    $data = $request->only($model->getFillable());
    $finded->fill($data)->save();

    return redirect($this->back)->with('success','Guardado correctamente!');
  }

  public function destroy($id) {
    $model = new productosModel;
    $model::find($id)->delete();

    return redirect()->back()->with('success','Borrado correctamente!');
  }
}
