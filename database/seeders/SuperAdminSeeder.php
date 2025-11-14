<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Utils\Seeders\SuperAdminSeederUtil;
use Illuminate\Database\Seeder;

final class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SuperAdminSeederUtil::run();
    }
}
