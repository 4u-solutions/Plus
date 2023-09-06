<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\adminModels\_Model;

class plantillaController extends Controller
{
  protected $redirecUlr;
  public function __construct()
  {
      $this->middleware('auth:admin');
      $this->back = 'admin/_';
  }

  public function index(){
    $data   = _Model::get();

    return view('admin._.index', [
      'menubar' => $this->list_sidebar(),
      'data' => $data,
    ]);
  }

  public function show($id = null) {
    $data = _Model::find($id);

    return view('admin._.editar',[
      'menubar' => $this->list_sidebar(),
      'enlace' => $data->enlace
    ]);
  }

  public function store(Request $request) {
    $model = new _Model;
    $data = $request->only($model->getFillable());
    $model->fill($data)->save();

    return redirect($this->back)->with('success','Guardado correctamente!');
  }

  public function update(Request $request, $id) {
    $model = new _Model;
    $finded = $model::find($id);
    $data = $request->only($model->getFillable());
    $finded->fill($data)->save();

    return redirect($this->back)->with('success','Guardado correctamente!');
  }

  public function destroy($id) {
    $model = new _Model;
    $model::find($id)->delete();

    return redirect()->back()->with('success','Borrado correctamente!');
  }
}
