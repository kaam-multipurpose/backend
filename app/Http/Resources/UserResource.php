<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Enums\PermissionsEnum;
use App\Enums\UserRolesEnum;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

final class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(Request $request): array
    {

        /** @var User $user */
        $user = $this->resource;

        $role = $user->roles?->first();

        return [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'phone_number' => $user->phone_number,
            'permissions' => ($role->name === UserRolesEnum::SUPER_ADMIN->value) ? PermissionsEnum::values() : $role->permissions?->pluck('name')->toArray(),
        ];
    }
}
