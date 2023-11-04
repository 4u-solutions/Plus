<?php
namespace App\adminModels;

use Illuminate\Database\Eloquent\Model;

class listaMeserosModel extends Model
{
  protected $guard = 'admin';
  protected $table='admin_lista_meseros';
  protected $fillable = [
    "id",
    "id_mesero",
    "fecha",
    "created_at",
    "updated_at"
  ];

}
