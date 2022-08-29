<?php

namespace App\Models;

use App\Traits\SanctumOverride;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\Models\Manager
 *
 * @property int $id
 * @property string $name
 * @property string $family
 * @property string $email
 * @property string $password
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ShoppingCenter[] $shopping_centers
 * @property-read int|null $shopping_centers_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CustomSanctum[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|Manager newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Manager newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Manager query()
 * @method static \Illuminate\Database\Eloquent\Builder|Manager whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Manager whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Manager whereFamily($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Manager whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Manager whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Manager wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Manager whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Manager extends Authenticatable
{
    use SanctumOverride,HasFactory;

    protected $guarded;

    public function shopping_centers()
    {
        return $this->hasMany(ShoppingCenter::class);
    }

    public function images()
    {
        return $this->morphMany(Image::class,'imageable');
    }
}
