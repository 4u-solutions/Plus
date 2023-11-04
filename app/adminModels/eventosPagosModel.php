<?php
namespace App\adminModels;

use Illuminate\Database\Eloquent\Model;

class eventosPagosModel extends Model
{
  protected $guard = 'admin';
  protected $table='admin_eventos_pagos';
  protected $fillable = [
    "id",
    "id_invitado",
    "id_metodo",
    "monto",
    "tarjeta",
    "num_tarjeta",
    "mes_exp",
    "anio_exp",
    "cvc",
    "no_trans",
    "no_auto",
    "fecha",
    "hora",
    "id_evento",
    "id_mesa",
    "created_at",
    "updated_at"
  ];

}
