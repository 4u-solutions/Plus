<?php
namespace App\adminModels;

use Illuminate\Database\Eloquent\Model;

class pedidosDetalleModel extends Model
{
  protected $guard = 'admin';
  protected $table='admin_pedidos_detalle';
  protected $fillable = [
    "id",
    "id_pedido",
    "id_producto",
    "cantidad",
    "subtotal",
    "contable",
    "aprobado",
    "despachado",
    "estado",
    "created_at",
    "updated_at"
  ];

}
