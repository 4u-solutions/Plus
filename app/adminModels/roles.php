<?php
namespace App\adminModels;

use Illuminate\Database\Eloquent\Model;

class roles extends Model
{
  protected $guard = 'admin';
  protected $table='admin_roles';
  protected $fillable = ["id","nameRole"];
  public function roles(){
    return $this->hasMany(roles_names_pivots::class, 'id_role');
  }
}
