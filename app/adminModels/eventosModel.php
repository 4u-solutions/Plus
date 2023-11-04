<?php
namespace App\adminModels;

use Illuminate\Database\Eloquent\Model;

class eventosModel extends Model
{
  protected $guard = 'admin';
  protected $table='admin_eventos';
  protected $fillable = [
    "id",
    "nombre",
    "fecha",
    'pagado',
    "created_at",
    "updated_at"
  ];

}
