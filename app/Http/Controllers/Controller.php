<?php
namespace App\Http\Controllers;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\adminModels\roles;
use App\adminModels\roles_names;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\adminModels\changeLogModel;
// use App\Helper\Helper;
use Auth;
use DB;
use nusoap_client;
use QrCode;
use PDF;
use Mail;
use App\Mail\sendMail;

class Controller extends BaseController
{
  use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
  private $pbkey;
  private $pvkey;
  private $RPID;
  private $URL;
  private $ctmclaim;
  private $glbl;
  protected $http;
  public static function superAdmin(){
    return (Auth::user()->superuser==1?true:false);
  }
  public function list_sidebar()
  {
    if(!$this->superAdmin()){
        $varm=roles::find(Auth::user()->roleUS)->roles()->get();
        // dd($varm);
    }else{
      $varm=roles_names::where('publc', 1)->orderBy('naccess', 'ASC')->get();
    }

    // dd($varm);
    $icns=array();
    foreach($varm AS $elemts){
      if(!$this->superAdmin()){
        $dataAcces = $elemts->nameroles()->first();
      }else{
        $dataAcces = $elemts;
      }
      $grupi=($dataAcces->groupacc!=''?$dataAcces->groupacc:'NA');
      // dd($dataAcces);
      $groups[str_replace(" ","",$grupi)]["info"] = ["icon"=>$dataAcces->iconaccess,
                                                     "group"=>$dataAcces->groupacc];
      $groups[str_replace(" ","",$grupi)]["access"][] = ["perm"=>$dataAcces->archaccess,
                                                         "name"=>$dataAcces->naccess];
    }
    // dd($groups);
    return ["groups"=>$groups];
  }
  public function  randem($longitud,$nmbr=0)
  {
          $this->long=$longitud;
          $this->key ='';
          $patrn=array('1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ','1234567890');
          $this->pattern = $patrn[$nmbr];
          $this->max = strlen($this->pattern)-1;
          for($i=0;$i < $this->long;$i++) $this->key.= $this->pattern[mt_rand(0,$this->max)];
          return $this->key;
  }
  public static function get_month($num,$yep=0)
  {
    $month=array(1=>'enero',2=>'febrero',3=>'marzo',4=>'abril',5=>'mayo',6=>'junio',7=>'julio',8=>'agosto',9=>'septiembre',10=>'octubre',11=>'noviembre',12=>'diciembre');
    $nm=(int)$num;
    return ($yep==1?substr($month[$nm], 0, 3):($yep==2?$month:$month[$nm]));
  }
  public static function get_day($daysel)
  {
    $day=array("Mon"=>'Lunes',"Tue"=>'Martes',"Wed"=>'Miercoles',"Thu"=>'Jueves',"Fri"=>'Viernes',"Sat"=>'Sabado',"Sun"=>'Domingo');
    return $day[$daysel];
  }
  public function usergenerate($namefnl,$tipo='',$mail='info@horizum.com')
  {
    $usm=explode(' ',$namefnl);
    $codus=substr(MD5($namefnl.$this->randem(6)),1,4);
    $conter=count($usm);
    switch($conter)
    {
      case 1:{
        $nomcomplet=$namefnl;
        $usesis=strtolower($usm[0]).$codus;
      }break;
      case 2:{
        $nomcomplet=$usm[0].", ".$usm[1];
      }break;
      case 3:{
        $nomcomplet=$usm[0]." ".$usm[1].", ".$usm[2];
      }break;
      case 4:{
        $nomcomplet=$usm[0]." ".$usm[1].", ".$usm[2]." ".$usm[3];
      }break;
      case 5:{
        $nomcomplet=$usm[0]." ".$usm[1]." ".$usm[2].", ".$usm[3]." ".$usm[4];
      }break;
      case 6:{
        $nomcomplet=$usm[0]." ".$usm[1]." ".$usm[2]." ".$usm[3].", ".$usm[4]." ".$usm[5];
      }
    }
    $usepass=self::usergeneratescon($usm,$tipo);
    $anempar=explode(",",$nomcomplet);
    //var_dump($anempar);
    $pass=$this->randem(6,1);
    $connectIn = 10;
    // $connectIn = $this->createuser(["user"=>$usepass["user"],
    //                    "pass"=>$usepass["pass"],
    //                    "name"=>TRIM($anempar[0]),
    //                    "lastname"=>(!empty($anempar[1])?TRIM($anempar[1]):TRIM($anempar[0])),
    //                     "mail"=>$mail]);
    return array("name"=>$nomcomplet,"namsep"=>TRIM($anempar[0]),
    "lastsep"=>(!empty($anempar[1])?TRIM($anempar[1]):TRIM($anempar[0])),
           "user"=>$usepass["user"],"pass"=>$usepass["pass"],
         "connectId"=>$connectIn);
  }
  private function usergeneratescon($namefnl,$tipi)
  {
    $clename=array();
    foreach($namefnl AS $name){
      $clename[]=self::weirdcharacters($name);
    }
    //var_dump($clename);
    $usesis=str_replace(" ","",substr(TRIM($clename[0]), 0,3).(!isset($clename[2])?(!isset($clename[1])?TRIM($clename[0]):TRIM($clename[1])):TRIM($clename[2]))."_".$tipi.$this->randem(2,1).$this->randem(2));
    $pass=$this->randem(6,1);
    return array("user"=>self::quitar_tildes(strtolower($usesis)),"pass"=>$pass);
  }
  public  function quitar_tildes($cadena)
  {
    $no_permitidas= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","Ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹","/","*","#","&");
    $permitidas= array    ("a","e","i","o","u","A","E","I","O","U","n","N","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E","","","","");
    $texto = str_replace($no_permitidas, $permitidas ,$cadena);
    return preg_replace('/[\W]\s/', '', $texto);
  }
  public function weirdcharacters($string)
  {
    $vallm='';
    $chi=str_split(self::quitar_tildes($string));
    foreach($chi AS $valls){
      $vallm.=(mb_detect_encoding($valls, mb_detect_order(), true)!==false?$valls:'');
    }
    return $vallm;
  }
  public function uploadFiles($file,$path,$owname=1,$onlyName=false,$prefixTMP='')
  {
    $extension = $file->getClientOriginalExtension();
    if($owname===0){
      $name = $prefixTMP.self::FilenameSafe($file->getClientOriginalName());
    }else{
      $name= $prefixTMP.$this->randem(35).'.'.$extension;
    }
    $filePath = $path . $name;
    Storage::put('public/'.$filePath, file_get_contents($file));
    if($onlyName==false){
      return $filePath;
    }else if($onlyName==true){
      return $name;
    }
  }
  public static function FilenameSafe($filename)
  {
    return preg_replace('/\s+/', "-", trim(trim(preg_replace('/[^A-Za-z0-9_.\-]/', " ", $filename), ".")));
  }
  public function get_files($filesTo,$disk="s3",$check=0)
  {
    if(!empty($filesTo)){
      // dd(file_exists(public_path().'/storage/'.$filesTo));
      // dd(file_exists(public_path().'storage/'.$filesTo));
      if(file_exists(public_path().'/storage/'.$filesTo)){
        $lkd = asset('storage/'.$filesTo);
        return $lkd;
      }else{
        return false;
      }

    }
    // return (!empty($filesTo)?Storage::disk($disk)
    //     ->temporaryUrl($filesTo, now()->addMinutes(180)):null);
  }
  public function deleteFiles($files)
  {
    if(!empty($files)){
      Storage::delete($files);
      return true;
    }
    // Storage::disk('s3')->delete($files);
  }

    /*
    *
    *SANTILLANA CONNECT
    *
    */
  public function differToday($times)
  {
    $now = new DateTime();
    $then = new DateTime(date("Y-m-d H:s:i",$times));
    $diff = $now->diff($then);
    $mins = $diff->format('%i');
    $hors = $diff->format('%h')*60;
     return ($mins+$hors);
  }
  public function superuser(){
    // dd(Auth::user());
    return $this->superAdmin();
  }
  public function get_userAdmin(){
    // dd(Auth::user());
    return Auth::user()->usersys;
  }

  public function esPromotor(){
    if(Auth::user()->roleUS==3){
      return true;
    }
    return false;
  }
  public function get_country(){
    return Auth::user()->country;
  }
  public function onlyParams($params,$data){
    $clean = [];
      foreach($data AS $key=>$vales){
        if($vales !== NULL && $vales !== FALSE && $vales !== ''
          &&in_array($key,$params)){
            $clean[$key] = $vales;
        }
      }
      return $clean;
  }
  public function getDomain(){
    return str_replace("www.","",request()->server('SERVER_NAME'));
  }
  public function getPrefCoun($codco=null){
    if(!empty($codco)){
      $countries = array('su'=>'México','gt'=>'Guatemala','ec'=>'Ecuador','sv'=>'El Salvador',
      'hn'=>'Honduras','ni'=>'Nicaragua','cr'=>'Costa Rica',
      'pa'=>'Panamá','co'=>'Colombia','cl'=>'Chile','uy'=>'Uruguay',
      'fi'=>'Finlandia','pr'=>'Puerto Rico','pe'=>'Perú','py'=>'Paraguay',
      'bo'=>'Bolivia','pd'=>'País Demo1','mx'=>'México',""=>'NA','de'=>'Alemania');
      preg_match_all('!\d+!', $codco, $matches);
      return $countries[str_replace($matches[0],'',$codco)].$matches[0][0];
    }else{
      return 'NA';
    }

  }
  public function getCountries(){
    return ['su'=>'Guatemala super admin','gt'=>'Guatemala','ec'=>'Ecuador','sv'=>'El Salvador',
      'hn'=>'Honduras','ni'=>'Nicaragua','cr'=>'Costa Rica',
      'pa'=>'Panamá','co'=>'Colombia','cl'=>'Chile','uy'=>'Uruguay',
      'fi'=>'Finlandia','pr'=>'Puerto Rico','pe'=>'Perú','py'=>'Paraguay',
      'bo'=>'Bolivia','pd'=>'País Demo1','mx'=>'México','de'=>'Alemania'];
  }
  public function countryPrefix($namefind,$modelBase,$prefixCount='country',$filcon=1){
    // where("country",$this->get_country())
    $prefix = $countryList = $prefixClean = [];
    $countrsWrh = ($filcon==1?[["country",$this->get_country()]]:[]);
    $prfij=$modelBase::select('packagebook')
    ->where($countrsWrh)
    ->orderBy('packagebook')
    ->groupBy('packagebook')->get();
    foreach($prfij AS $listP){
      if(isset($listP["packagebook"]))
        $prefix[]=["value"=>$listP["packagebook"],"text"=>$listP["packagebook"]];
        $prefixClean[] = [$listP["packagebook"],$listP["packagebook"]];
    }
    // dd($prefix);
    $countryC=$modelBase::select($prefixCount)
    ->where($countrsWrh)
    ->orderBy($prefixCount)->groupBy($prefixCount)->get();
    foreach($countryC AS $listC){
      $countryList[]=["value"=>$listC[$prefixCount],"text"=>$this->getPrefCoun($listC[$prefixCount])];
    }
    $find=[];
    if(isset($prefix[0]["text"])&&empty($_GET["pref"])){
      $find[]=['packagebook',$prefix[0]["text"]];
    }
      if(!empty($_GET["pref"]))
        $find=[['packagebook',$_GET["pref"]]];
      if(!empty($_GET["text"]))
        $find[]=[$namefind,'like','%'.$_GET["text"].'%'];
      if(!empty($_GET["cont"]))
        $find[]=[$prefixCount,$_GET["cont"]];
   return ["prefix"=>$prefix,"countries"=>$countryList,
           "where"=>$find,"cleanPref"=>$prefixClean];
  }
  public function prefixbyCountry($exis=null){
    $prefixs = [];
    $campain=codes_projectModel::where("country",$this->get_country())->get();
    foreach($campain AS $capm){
      foreach($capm->prefixes()
      ->orderBy("prefixbook")->groupBy("prefixbook")->get() AS $alme){
        $prefixs[]=["code"=>$alme["prefixbook"],
        "name"=>$alme["prefixbook"],
        "selected"=>($exis!=null&&$exis==$alme["prefixbook"]?true:false)];
      }
    }
    return $prefixs;
  }
  public function prefixbyCountryClean(){
    $toc = $this->prefixbyCountry();
    $Pfix[] = ["","--Seleccionar--"];
    foreach($toc AS $elems){
      $Pfix[] = [$elems["code"],$elems["code"]];
    }
    // dd($Pfix);
    return $Pfix;
  }

  public function generalReversePay($data){
      // $idPayment = $request->id; traerlo de la tabla payments
      $this->pdata = new paymentModel;

      $auditNumber = $data['auditNumber'];
      $client_ip = $data['client_ip'];

      $client = new nusoap_client('https://epaytestvisanet.com.gt/paymentcommerce.asmx?WSDL', true);

      $url = "https://epaytestvisanet.com.gt/paymentcommerce.asmx?WSDL";
      $param=array(
          'posEntryMode' => "012" //Método de entrada
          ,'pan' => ""
          ,'expdate' => ""
          ,'amount' => ""
          ,'track2Data' => ""
          ,'cvv2' => ""
          ,'paymentgwIP' => "190.149.69.135" //IP WebService Visanet
          ,'shopperIP' => $client_ip //"190.149.168.54" //IP Cliente que realiza la compra
          ,'merchantServerIP' => "67.205.167.98" //IP Comercio integrado a VisaNet
          ,'merchantUser' => "76B925EF7BEC821780B4B21479CE6482EA415896CF43006050B1DAD101669921" //Usuario
          ,'merchantPasswd' => "DD1791DB5B28DDE6FBC2B9951DFED4D97B82EFD622B411F1FC16B88B052232C7" //Password
          ,'terminalId' => "77788881" //Terminal
          ,'merchant' => "00575123" //Afiliacion
          ,'messageType' => "0400" //Mensaje de REVERSA
          ,'auditNumber' => $auditNumber // "990628" //Correlativo ciclico de transaccion de 000001 hasta 999999
          ,'additionalData' => "" //Datos adicionales cuotas o puntos
      );
      $params = array(array('AuthorizationRequest' => $param));

      $client = new nusoap_client($url, 'wsdl');
      $client->connection_timeout = 10;

      try
      {
          $result = $client->call('AuthorizationRequest',$params);
      }
      catch(SoapFault $e)
      {
          $result['response']['responseText'] = $this->parseResponseCode($result['response']['responseCode']);

          $this->pdata->no_tarjeta = '';
          $this->pdata->amount = '';
          $this->pdata->shopper_ip = $client_ip;
          $this->pdata->status = 'failed';
          $this->pdata->user_id = '';
          $this->pdata->tokenO =  '';

          $this->pdata->audit = $result['response']['auditNumber'];
          $this->pdata->reference = $result['response']['referenceNumber'];
          $this->pdata->authorization = $result['response']['authorizationNumber'];
          $this->pdata->response = $result['response']['responseCode'];
          $this->pdata->responseText = $result['response']['responseText'];
          $this->pdata->messageType = $result['response']['messageType'];
          $this->pdata->transactionType = 'Reversa';
          $this->pdata->save();

          return false;
      }

      $result['response']['responseText'] = $this->parseResponseCode($result['response']['responseCode']);

      $this->pdata->no_tarjeta = '';
      $this->pdata->amount = 0;
      $this->pdata->shopper_ip = $client_ip;
      $this->pdata->status = 'success';
      $this->pdata->user_id = 0;
      $this->pdata->tokenO =  '';

      $this->pdata->audit = $result['response']['auditNumber'];
      $this->pdata->reference = $result['response']['referenceNumber'];
      $this->pdata->authorization = $result['response']['authorizationNumber'];
      $this->pdata->response = $result['response']['responseCode'];
      $this->pdata->responseText = $result['response']['responseText'];
      $this->pdata->messageType = $result['response']['messageType'];
      $this->pdata->transactionType = 'Reversa';
      $this->pdata->save();

      return $result;
  }

  public function parseResponseCode($code){
      switch($code){
          case '00':
              return 'Aprobada';
              break;
          case '01':
          case '02':
              return 'Refiérase al emisor';
              break;
          case '05':
              return 'Transacción no aceptada';
              break;
          case '12':
              return 'Transacción inválida';
              break;
          case '13':
              return 'Monto inválido';
              break;
          case '19':
              return 'Transacción no realizada, intente de nuevo';
              break;
          case '31':
              return 'Tarjeta no soportada por switch';
              break;
          case '35':
              return 'Transacción ya ha sido ANULADA';
              break;
          case '36':
              return 'Transacción a ANULAR no EXISTE';
              break;
          case '37':
              return 'Transacción de ANULACION REVERSADA';
              break;
          case '38':
              return 'Transacción a ANULAR con Error';
              break;
          case '41':
              return 'Tarjeta Extraviada';
              break;
          case '43':
              return 'Tarjeta Robada';
              break;
          case '51':
              return 'No tiene fondos disponibles';
              break;
          case '57':
              return 'Transacción no permitida';
              break;
          case '58':
              return 'Transacción no permitida en la terminal';
              break;
          case '65':
              return 'Límite de actividad excedido';
              break;
          case '80':
              return 'Fecha de Expiración inválida';
              break;
          case '89':
              return 'Terminal inválida';
              break;
          case '91':
              return 'Emisor no disponible';
              break;
          case '94':
              return 'Transacción duplicada';
              break;
          case '96':
              return 'Error del sistema, intente más tarde';
              break;
          default:
              return 'Error desconocido';
              break;
      }
  }


  public function getCorrelativo($pdata){
      // $correlativo_query = paymentModel::whereNotNull('correlativo')->orderBy('correlativo', 'desc')->first();
      // $correlativo = $correlativo_query ? (int)$correlativo_query->correlativo : 0;
      $pdata->save();
      $correlativo = $pdata->id;
      // $correlativo++;
      if ($correlativo > 999999){
          $correlativo = 1;
      }
      $padded = str_pad($correlativo, 6, "0", STR_PAD_LEFT);
      $pdata->fill(["correlativo" => $padded])->save();

      // for ($i = 0; $i < 1000; $i++){
      //     $correlativo++;
      //     if ($correlativo > 999999){
      //         $correlativo = 1;
      //     }
      //     $padded = str_pad($correlativo, 6, "0", STR_PAD_LEFT);
      //
      //     $data = paymentModel::where("correlativo", $padded)->get();
      //     //dd(count($data), $padded);
      //
      //     // si no existe el correlativo se guarda y sale de la funcion
      //     if (count($data) == 0){
      //
      //       break;
      //       return;
      //     }
      // }
  }

  public function obtener_decimales($numero){
    return number_format($numero / 100, 2);
  }
  public function sendTickets($codes,$infoVenta,$infoUsuario) {

    // dd($ticketInfo);
    // dd($codes,$infoVenta,$infoUsuario);
    $fileQr = [];
    $exCode = $codes;
    foreach($codes AS $value){
      $png = QrCode::format('png')->size(350)->generate($value["code"]);
      $png = base64_encode($png);
      $data = ['qrimage'=>$png,
               'name'=>$infoUsuario["nombre"],
               'phone'=>$infoUsuario["telefono"],
               'mail'=>$infoUsuario["correo"],
               'nombreEvento'=>$infoVenta["nombreEvento"],
               'fechaEvento'=>$infoVenta["fechaEvento"],
               'horaEvento'=>$infoVenta["horaEvento"],
               'fecha_compra'=>$infoVenta["fecha_compra"],
               'soldin'=>$infoVenta["vendidoen"],
               'cod'=>$value["code"],
               'localidad'=>$value["localidad"],
               'localidadReal'=>$value["localidadReal"],
               'asiento'=>$value["asiento"],
               'cost'=>$value["precio"],];
      $customPaper = [0,0,300,800];
      $pdf = PDF::loadView('admin.pdf.show', $data)
                ->setPaper($customPaper, 'portrait');
      $test = Storage::put('public/tickets/'.$value["code"].'.pdf',$pdf->output()) ;
      // dd($test);
      if (Storage::exists('public/tickets/'.$value["code"].'.pdf')) {
        $fileQr[]= public_path('storage/tickets/'.$value["code"].'.pdf');
      }
      // dd($test);
    }
    // dd($fileQr);
    $data = ["mail"=>$infoUsuario["correo"],
             "name"=>$infoUsuario["nombre"],
             "titulo"=>$infoVenta["nombreEvento"],
             "subject"=>$infoVenta["nombreEvento"].' - tus tickets',
             "attachemnts"=>$fileQr];
             // ->to($this->data["mail"], $this->data["name"])
   Mail::to($infoUsuario["correo"])->send(new sendMail($data));
   // $finded = userTicketsModel::find($id);
   // $finded->statusMail = 'Reenviado';
   // $finded->save();
   return redirect()->back()->with('success','Enviado correctamente!');
  }
  function obtener_asientos_estados($id_evento){
    $queryString = "select c.estado,c.tipo,ct.id_localidad, ct.ubicacion_comprada ".
                   "from compras c ".
                   "inner join compras_tickets ct ".
                   "on c.id = ct.id_compra ".
                   "where (c.id_evento = ".$id_evento." and c.estado in ('comprado','reservado')) ".
                   "or (c.id_evento = ".$id_evento." and c.estado!='comprado' and '".date("Y-m-d H:i:s")."' < (hora_de_compra + INTERVAL '10 MINUTES')) ".
                   "or (c.tipo='asientoReservado' and c.id_evento=".$id_evento.") ".
                   "order by c.id,ct.ubicacion_comprada";

    $reservados = DB::select(DB::raw($queryString));

    // dd($reservados);
    $compras = [];
    $reservas = [];
    if(count($reservados) > 0){
      foreach($reservados AS $valores){
        if(strtolower($valores->estado)!='comprado'||$valores->tipo=='asientoReservado'){
          $reservas[$valores->id_localidad][] = $valores->ubicacion_comprada;
        }else if(strtolower($valores->estado)=='comprado'){
          $compras[$valores->id_localidad][] = $valores->ubicacion_comprada;
        }
      }
    }
    foreach($reservas AS $ky =>$ticketx){
        $reservas[$ky] = array_values($reservas[$ky]);
    }
    foreach($compras AS $kJ =>$ticketC){
        $compras[$kJ] = array_values($compras[$kJ]);
    }
    return ['comprados'=>$compras,'reservados'=>$reservas];

  }

  public function changeLog($params){
      $request = isset($params['request']) ? $params['request'] : null;
      $area = isset($params['area']) ? $params['area'] : null;
      $type = isset($params['type']) ? $params['type'] : null;
      $element_id = isset($params['element_id']) ? $params['element_id'] : null;

      $userModel = Auth::user();
      $user = $userModel->name.'-'.$userModel->usersys;

      $baseModel = new changeLogModel();
      $data = $request->only($baseModel->getFillable());
      $data['user'] = $user;
      $data['area'] = $area;
      $data['type'] = $type;
      $data['element_id'] = $element_id;

      $baseModel->fill($data)->save();
  }
}
