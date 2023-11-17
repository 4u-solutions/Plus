<?php
namespace App\adminModels;

use Illuminate\Database\Eloquent\Model;

class eventosMesasInvitadosSinListaModel extends Model
{
  protected $guard = 'admin';
  protected $table='admin_eventos_mesas_invitados_sin_lista';
  protected $fillable = [
    "id",
    "id_evento",
    "created_at",
    "updated_at"
  ];

}
