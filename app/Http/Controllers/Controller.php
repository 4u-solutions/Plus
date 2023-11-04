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
    if (@Auth::user()) {
      if(!$this->superAdmin()){
        $str = "select * from admin_access left join admin_access_roles on (admin_access.id = admin_access_roles.id_access) where publc and admin_access_roles.id_role = " . Auth::user()->roleUS . " order by naccess;";
        $varm = DB::select($str);
      }else{
        $varm = roles_names::where('publc', 1)->orderBy('naccess', 'ASC')->get();
      }

      $icns=array();
      foreach($varm AS $elemts){
        $dataAcces = $elemts;
        $grupi=($dataAcces->groupacc!=''?$dataAcces->groupacc:'NA');
        // dd($dataAcces);
        $groups[str_replace(" ","",$grupi)]["info"] = ["icon"=>$dataAcces->iconaccess,
                                                       "group"=>$dataAcces->groupacc];
        $groups[str_replace(" ","",$grupi)]["access"][] = ["perm"=>$dataAcces->archaccess,
                                                           "name"=>$dataAcces->naccess];
      }
    } else {
      $varm = null;
    }
    // dd($groups);
    // return ["groups"=>$groups];
    // dd($varm);
    return $varm;
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

  public function obtener_decimales($numero){
    return number_format($numero / 100, 2);
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
