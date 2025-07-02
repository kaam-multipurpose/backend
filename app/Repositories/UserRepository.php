<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryContract;
use Illuminate\Database\Eloquent\Model;

class UserRepository extends BaseRepository implements UserRepositoryContract
{
    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    /**
     * @param string $email
     * @return Model|null
     */
    public function findByEmail(string $email): ?Model
    {
        return $this->model->newQuery()->where('email', $email)->first();
    }
}
