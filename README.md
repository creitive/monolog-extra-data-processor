# Monolog Extra Data Processor

[![Software License][ico-license]](LICENSE.md)

A processor for [Monolog][https://github.com/Seldaek/monolog] to enable easy addition of arbitrary data into all log entries.


## Install

Via Composer

``` bash
$ composer require creitive/monolog-extra-data-processor
```

## Usage

You can do something like this (obviously, the data you pass in depends on your own needs):

``` php
$extraDataProcessor = new ExtraDataProcessor([
    'environment' => getenv('APP_ENV'),
    'cookies' => $_COOKIES,
    'foo' => 'bar',
]);

/*
 * Assuming `$monolog` is an instance of `\Monolog\Logger`:
 */
$monolog->pushProcessor($extraDataProcessor);
```

That's all! The extra data you've passed will now be logged with all entries, under the `extra` attribute.

If you already have an instance with some data, you can add additional data:

``` php
$extraDataProcessor = addExtraData([
    'baz' => 'qux',
]);
```

You can also remove extra data by keys:

``` php
$extraDataProcessor->removeExtraData([
    'foo',
    'baz',
]);
```

And get all currently configured data:

``` php
$extraData = $extraDataProcessor->getExtraData();
```


## What's the point?

For example, we use this processor class to log the following data (which we find useful):

- Environment (e.g. `local` vs. `staging` vs. `production`)
- Whether PHP is executing as a console command or a web request
- Command-line arguments (if relevant)
- HTTP method
- URL being visited
- Client's IP address
- Referrer
- User agent

Some of this (for example, the web-request stuff) is available via the `Monolog\Processor\WebProcessor` - however, that class seems a bit limited in scope, and it would seem a bit hacky to use it for this purpose.

You could even misuse the `Monolog\Processor\TagProcessor` class to log extra stuff, but it would all be under `extra.tags` instead of just `extra` - and it's still missing the point.


## Tips

Since we primarily work with Laravel 5, we have an `App\Log\Configurator` class, which wires the basic logging functionality. It's invoked in `bootstrap/app.php` like this:

```php
$app->configureMonologUsing(function(\Monolog\Logger $monolog) use ($app)
{
    $configurator = new \App\Log\Configurator;

    $configurator->configure($monolog, $app);
});
```

The configurator, in turn, pushes the `GitProcessor` (awesome for knowing which exact project commit was being executed when the entry was logged), and the `ExtraDataProcessor` to the passed logger instance. In this step, the `ExtraDataProcessor` is only populated with the current environment, console command check, and command-line arguments - as the rest of the data comes via the HTTP request (which isn't captured yet at this point). We also configure the handlers at this point.

The request data is added later using a service provider, when the app is already booted.

If logging something like input data, be sure to pay attention to privacy issues - for example, don't log `$_POST['password']` from login forms, as this compromises both your users and your system. This is especially true if you're using some kind of cloud-hosted third-party logging service (e.g. Logentries, Loggly, Papertrail, etc.)


## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.


## Testing

``` bash
$ composer test
```


## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.


## Security

If you discover any security related issues, please email development@creitive.rs instead of using the issue tracker.


## Credits

- [Miloš Levačić][link-author]
- [All Contributors][link-contributors]


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square

[link-author]: https://github.com/levacic
[link-contributors]: ../../contributors
