<?php

namespace App\Http\Resources;

use App\Enum\PermissionsEnum;
use App\Enum\UserRolesEnum;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    public function __construct(Role $resource, protected bool $full = false)
    {
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(Request $request): array
    {
        /** @var Role $role */
        $role = $this->resource;

        $data = [
            'name' => $role->name,
            'slug' => $role->slug,
        ];

        if ($this->full) {
            $data['permissions'] = ($role->name == UserRolesEnum::SUPER_ADMIN->value) ?
                collect(PermissionsEnum::values())->toArray() : $role->getPermissionNames()->toArray();
        }

        return $data;
    }
}
