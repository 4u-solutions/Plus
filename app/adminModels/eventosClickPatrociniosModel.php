<?php
namespace App\adminModels;

use Illuminate\Database\Eloquent\Model;

class eventosClickPatrociniosModel extends Model
{
  protected $guard = 'admin';
  protected $table='admin_eventos_clicks_patrocinios';
  protected $fillable = [
    "id",
    "id_patrocinio",
    "id_lider",
    "id_invitado",
    "fecha",
    "hora"
  ];

}
