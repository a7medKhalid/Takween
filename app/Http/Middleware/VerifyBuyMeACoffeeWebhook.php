<?php

namespace App\Http\Middleware;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Closure;

class VerifyBuyMeACoffeeWebhook
{
    private array $headers = [
        'x-bmc-event', 'x-bmc-signature',
    ];

    public function handle(Request $request, Closure $next)
    {
        return $this->verify($request) ? $next($request) : response("Webhook signature didn't match!", Response::HTTP_BAD_REQUEST);
    }

    private function verify(Request $request)
    {
        if (! collect($request->headers)->has($this->headers)) {
            return false;
        }

        $signature = $request->header('X-Bmc-Signature');
        $request->headers->remove('X-Bmc-Signature');

        return hash_equals(hash_hmac('sha256', $request->getContent(), config('webhooks.buymeacoffee.secret')), $signature);
    }
}

