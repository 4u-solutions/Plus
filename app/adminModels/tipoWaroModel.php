<?php
namespace App\adminModels;

use Illuminate\Database\Eloquent\Model;

class tipoWaroModel extends Model
{
  protected $guard = 'admin';
  protected $table='admin_tipo_waro';
  protected $fillable = ["id", "nombre", "color", "especial", "estado"];
}
