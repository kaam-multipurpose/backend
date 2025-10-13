<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $token
 * @property string $email
 * @property Carbon|null $expires_at
 * @property Carbon|null $updated_at
 */
class PasswordResetToken extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'token',
        'email',
        'expires_at',
        'created_at',
    ];

    protected $casts = [
        'token' => 'hashed',
    ];
}
