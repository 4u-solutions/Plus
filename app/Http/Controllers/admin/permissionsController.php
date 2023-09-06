<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\adminModels\roles_names;

class permissionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function index(){
      $listof=roles_names::orderBy("groupacc")->get();
      return view('admin.permission.show',
            ['menubar'=> $this->list_sidebar(),
             'permissions'=>$listof
            ]);

    }

    public function store(Request $request) {

      $validator = $request->validate([
          'naccess' => 'required',
          'iconaccess' => 'required',
          'archaccess' => 'required',
      ]);
      $data["publc"]=(!empty($request->publc)?'0':'1');
      $access = new roles_names;
      $data = $request->only($access->getFillable());
      $access->fill($data)->save();
      return redirect()->route('admin.permissions.index')
      ->with('success','Guardado correctamente!');
    }
    public function destroy($id) {
      roles_names::destroy($id);
      return redirect()->route('admin.permissions.index')
      ->with('warning','Borrado correctamente!');
    }
    public function update(Request $request, $id) {
      $accesscL = new roles_names;
      $access = $accesscL::find($id);
      $data = $request->only($accesscL->getFillable());
      $data["publc"]=(!empty($request->publc)?'0':'1');
      $access->fill($data)->save();
      // dd($access);
      // return redirect('pages/aracislemler/'.$vehicle->id);
      return redirect()->route('admin.permissions.index')
      ->with('info','Actualizado correctamente!');
    }
}
