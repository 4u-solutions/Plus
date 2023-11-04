<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\adminModels\roles_names;
use App\adminModels\roles;
use App\adminModels\roles_names_pivots;
class rolesController extends Controller
{
  protected $redirecUlr;
  public function __construct()
  {
      $this->middleware('auth:admin');
  }
  public function index(){
    $listof=roles::get();
    $permiss=roles_names::where("publc","1")->orderBy('naccess')->get();
    foreach($permiss AS $miss){
      $permission[]=array($miss['id'],$miss['naccess']);
    }
    return view('admin.role.show',
          ['menubar'=> $this->list_sidebar(),
           'roles'=>$listof,
           'permission'=>$permission
          ]);
  }

  public function store(Request $request) {
    $validator = $request->validate([
        'nameRole' => 'required',
    ]);
    $rolesM = new roles;
    $rolesM->nameRole = $request->nameRole;
    $rolesM->save();
    return redirect()->back()->with('success','Guardado correctamente!');
  }
  public function destroy($id) {
    try {
        roles::destroy($id);
        return redirect()->back()->with('warning','Borrado correctamente!');
      }catch (\Exception $e) {
       return redirect()->back()->with('error','No se puede eliminar porque se está utilizando por un usuario del módulo -Personal-. Primero debe eliminar esta configuración.'.$e->getCode());
    }
  }
  public function update(Request $request, $id) {
    $model = new roles;
    $finded = $model::find($id);
    $dataMod = $request->only($model->getFillable());
    $finded->fill($dataMod)->save();
    roles_names_pivots::where("id_role",$id)->delete();
     foreach($request->acceds AS $values){
       $rolesM = new roles_names_pivots;
       $rolesM->id_role = $id;
       $rolesM->id_access = $values;
       $rolesM->save();
     }
    return redirect()->back()->with('info','Actualizado correctamente!');
  }
}
