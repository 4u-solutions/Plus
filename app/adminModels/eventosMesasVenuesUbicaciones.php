<?php
namespace App\adminModels;

use Illuminate\Database\Eloquent\Model;

class eventosMesasVenuesUbicaciones extends Model
{
  protected $guard = 'admin';
  protected $table='admin_eventos_venues_ubicaciones';
  protected $fillable = [
    "id",
    "nombre",
    "id_venue",
    "id_tipo",
    "pax_porcent",
    "estado",
    "max_ubaciones",
    "created_at",
    "updated_at"
  ];

}
