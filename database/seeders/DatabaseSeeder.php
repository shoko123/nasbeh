<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(PermissionsSeeder::class);

        $this->call(LocusTablesSeeder::class);
        $this->call(StoneTablesSeeder::class);
        $this->call(TagTablesSeeder::class);
        //$this->call(MediaTablesSeeder::class);
    }
}
