<?php

namespace Database\Seeders;

use App\Utils\Seeders\ProductSeederUtil;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductSeederUtil::run(3, 5, true);
    }
}
