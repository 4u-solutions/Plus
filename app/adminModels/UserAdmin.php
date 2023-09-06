<?php

namespace App\adminModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class UserAdmin extends Authenticatable
{
    use HasFactory;
    protected $guard = 'admin';
    // protected $guard = 'admin';
    protected $table='admin_users';
    protected $fillable = [
        'id',
        'name', 
        'pago_minimo', 
        'usersys',
        'role',
        "id_sede",
        'statusUs',
        'superuser',
        'roleUS',
        'password'
    ];
    protected $hidden = [
        'password', 'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function getAuthPassword()
    {
        return $this->password;
    }
    public function role(){
      return $this->hasOne(roles::class,"id","roleUS");
    }
    public function sede(){
      return $this->hasOne(sedesModel::class,"id","id_sede");
    }

}
