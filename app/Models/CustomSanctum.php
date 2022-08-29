<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

/**
 * App\Models\CustomSanctum
 *
 * @property int $id
 * @property string $tokenable_type
 * @property int $tokenable_id
 * @property string $name
 * @property string $ip
 * @property string $token
 * @property array|null $abilities
 * @property \Illuminate\Support\Carbon|null $last_used_at
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $tokenable
 * @method static \Illuminate\Database\Eloquent\Builder|CustomSanctum newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomSanctum newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomSanctum query()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomSanctum whereAbilities($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomSanctum whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomSanctum whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomSanctum whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomSanctum whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomSanctum whereLastUsedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomSanctum whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomSanctum whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomSanctum whereTokenableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomSanctum whereTokenableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomSanctum whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CustomSanctum extends SanctumPersonalAccessToken
{
    use HasFactory;

    protected $fillable =[
        'name',
        'token',
        'abilities',
        'ip',
        'expires_at',
        'tokenable_type',
        'tokenable_id'
    ];

    protected $table = 'personal_access_tokens';
}
