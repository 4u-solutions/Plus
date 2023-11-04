<?php
namespace App\adminModels;

use Illuminate\Database\Eloquent\Model;

class eventosPagosRubrosModel extends Model
{
  protected $guard = 'admin';
  protected $table='admin_eventos_pagos_rubros';
  protected $fillable = [
    "id",
    "id_pago",
    "id_evento",
    "id_mesa",
    "created_at",
    "updated_at"
  ];

}
