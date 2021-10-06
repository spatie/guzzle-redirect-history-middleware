# A Guzzle middleware to keep track of redirects

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/guzzle-redirect-history-middleware.svg?style=flat-square)](https://packagist.org/packages/spatie/guzzle-redirect-history-middleware)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/spatie/guzzle-redirect-history-middleware/run-tests?label=tests)](https://github.com/spatie/guzzle-redirect-history-middleware/actions?query=workflow%3ATests+branch%3Amaster)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/spatie/guzzle-redirect-history-middleware/Check%20&%20fix%20styling?label=code%20style)](https://github.com/spatie/guzzle-redirect-history-middleware/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/guzzle-redirect-history-middleware.svg?style=flat-square)](https://packagist.org/packages/spatie/guzzle-redirect-history-middleware)

This package contains middleware for [Guzzle](https://docs.guzzlephp.org/en/stable/) that allows you to track redirects that happened during a request.

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/guzzle-redirect-history-middleware.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/guzzle-redirect-history-middleware)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require spatie/guzzle-redirect-history-middleware
```

## Usage

Here is a quick example of how you can use the `Spatie\GuzzleRedirectHistoryMiddleware\RedirectHistoryMiddleware` to store the redirect history in a `Spatie\GuzzleRedirectHistoryMiddleware\RedirectHistory` instance.

```php
use Spatie\GuzzleRedirectHistoryMiddleware\RedirectHistory;
use Spatie\GuzzleRedirectHistoryMiddleware\RedirectHistoryMiddleware;

/*
 * First create a new instance of `RedirectHistory`
 * This instance can be used after the requests to get the redirects.
 */
$redirectHistory = new RedirectHistory();

/*
 * This is the default way to add a middleware to Guzzle
 * default middleware stack.
 */
$stack = HandlerStack::create();
$stack->push(RedirectHistoryMiddleware::make($redirectHistory));

/*
 * Let's create Guzzle client that uses the middleware stack
 * containing our `RedirectHistoryMiddleware`.
 */
$client new Client([
    'handler' => $stack,
]);

/*
 * Now, let's make a request.
 */
$response = $client->get($anyUrl);

/*
 * And tada, here are all the redirects performed
 * during the request.
 */
$redirects = $redirectHistory->toArray();
````

`$redirects` is an array of which item is an array with these keys:
- `status`: the status code of the response
- `url`: the URL of the performed request that resulted in a redirect

So if you make a request to `https://example.com/page-a` which redirects to `/page-b` which finally redirect to `/page-c` this will be in `$redirects`

```php
[
    ['status' => 302, 'url' => 'https://example.com/page-a'],
    ['status' => 302, 'url' => 'https://example.com/page-b']
    ['status' => 200, 'url' => 'https://example.com/page-c']
];
```

Even if your initial request results in a `\GuzzleHttp\Exception\TooManyRedirectsException`, the `RedirectHistory` will still contain the performed redirects

## Why we created this package

Guzzle has [built-in support for tracking redirects](https://docs.guzzlephp.org/en/stable/request-options.html#allow-redirects). Unfortunately, it isn't that developer friendly to use. You'll have to manipulate and combine arrays found in the `X-Guzzle-Redirect-History` and `X-Guzzle-Redirect-Status-History` headers.

Also, when hitting an exception such as `TooManyRedirectsException`, these headers won't be filled.

Our package makes it easy to retrieve the redirects in a sane format. You're also able to get the redirect history even if the request ultimately fails.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
