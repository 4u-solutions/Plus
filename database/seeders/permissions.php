<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class permissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('admin_access')->insert([
           ['id'=>1,
            'naccess' => 'Roles de usuario',
            'archaccess' => 'roles',
            'publc' => '1',
            'groupacc' => 'Configuración'],
           ['id'=>2,
           'naccess' => 'Personal',
            'archaccess' => 'users',
            'publc' => '1',
            'groupacc' => 'Configuración'],
            ['id'=>3,
            'naccess' => 'Accesos',
             'archaccess' => 'permissions',
             'publc' => '1',
             'groupacc' => 'Configuración'],
           ['id'=>4,
           'naccess' => 'Enviar tickets',
            'archaccess' => 'sendtickets',
            'publc' => '1',
            'groupacc' => 'Administracion']
          ]);
    }
}
