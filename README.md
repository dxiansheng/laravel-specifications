# Laravel Score Matcher

[![Latest Version on Packagist](https://img.shields.io/packagist/v/pbmedia/laravel-score-matcher.svg?style=flat-square)](https://packagist.org/packages/pbmedia/laravel-score-matcher)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/pascalbaljetmedia/laravel-score-matcher/master.svg?style=flat-square)](https://travis-ci.org/pascalbaljetmedia/laravel-score-matcher)
[![Quality Score](https://img.shields.io/scrutinizer/g/pascalbaljetmedia/laravel-score-matcher.svg?style=flat-square)](https://scrutinizer-ci.com/g/pascalbaljetmedia/laravel-score-matcher)
[![Total Downloads](https://img.shields.io/packagist/dt/pbmedia/laravel-score-matcher.svg?style=flat-square)](https://packagist.org/packages/pbmedia/laravel-score-matcher)

This Laravel package provides the ability to attach 'scores' to your Eloquent models. It comes with a 'matcher' service that can sort collections of models based on 'criteria' you provide. Confused? Take a look at the example which almost speaks for itself.

## Install

Via Composer

``` bash
$ composer require pbmedia/laravel-score-matcher
```

Add the service provider and facade to your ```app.php``` config file:

``` php

// Laravel 5: config/app.php

'providers' => [
    ...
    Pbmedia\ScoreMatcher\Laravel\ScoreMatcherServiceProvider::class,
    ...
];

'aliases' => [
    ...
    'FFMpeg' => Pbmedia\ScoreMatcher\Laravel\ScoreMatcherFacade::class
    ...
];
```

Publish the migration files using the artisan CLI tool:

``` bash
php artisan vendor:publish --provider="Pbmedia\ScoreMatcher\Laravel\ScoreMatcherServiceProvider"
php artisan migrate
```

## Usage

``` php
// soon...!
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email pascal@pascalbaljetmedia.com instead of using the issue tracker.

## Credits

- [Pascal Baljet][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.