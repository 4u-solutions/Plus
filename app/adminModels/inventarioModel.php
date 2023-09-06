<?php
namespace App\adminModels;

use Illuminate\Database\Eloquent\Model;

class inventarioModel extends Model
{
  protected $guard = 'admin';
  protected $table='admin_inventario';
  protected $fillable = [
    "id",
    "id_producto",
    "cantidad_inicial",
    "cantidad_final",
    "recarga",
    "fecha",
    "created_at",
    "updated_at"
  ];

}
