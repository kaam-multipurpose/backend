<?php

namespace Database\Seeders;

use App\Utils\Seeders\SuperAdminSeederUtil;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SuperAdminSeederUtil::run();
    }
}
