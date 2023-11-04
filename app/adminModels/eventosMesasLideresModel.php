<?php
namespace App\adminModels;

use Illuminate\Database\Eloquent\Model;

class eventosMesasLideresModel extends Model
{
  protected $guard = 'admin';
  protected $table='admin_eventos_mesas_lideres';
  protected $fillable = [
    "id",
    "id_mesa",
    "id_lider",
    "created_at",
    "updated_at"
  ];

}
