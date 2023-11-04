<?php
namespace App\adminModels;

use Illuminate\Database\Eloquent\Model;

class pedidosModel extends Model
{
  protected $guard = 'admin';
  protected $table='admin_pedidos';
  protected $fillable = [
    "id",
    "id_tipo",
    "id_usuario",
    "cliente",
    "fecha",
    "hora",
    "monto",
    "saldo",
    "devolucion",
    "aprobar",
    "id_estado",
    "estado"
  ];

}
