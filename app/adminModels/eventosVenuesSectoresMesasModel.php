<?php
namespace App\adminModels;

use Illuminate\Database\Eloquent\Model;

class eventosVenuesSectoresMesasModel extends Model
{
  protected $guard = 'admin';
  protected $table='admin_eventos_venues_sectores_mesas';
  protected $fillable = [
    "id",
    "id_mesa",
    "id_sector",
    "no_mesa",
    "created_at",
    "updated_at"
  ];

}
