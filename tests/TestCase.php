<?php

namespace Tests;

use App\Enum\UserRolesEnum;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Traits\CreateTestUser;

abstract class TestCase extends BaseTestCase
{
    use CreateTestUser,RefreshDatabase,withFaker;

    protected Authenticatable $superAdminUser;

    protected Authenticatable $adminUser;

    protected Authenticatable $salesRepUser;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->superAdminUser = $this->createUser(UserRolesEnum::SUPER_ADMIN);
        $this->adminUser = $this->createUser(UserRolesEnum::ADMIN);
        $this->salesRepUser = $this->createUser(UserRolesEnum::SALES_REP);

        if (! defined('TEST_USER_PASSWORD')) {
            define('TEST_USER_PASSWORD', config('app.default_user_password'));
        }

    }
}
