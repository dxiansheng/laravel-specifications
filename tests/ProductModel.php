<?php

namespace Pbmedia\Specifications\Laravel\Tests;

use Illuminate\Database\Eloquent\Model;
use Pbmedia\Specifications\Interfaces\CanBeSpecified;
use Pbmedia\Specifications\Laravel\Models\HasSpecificationsTrait;

class ProductModel extends Model implements CanBeSpecified
{
    use HasSpecificationsTrait;

    protected $fillable = ['name'];

    protected $table = 'products';
}
