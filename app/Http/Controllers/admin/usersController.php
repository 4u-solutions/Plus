<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\adminModels\UserAdmin;
use App\adminModels\roles;
class usersController extends Controller
{
  protected $redirecUlr;
  public function __construct()
  {
    $this->middleware('auth:admin');
  }
  public function index(){
    $count = $this->get_country();
    $roleUsers = [];
    $listof=UserAdmin::get();
    $usersAd=roles::orderBy('nameRole')->get();
    foreach($usersAd AS $sers){
      $roleUsers[]=array($sers['id'],$sers['nameRole']);
    }

    $paises[] = ["","--Elegir país--"];
    return view('admin.user.show',
          ['menubar'=> $this->list_sidebar(),
           'users' => $listof,
           'roleUsers' => $roleUsers,
           'countries' => $paises
          ]);
  }

  public function store(Request $request) {

    // dd(isset($request->statusUs));
    $model = new UserAdmin;
    $data = $request->only($model->getFillable());
    if(!empty($request->password)){
      $data['password'] =  Hash::make($request->password);
    }else{
      unset($data['password']);
    }
    $data["statusUs"] = (isset($request->statusUs)?1:0);
    // $data['country'] =  'gt';
    $data['superuser'] =  0;
    // dd($data);
    $model->fill($data)->save();
    return redirect()->back()->with('success','Guardado correctamente!');
  }
  public function destroy($id) {
    UserAdmin::destroy($id);
    return redirect()->back()->with('warning','Borrado correctamente!');
  }
  public function update(Request $request, $id) {

    $model = new UserAdmin;
    $finded = $model::find($id);
    $dataMod = $request->only($model->getFillable());
    $dataMod["statusUs"] = (isset($request->statusUs)?1:0);
    if(!empty($request->password)){
      $dataMod['password'] =  Hash::make($request->password);
    }else{
      unset($dataMod['password']);
    }
    $finded->fill($dataMod)->save();
    return redirect()->back()->with('info','Actualizado correctamente!');
  }

  public function acceso(){
    $usuario = UserAdmin::where('id', Auth::id())->get()[0];

    $accion = false;
    if (@$_POST['action']) {
      UserAdmin::where('id', Auth::id())->update([
          'password' => Hash::make($_POST['rpass'])
      ]);

      $accion = true;
    }

    return view('admin.user.acceso',[
      'menubar' => $this->list_sidebar(),
      'usuario' => $usuario,
      'accion'  => $accion
    ]);
  }
}
