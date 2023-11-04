<?php
namespace App\adminModels;

use Illuminate\Database\Eloquent\Model;

class eventosMesasModel extends Model
{
  protected $guard = 'admin';
  protected $table='admin_eventos_mesas';
  protected $fillable = [
    "id",
    "id_evento",
    "id_lider",
    "nombre",
    "id_area",
    "cantidad",
    "abierta",
    "evento",
    "pull",
    "estado",
    "version",
    "area",
    "mesa",
    "id_mesero",
    "id_cobrador",
    "created_at",
    "updated_at"
  ];

}
