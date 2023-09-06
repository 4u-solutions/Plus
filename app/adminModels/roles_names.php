<?php

namespace App\adminModels;

use Illuminate\Database\Eloquent\Model;

class roles_names extends Model
{
  protected $guard = 'admin';
  protected $table='admin_access';
  protected $fillable = [
    'naccess',
    'archaccess',
    'iconaccess',
    'orden',
    'publc',
    'principal',
    'groupacc',
    'applyon'
  ];

}
