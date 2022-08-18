<?php

namespace App\Policies;

use App\Actions\subscrbtions\GetUserSubscrbtionTypeAction;
use App\Models\DataBase;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DataBasePolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {

        $getUserSubscriptionType = new GetUserSubscrbtionTypeAction();
        $subscriptionType = $getUserSubscriptionType->execute($user);

        $userDatabasesCount = $user->databases()->count();

        if ($subscriptionType == 'free') {
            return $userDatabasesCount === 0;
        } elseif ($subscriptionType == 'basic') {
            return $userDatabasesCount < 10;
        } elseif ($subscriptionType == 'pro') {
            return $userDatabasesCount < 50;
        }

        return false;

    }
}
