<?php

namespace App\Policies;

use App\Actions\subscrbtions\CheckBMCForNewSubscriptions;
use App\Actions\subscrbtions\GetUserSubscrbtionTypeAction;
use App\Models\DataBase;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EditorPolicy
{
    use HandlesAuthorization;

    //check if user can add editor
    public function addEditor(User $user, DataBase $dataBase)
    {
        $getUserSubscriptionType = new GetUserSubscrbtionTypeAction();
        $subscriptionType = $getUserSubscriptionType->execute($user);
        $editorsCount = $dataBase->editors()->count();
        if ($subscriptionType == 'free') {
            return false;
        } elseif ($subscriptionType == 'basic') {
            return $editorsCount < 5;
        } elseif ($subscriptionType == 'pro') {
            return $editorsCount < 10;
        }
        return false;
    }

}
