<?php
namespace App\adminModels;

use Illuminate\Database\Eloquent\Model;

class tipoPedidoModel extends Model
{
  protected $guard = 'admin';
  protected $table='admin_tipo_pedido';
  protected $fillable = [
    "id",
    "nombre",
    "feathder",
    "es_admin"
  ];

}
