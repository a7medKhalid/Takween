<?php

namespace App\Http\Livewire;

use App\Actions\subscrbtions\CheckBMCForNewSubscriptions;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Subscriptions extends Component
{

    public $subscriptionEndDate;
    public $subscriptionType;
    public $isSubscriptionActive;

    public function refresh(){
        $checkBMCForNewSubscriptions = new CheckBMCForNewSubscriptions();
        $checkBMCForNewSubscriptions->execute();
    }

    public function getSubscription(){
        $this->subscriptionType = Auth::user()->subscriptionType;
        $this->subscriptionEndDate = Auth::user()->subscriptionEndDate;

        //check if subscription is active
        if($this->subscriptionEndDate > now()){
            $this->isSubscriptionActive = true;
        }
        else{
            $this->isSubscriptionActive = false;
        }
    }


    public function mount(){
        $this->getSubscription();


    }

    public function render()
    {
        return view('livewire.subscriptions');
    }
}
