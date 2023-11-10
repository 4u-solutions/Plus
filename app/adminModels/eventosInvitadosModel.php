<?php
namespace App\adminModels;

use Illuminate\Database\Eloquent\Model;

class eventosInvitadosModel extends Model
{
  protected $guard = 'admin';
  protected $table='admin_eventos_invitados';
  protected $fillable = [
    "id",
    "nombre",
    "correo",
    "telefono",
    "fecha_nacimiento",
    "sexo",
    "es_menor",
    "created_at",
    "updated_at"
  ];

}
