<?php

namespace App\Http\Resources;

use App\Enum\PermissionsEnum;
use App\Enum\UserRolesEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $role = optional($this->roles->first());

        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'permissions' => ($role->name == UserRolesEnum::SUPER_ADMIN->value) ? PermissionsEnum::values() : optional($role->permissions)->pluck('name')->toArray(),
        ];
    }
}
