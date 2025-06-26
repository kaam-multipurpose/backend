<?php

namespace Database\Seeders;

use App\Util\Seeder\RoleAndPermissionSeederUtil;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RoleAndPermissionSeederUtil::run();
    }
}
