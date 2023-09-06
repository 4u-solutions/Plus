<?php
namespace App\adminModels;

use Illuminate\Database\Eloquent\Model;

class pedidosPagosModel extends Model
{
  protected $guard = 'admin';
  protected $table='admin_pedido_pagos';
  protected $fillable = [
    "id",
    "id_usuario",
    "id_pedido",
    "id_tipo",
    "monto",
    "estado",
    "created_at",
    "updated_at"
  ];

}
