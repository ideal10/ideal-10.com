<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Validation\Rule;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use Auditable, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Validation rules for the optional username. No "@" allowed, so the
     * login form can tell a username from an email.
     */
    public static function usernameRules(?int $ignoreId = null): array
    {
        return [
            'nullable', 'string', 'min:3', 'max:50', 'regex:/^[a-z0-9._-]+$/i',
            Rule::unique('users', 'username')->ignore($ignoreId),
        ];
    }

    /**
     * Stored lowercase and trimmed (empty means "no username") so sign-in
     * is case-insensitive.
     */
    protected function username(): Attribute
    {
        return Attribute::set(
            fn (?string $value) => filled($value) ? mb_strtolower(trim($value)) : null,
        );
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
