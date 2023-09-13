<?php
namespace App\adminModels;

use Illuminate\Database\Eloquent\Model;

class descargasEfectivoModel extends Model
{
  protected $guard = 'admin';
  protected $table='admin_descargas_efectivo';
  protected $fillable = [
    "id",
    "id_cobrador",
    "monto",
    "fecha",
    "created_at",
    "updated_at"
  ];

}
