<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Model;

interface UserRepositoryContract extends BaseRepositoryInterface
{
    /**
     * @param string $email
     * @return Model|null
     */
    public function findByEmail(string $email): ?Model;
}
