<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\userModel;
use App\Models\userTicketsModel;
use Illuminate\Support\Facades\Hash;
use Auth;
class ordersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('verified');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
      $buyings = userTicketsModel::where([["idUser",Auth::user()->id]])->get()->toArray();
      // dd($buyings);
        return view('front.orders',[
          "buyings"=>$buyings

        ]);
    }
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users']
        ]);
    }

     function create(Request $request)
    {
      // dd($request->paymethod);
      $model=new userModel;
      $data = $request->only($model->getFillable());
      $data["password"] = Hash::make('!ticketN!um@rK440#2O22');
      $datos = userModel::create($data);
      $tickets= new userTicketsModel;
      $dataTK = $request->only($tickets->getFillable());
      $tickeTP = $request->amount;
      $tickeTP = explode('_',$tickeTP);
      $idUser=$datos->id;
      $dataTK["idUser"] = $idUser;
      $dataTK["concert"] = 'canibal corpse 24 mayo 2022';
      $dataTK["typeTicket"] = $tickeTP[0];
      $dataTK["cost"] =  $tickeTP[1];
      $dataTK["typePayment"] = (!empty($request->paymethod)?'tarjeta':'efectivo');


      $tickets->fill($dataTK)->save();


      // dd($data);
      // userModel::create([
      //     'name' => $data['name'],
      //     'email' => $data['email'],
      //     'lastname' => $data['lastname'],
      //     'phone' => $data['phone'],
      //     'country' => $data['country'],
      //     'nit' => $data['nit']
      // ]);

        return redirect()->route('orders')->withFragment('#new')->with('success','Guardado correctamente!');
    }
}
