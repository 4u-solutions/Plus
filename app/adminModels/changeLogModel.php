<?php
namespace App\adminModels;

use Illuminate\Database\Eloquent\Model;

class changeLogModel extends Model
{
  protected $guard = 'admin';
  protected $table = 'admin_changeLog';
  protected $fillable = [
                          'user', // usuario que hizo el cambio
                          'area', // acceso en el que se hizo el cambio (compañia, colaborador, etc)
                          'type', // tipo de cambio (creacion, edicion, eliminacion)
                          'comment', // comentario sobre el cambio
                          'element_id', // id del elemento modificado (compañia, colaborador, etc), correlacionar con area
                      ];

}
