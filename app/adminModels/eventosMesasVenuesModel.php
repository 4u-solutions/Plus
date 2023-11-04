<?php
namespace App\adminModels;

use Illuminate\Database\Eloquent\Model;

class eventosMesasVenuesModel extends Model
{
  protected $guard = 'admin';
  protected $table='admin_eventos_venues';
  protected $fillable = [
    "id",
    "nombre",
    "link_waze",
    "max_pas",
    "estado",
    "created_at",
    "updated_at"
  ];

}
