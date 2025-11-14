<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Utils\Seeders\RoleAndPermissionSeederUtil;
use Illuminate\Database\Seeder;

final class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RoleAndPermissionSeederUtil::run();
    }
}
