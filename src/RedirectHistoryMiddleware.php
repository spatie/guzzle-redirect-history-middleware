<?php

namespace Spatie\GuzzleRedirectHistoryMiddleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class RedirectHistoryMiddleware
{
    public static function make(RedirectHistory $redirectHistory): self
    {
        return new static($redirectHistory);
    }

    public function __construct(protected RedirectHistory $redirectHistory)
    {
    }

    public function __invoke(callable $handler)
    {
        return function (RequestInterface $request, array $options) use ($handler) {
            /** @var \GuzzleHttp\Promise\FulfilledPromise $response */
            $response = $handler($request, $options);

            $response->then(function (ResponseInterface $response) use ($request) {
                $this->redirectHistory->add(
                    $response->getStatusCode(),
                    (string)$request->getUri(),
                    $response->getReasonPhrase(),
                    $response->getHeaders()
                );
            });

            return $response;
        };
    }
}
