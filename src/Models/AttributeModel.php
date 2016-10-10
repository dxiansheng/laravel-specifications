<?php

namespace Pbmedia\ScoreMatcher\Laravel\Models;

use Illuminate\Database\Eloquent\Model;
use Pbmedia\ScoreMatcher\Interfaces\Attribute;
use Pbmedia\ScoreMatcher\Laravel\Exceptions\AttributeModelNotSavedException;

class AttributeModel extends Model implements Attribute
{
    protected $fillable = ['name'];

    protected $table = 'score_matcher_attributes';

    public static function createWithName($name)
    {
        return static::create(compact('name'));
    }

    public function getIdentifier()
    {
        if (!$this->exists) {
            $exception = new AttributeModelNotSavedException;
            $exception->setModel($this);

            throw $exception;
        }

        return $this->getKey();
    }
}
