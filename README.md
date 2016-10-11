# Laravel Specifications

[![Latest Version on Packagist](https://img.shields.io/packagist/v/pbmedia/laravel-specifications.svg?style=flat-square)](https://packagist.org/packages/pbmedia/laravel-specifications)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/pascalbaljetmedia/laravel-specifications/master.svg?style=flat-square)](https://travis-ci.org/pascalbaljetmedia/laravel-specifications)
[![Quality Score](https://img.shields.io/scrutinizer/g/pascalbaljetmedia/laravel-specifications.svg?style=flat-square)](https://scrutinizer-ci.com/g/pascalbaljetmedia/laravel-specifications)
[![Total Downloads](https://img.shields.io/packagist/dt/pbmedia/laravel-specifications.svg?style=flat-square)](https://packagist.org/packages/pbmedia/laravel-specifications)

This Laravel package provides the ability to 'specify' your Eloquent models. It comes with a 'matcher' service that can sort collections of models based on 'criteria' you provide. Confused? Take a look at the example which almost speaks for itself. Under the hood it uses the [framework-agnostic](https://github.com/pascalbaljetmedia/specifications) version of this package.

## Requirements

* Compatible with Laravel 5.3 and up.
* PHP 7.0 and 7.1.

## Install

Via Composer

``` bash
$ composer require pbmedia/laravel-specifications
```

Add the service provider and facade to your ```app.php``` config file:

``` php
'providers' => [
    ...
    Pbmedia\Specifications\Laravel\SpecificationsServiceProvider::class,
    ...
];

'aliases' => [
    ...
    'SpecificationsMatcher' => Pbmedia\Specifications\Laravel\SpecificationsFacade::class
    ...
];
```

Publish the migration files using the artisan CLI tool:

``` bash
php artisan vendor:publish --provider="Pbmedia\Specifications\Laravel\SpecificationsServiceProvider"
php artisan migrate
```

## Usage

Imagine you have an Eloquent model which represents products you sell. Using the ```HasSpecificationsTrait``` and ```CanBeSpecified``` interface, you can add specifications to your products. Add the interface and trait to the Eloquent model:

``` php
use Illuminate\Database\Eloquent\Model;
use Pbmedia\Specifications\Interfaces\CanBeSpecified;
use Pbmedia\Specifications\Laravel\Models\HasSpecificationsTrait;

class Product extends Model implements CanBeSpecified {

    use HasSpecificationsTrait;

}
```

Let's think about how you want to specify your product, for example 'disk capacity' and 'internal memory'. Let's store these into the database using the ```AttributeModel```.

``` php
use Pbmedia\Specifications\Laravel\Models\AttributeModel;

$diskCapacity = AttributeModel::create(['name' => 'Disk Capacity in GB']);

// or use the 'createWithName' helper method:
$internalMemory = AttributeModel::createWithName('Internal Memory in MB');
```

With the ```ScoreModel```, you can bind a value to an ```AttributeModel``` and add it to the specifications of your product:

``` php
use Pbmedia\Specifications\Laravel\Models\ScoreModel;

$macbookAir = Product::whereName('MacBook Air')->first();
$macbookPro = Product::whereName('MacBook Pro')->first();

$macbookAir->specifications()->set(
    $internalMemory, new ScoreModel(['value' => 4096])
);

// or use the 'withValue' helper method:
$macbookPro->specifications()->set(
    $internalMemory, ScoreModel::withValue(8192)
);

// don't forget to save the products!
$macbookAir->save();
$macbookPro->save();
```

The ```specifications()``` method returns a ```Specifications``` class which has the following methods available:

```php
// add a AttributeScore object to the specifications
public function add(AttributeScore $attributeScore): Specifications;

// helper method to add multiple AttributeScore objects at once
public function addMany(array $attributeScores = []): Specifications;

// does the same as the 'add' method, but generates the AttributeScore object
// automatically based on the given Attribute and Score objects
public function set(Attribute $attribute, Score $score): Specifications;

// returns a boolean wether the given Attribute is present
public function has(Attribute $attribute): bool;

// returns the AttributeScore object based on the given Attribute object
public function get(Attribute $attribute): AttributeScore;

// forgets the AttributeScore object based on the given Attribute object
public function forget(Attribute $attribute): Specifications;

// returns a Collection object containing all AttributeScore objects
public function all(): Collection;

// count the number of AttributeScore objects
public function count(): int;
```

An ```AttributeScore``` object combines an Attribute object with a Score object and has only three methods available:

```php
$diskCapacity = new AttributeModel(['name' => 'Disk Capacity in GB']);
$size = new ScoreModel(['value' => 256]);

$attributeScore = new AttributeScore($diskCapacity, $size);

// returns the AttributeModel
$attributeScore->getAttribute();

// returns the ScoreModel
$attributeScore->getScore();

// returns 256
$attributeScore->getScoreValue();
```

Now let's focus on the ```Matcher``` service. You have to provide the service with two kinds of data. Firstly, you have to add products to the service (or other models which implement the ```CanBeSpecified``` interface). Secondly, you have to add 'criteria', just like you've added to the products. Since the service itself also implement the ```CanBeSpecified``` interface, this works exactly the same by using the ```specifications()``` method.

In this example we will be using the MacBook products again. Remember we've specified the Internal Memory of these products. Say you are looking for a notebook with 16 GB of Internal Memory, but unfortunately, these notebook do not exist in our database. The matcher service will sort the products based on which ones are most closely to the criteria.

```php
// the MacBook Air has 4096 MB of Internal Memory
$macbookAir = Product::whereName('MacBook Air')->first();

// the MacBook Pro has 8196 MB of Internal Memory
$macbookPro = Product::whereName('MacBook Pro')->first();

$matcher = new Matcher();

$matcher->addCandidate($macbookAir);
$matcher->addCandidate($macbookPro);

// you can also use the 'addCandidates' helper method:
$matcher->addCandidates($macbookAir, $macbookPro);
$matcher->addCandidates([$macbookAir, $macbookPro]);

// now provide some criteria.
$memoryAttribute = AttributeModel::whereName('Internal Memory in MB')->first();
$sixteenGigabytesScore = ScoreModel::withValue(16384);

$matcher->specifications()->set(
    $memoryAttribute,
    $sixteenGigabytesScore
);

// now let the service do its magic!
$products = $matcher->get(); // returns a Collection instance

// prints the MacBook Pro
var_dump($products[0]);

// print the MacBook Air
var_dump($products[1]);
```

The MacBook Pro is the first element in the array since it comes closer to the given specifications than the MacBook Air. You can add as many specifications as you wish, each are treated equally important in the comparison. The ```Matcher``` service is also available throught the Laravel facade:

```php
$matcher = SpecificationsMatcher::addCandidates([$macbookAir, $macbookPro]);
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

- [Pascal Baljet](https://github.com/pascalbaljet)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
