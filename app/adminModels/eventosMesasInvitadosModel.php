<?php
namespace App\adminModels;

use Illuminate\Database\Eloquent\Model;

class eventosMesasInvitadosModel extends Model
{
  protected $guard = 'admin';
  protected $table='admin_eventos_mesas_invitados';
  protected $fillable = [
    "id",
    "id_mesa",
    "nombre",
    "sexo",
    "fila",
    "estado",
    "created_at",
    "updated_at"
  ];

}
