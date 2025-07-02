<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{

    public function __construct(protected Model $model)
    {
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function find(int|string $id): ?Model
    {
        return $this->model->find($id);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(int|string $id, array $data): bool
    {
        $model = $this->find($id);
        return $model && $model->update($data);
    }

    public function delete(int|string $id): bool
    {
        $model = $this->find($id);
        return $model ? $model->delete() : false;
    }

    public function query(): Builder
    {
        return $this->model->newQuery();
    }
}
