<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\adminModels\comprasModel;
class userModel extends Model
{
    protected $table='users';
    protected $fillable = [
        'name',
        'email',
        'lastname',
        'phone',
        'nit',
        'gender',
        'country',
        'password',
        'termsand',
        'birth',
    ];
    public function tickets(){
      return $this->hasMany(comprasModel::class, 'id_comprador');
    }

}
