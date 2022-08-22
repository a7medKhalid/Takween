<?php

namespace App\Policies;

use App\Actions\subscrbtions\CheckBMCForNewSubscriptions;
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

    //check if user can add rows to database
    public function addRows(User $user, DataBase $database)
    {
        $getUserSubscriptionType = new GetUserSubscrbtionTypeAction();
        $subscriptionType = $getUserSubscriptionType->execute($user);
        $rowsCount = $database->rowsCount;
        if ($subscriptionType == 'free') {
            return $rowsCount < 1000;
        } elseif ($subscriptionType == 'basic') {
            return $rowsCount < 5000;
        } elseif ($subscriptionType == 'pro') {
            return $rowsCount < 10000;
        }
        return false;
    }
}
