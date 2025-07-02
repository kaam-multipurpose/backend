<?php

namespace Tests;

use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Traits\CreateTestUser;

abstract class TestCase extends BaseTestCase
{
    use  RefreshDatabase, WithFaker, CreateTestUser;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleAndPermissionSeeder::class);

        if (!defined('TEST_USER_PASSWORD')) {
            define('TEST_USER_PASSWORD', config('app.default_user_password'));
        }

    }
}
