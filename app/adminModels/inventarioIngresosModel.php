<?php
namespace App\adminModels;

use Illuminate\Database\Eloquent\Model;

class inventarioIngresosModel extends Model
{
  protected $guard = 'admin';
  protected $table='admin_inventario_ingresos';
  protected $fillable = [
    "id",
    "id_producto",
    "ingreso",
    "fecha",
    "created_at",
    "updated_at"
  ];

}
