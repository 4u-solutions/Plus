<?php
namespace App\adminModels;

use Illuminate\Database\Eloquent\Model;

class parametrosModel extends Model
{
  protected $guard = 'admin';
  protected $table='admin_parametros';
  protected $fillable = [
    "id",
    "nombre",
    "identificador",
    "valor",
    "estado",
    "created_at",
    "updated_at"
  ];

}
