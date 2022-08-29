<?php

namespace App\Models;

use App\Casts\PointCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ShoppingCenter
 *
 * @property int $id
 * @property int $manager_id
 * @property string $name
 * @property mixed $location
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Manager $manager
 * @method static \Illuminate\Database\Eloquent\Builder|ShoppingCenter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShoppingCenter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShoppingCenter query()
 * @method static \Illuminate\Database\Eloquent\Builder|ShoppingCenter whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShoppingCenter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShoppingCenter whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShoppingCenter whereManagerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShoppingCenter whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShoppingCenter whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ShoppingCenter extends Model
{
    use HasFactory;

    protected $guarded;

    protected $casts = [
        'location' => PointCast::class
    ];

    public function manager()
    {
        return $this->belongsTo(Manager::class);
    }
}
