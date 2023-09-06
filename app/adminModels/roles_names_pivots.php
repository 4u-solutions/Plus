<?php

namespace App\adminModels;

use Illuminate\Database\Eloquent\Model;

class roles_names_pivots extends Model
{
  protected $guard = 'admin';
  protected $table='admin_access_roles';
  protected $fillable = ['id_role', 'id_access'];
  public function nameroles(){
    return $this->hasMany(roles_names::class, 'id','id_access');
  }
}
