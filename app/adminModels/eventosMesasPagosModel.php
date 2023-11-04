<?php
namespace App\adminModels;

use Illuminate\Database\Eloquent\Model;

class eventosMesasPagosModel extends Model
{
  protected $guard = 'admin';
  protected $table='admin_eventos_mesas_pagos';
  protected $fillable = [
    "id",
    "id_invitado",
    "fecha",
    "hora",
    "created_at",
    "updated_at"
  ];

}
