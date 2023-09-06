<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class basis extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $this->call([
         permissions::class,
         roles::class,
         permsroles::class,
         superuser::class,
     ]);
    }
}
