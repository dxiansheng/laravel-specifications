<?php

namespace Pbmedia\ScoreMatcher\Laravel\Tests;

use Illuminate\Database\Eloquent\Model;
use Pbmedia\ScoreMatcher\Interfaces\CanBeSpecified;
use Pbmedia\ScoreMatcher\Laravel\Models\HasSpecificationsTrait;

class ProductModel extends Model implements CanBeSpecified
{
    use HasSpecificationsTrait;

    protected $fillable = ['name'];

    protected $table = 'products';
}
