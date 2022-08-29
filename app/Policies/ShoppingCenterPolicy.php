<?php

namespace App\Policies;


use App\Models\Manager;
use App\Models\ShoppingCenter;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShoppingCenterPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function update(Manager $manager, ShoppingCenter $shoppingCenter)
    {
        return $manager->id === $shoppingCenter->manager_id;
    }
}
