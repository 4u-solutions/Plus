<?php
namespace App\adminModels;

use Illuminate\Database\Eloquent\Model;

class productosModel extends Model
{
  protected $guard = 'admin';
  protected $table='admin_productos';
  protected $fillable = [
    "id",
    "nombre",
    "precio",
    "mixers",
    "id_tipo",
    "id_producto",
    "estado",
    "created_at",
    "updated_at",
  ];

}
