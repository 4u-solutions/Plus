<?php
namespace App\Helper;
use Illuminate\Support\Facades\Storage;
use Auth;
class Helper
{
  public static function get_files($filesTo,$disk="s3"){
    $hostCeibal = ['api.ceibal-horizum.com','admin.ceibal-horizum.com','ceibal-horizum.com','horizum.dvp',
                   'pre.api.ceibal-horizum.com','pre.admin.ceibal-horizum.com','pre.ceibal-horizum.com','pre.admin.co.blckmrk.com'];
    // if(in_array($_SERVER['HTTP_HOST'],$hostCeibal)){
         $lkd = asset('storage/'.$filesTo);
         return $lkd;
    // }else{
    //   return (!empty($filesTo)?Storage::disk($disk)
    //   ->temporaryUrl($filesTo, now()->addMinutes(60)):null);
    // }

  }
  public static function breakWords($string){
    return preg_replace("/<br\s?\/?>/",'',$string);
  }
  public static function utf8D($cad){
    return utf8_decode($cad);
  }
  public static function superAdmin(){
    return (Auth::user()->superuser==1?true:false);
  }
  public static function get_country($coun=null ){
    $result = preg_replace("/[^a-zA-Z]+/", "",($coun===null?Auth::user()->country:$coun));
    return $result;
  }
  public static function get_fullcountry(){
    return Auth::user()->country;
  }
  public static function get_platform(){
    if(!self::superAdmin()){
      $is=Auth::user()->get_platform->platform;
    }else{
      $is = null;
    }
    return $is;
  }
  public static function get_env()
  {
    $envsrv=array('horizum.dvp'=>'pro',
                  'dev.horizum.com'=>'pro',
                  'pre.horizum.com'=>'pro',
                  'horizum.com'=>'pro',
                  'ceibal-horizum.com'=>'pro',
                  'api.ceibal-horizum.com'=>'pro',
                  'admin.ceibal-horizum.com'=>'pro',
                  'pre.ceibal-horizum.com'=>'pro',
                  'pre.api.ceibal-horizum.com'=>'pro',
                  'pre.admin.co.blckmrk.com'=>'pro',
                  'pre.admin.ceibal-horizum.com'=>'pro');
    $devnv=$envsrv[str_replace('www.', '', $_SERVER['HTTP_HOST'])];
    return $devnv;
  }
  public static function urlUps(){
    if(self::get_env()=='pro'){
      return 'https://pre.files.co.blckmrk.com/';
    }else{
      return 'http://pre-recursos.horizum.com/';
    }
  }
  public static function httpVerify($cad){
    if((strpos($cad, 'http') !== false)){
      return $cad;
    }else{
      if(!empty($_SERVER['HTTPS'])
        &&$_SERVER['HTTPS'] != 'off'){
          return 'https://'.$cad;
      }else{
        return 'http://'.$cad;
      }
    }

  }
}
