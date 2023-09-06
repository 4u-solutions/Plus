<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class permsroles extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('admin_access_roles')->insert([
          ['id_role' => '1',
           'id_access' => '1'],
          ['id_role' => '1',
           'id_access' => '2'],
          ['id_role' => '1',
           'id_access' => '3']

      ]);
    }
}
