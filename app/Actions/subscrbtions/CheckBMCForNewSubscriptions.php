<?php
namespace App\Actions\subscrbtions;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class CheckBMCForNewSubscriptions
{
    public function execute()
    {
        $pageId = Cache::get('bmc_subscriptions_page_id')??1;

        //get all BMC SUBSCRIPTIONS
        $url = 'https://developers.buymeacoffee.com/api/v1/subscriptions';

        $response = Http::withHeaders([
            'Authorization' => config('BMC.ACCESS_TOKEN'),
        ])->asForm()->get($url ,[
            'status' => 'active',
            'page' => $pageId,
        ]);

        $lastPage = $response->json()['last_page'];
        for ($i= $pageId; $i <= $lastPage; $i++) {
            $pageId = $i;
            $response = Http::withHeaders([
                'Authorization' => config('BMC.ACCESS_TOKEN'),
            ])->asForm()->get($url ,[
                'status' => 'active',
                'page' => $pageId,
            ]);
            $subscriptions = $response->json()['data'];
            foreach ($subscriptions as $subscription) {
                $user = User::where('email', $subscription['payer_email'])->first();
                if($user){

                    if($subscription['subscription_coffee_num'] === 1){
                        $user->subscriptionType = 'basic';
                    }elseif ($subscription['subscription_coffee_num'] === 5) {
                        $user->subscriptionType = 'pro';
                    }
                    $user->subscriptionEndDate = $subscription['subscription_current_period_end'];
                    $user->save();
                }
            }
        }
        Cache::forever('bmc_subscriptions_page_id', $lastPage);



    }

}
