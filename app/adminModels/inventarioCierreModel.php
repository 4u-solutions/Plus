<?php
namespace App\adminModels;

use Illuminate\Database\Eloquent\Model;

class inventarioCierreModel extends Model
{
  protected $guard = 'admin';
  protected $table='admin_inventario_cierre';
  protected $fillable = [
    "id",
    "id_cobrador",
    "fecha",
    "efectivo",
    "tarjeta",
    "aprobado",
    "created_at",
    "updated_at"
  ];

}
