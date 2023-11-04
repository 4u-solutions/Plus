<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\adminModels\dashboardModel;

class dashboardController extends Controller
{
  protected $redirecUlr;
  public function __construct()
  {
      $this->middleware('auth:admin');
      $this->back = 'admin/dashboard';
  }

  public function index(){
    return view('admin.dashboard.index', [
      'menubar' => $this->list_sidebar(),
      'data' => []
    ]);
  }

  public function show() {
    return view('admin.dashboard.index', [
      'menubar' => $this->list_sidebar(),
      'data' => []
    ]);
  }

  public function store(Request $request) {
    $model = new dashboardModel;
    $data = $request->only($model->getFillable());

    $data['id_ficha'] = $request->id_ficha;
    $data['servicio_psico'] = $request->servicio_psico ?: 0;
    $data['tiempo_legal'] = $request->tiempo_legal ?: 0;
    $data['tiempo_psico'] = $request->tiempo_psico ?: 0;

    $model->fill($data)->save();

    return redirect($this->back)->with('success','Guardado correctamente!');
  }

  public function update(Request $request, $id) {
    $model = new dashboardModel;
    $finded = $model::find($id);
    $data = $request->only($model->getFillable());

    $data['necesita_o_violencia'] = $request->necesita_o_violencia ?: 0;
    $data['necesita_o_legal']     = $request->necesita_o_legal ?: 0;
    $data['necesita_o_psico']     = $request->necesita_o_psico ?: 0;
    
    $finded->fill($data)->save();

    $this->changeLog([
      'area' => 'ficha',
      'type' => 'edicion',
      'request' => $request,
      'element_id' => $id
    ]);

    return redirect($this->back)->with('success','Guardado correctamente!');
  }

  public function destroy($id) {
    dashboardModel::destroy($id);

    return redirect()->back()->with('success','Borrado correctamente!');
  }
}
