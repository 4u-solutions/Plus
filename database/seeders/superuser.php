<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class superuser extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('admin_users')->insert([
            'name' => 'Sistema Admin',
            'usersys' => 'sadmin',
            'password' => Hash::make('123456'),
            'superuser' => '1',
            'roleUS' => '1',
            'statusUs' => '1',
            'country' => 'su00',

        ]);
    }
}
