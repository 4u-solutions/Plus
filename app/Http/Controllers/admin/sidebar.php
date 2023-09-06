<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
class sidebar extends Controller
{
  public function get_bar(){
    // App\adminModels\roles::find('kV1j9')->nameroles
     return Auth::user()->usersystem;
  }
}
