<?php

namespace App\Http\Controllers;

use App\Models\Column;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BuyMeACoffeeWebhookController extends Controller
{

    public function __invoke(Request $request)
    {
        $event = Str::camel($request->header('x-bmc-event'));
        $payload = $request->json('response');

        //log the event
        Log::info($payload);
;

        if (method_exists($this, $event)) {
            return call_user_func([$this, $event], $payload);
        }

        return response()->json("The event {$request->header('x-bmc-event')} is unsupported.");
    }

    public function coffeePurchase(array $payload): JsonResponse
    {
        return response()->json("Thank you for your support {$payload['supporter_email']}.");
    }

    public function coffeeLinkPurchase(array $payload): JsonResponse
    {
        return response()->json("Thank you for your support {$payload['supporter_email']}.");
    }
}

