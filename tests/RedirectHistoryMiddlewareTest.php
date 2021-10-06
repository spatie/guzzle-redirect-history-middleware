<?php

use GuzzleHttp\Exception\TooManyRedirectsException;
use Spatie\GuzzleRedirectHistoryMiddleware\RedirectHistory;

beforeEach(function() {
   $this->redirectHistory = new RedirectHistory();
});

it('can track redirects', function() {
    $response = guzzleClient($this->redirectHistory)
        ->get('https://ohdear.app/test-routes/redirect/number/2');

    expect($response->getStatusCode())->toBe(204);

    expect($this->redirectHistory->toArray())->toEqual([
        ['status' => 302, 'url' => 'https://ohdear.app/test-routes/redirect/number/2'],
        ['status' => 302, 'url' => 'https://ohdear.app/test-routes/redirect/number/1'],
        ['status' => 204, 'url' => 'https://ohdear.app/test-routes/redirect/number/0'],
    ]);
});

it('will still track redirects if the redirect limit is reached', function() {

    try {
        guzzleClient($this->redirectHistory, maxRedirects: 2)
            ->get('https://ohdear.app/test-routes/redirect/number/10');
    } catch (TooManyRedirectsException) {

    }

    expect($this->redirectHistory->toArray())->toEqual([
        ['status' => 302, 'url' => 'https://ohdear.app/test-routes/redirect/number/10'],
        ['status' => 302, 'url' => 'https://ohdear.app/test-routes/redirect/number/9'],
        ['status' => 302, 'url' => 'https://ohdear.app/test-routes/redirect/number/8'],
    ]);
});
