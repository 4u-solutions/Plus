<?php
namespace App\adminModels;

use Illuminate\Database\Eloquent\Model;

class asignacionModel extends Model
{
  protected $guard = 'admin';
  protected $table='admin_asignacion';
  protected $fillable = [
    "id",
    "id_cobrador",
    "id_mesero",
    "fecha",
    "columna",
    "created_at",
    "updated_at"
  ];

}
