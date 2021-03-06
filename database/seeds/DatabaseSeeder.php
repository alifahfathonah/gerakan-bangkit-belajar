T<?php

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
        $this->call(UserSeeder::class);
        // $this->call(ProvinceTableSeeder::class);
        // $this->call(CityTableSeeder::class);
        // $this->call(DistrictTableSeeder::class);
        $this->call(AboutTableSeeder::class);
        $this->call(AnggotaTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(AdminTableSeeder::class);
        $this->call(JenjangTableSeeder::class);
    }
}
