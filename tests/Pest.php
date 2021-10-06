<?php


use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Spatie\GuzzleRedirectHistoryMiddleware\RedirectHistory;
use Spatie\GuzzleRedirectHistoryMiddleware\RedirectHistoryMiddleware;

function guzzleClient(RedirectHistory $redirectHistory, int $maxRedirects = 5): Client
{
    $stack = HandlerStack::create();

    $stack->push(RedirectHistoryMiddleware::make($redirectHistory));

    return new Client([
        'handler' => $stack,
        'allow_redirects' => [
            'max' => $maxRedirects,
        ],
    ]);
}
