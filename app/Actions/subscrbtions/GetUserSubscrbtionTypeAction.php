<?php

namespace App\Actions\subscrbtions;

use App\Models\User;

class GetUserSubscrbtionTypeAction
{
    public function execute(User $user)
    {
        //check if user has subscription and it is active
        if ($user->subscriptionType != 'free' && $user->subscriptionEndDate > now()) {
            return $user->subscriptionType;
        }
        return 'free';
    }

}
