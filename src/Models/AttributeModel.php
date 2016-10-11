<?php

namespace Pbmedia\ScoreMatcher\Laravel\Models;

use Illuminate\Database\Eloquent\Model;
use Pbmedia\ScoreMatcher\Interfaces\Attribute;
use Pbmedia\ScoreMatcher\Laravel\Exceptions\AttributeModelNotSavedException;

class AttributeModel extends Model implements Attribute
{
    /**
     * {@inheritDoc}
     */
    protected $fillable = ['name'];

    /**
     * {@inheritDoc}
     */
    protected $table = 'score_matcher_attributes';

    /**
     * Helper method to quickly create a new AttributeModel.
     *
     * @return \Pbmedia\ScoreMatcher\Laravel\Models\AttributeModel
     */
    public static function createWithName($name)
    {
        return static::create(compact('name'));
    }

    /**
     * Returns the key by which we can identify this model.
     *
     * @throws \Pbmedia\ScoreMatcher\Laravel\Exceptions\AttributeModelNotSavedException
     * @return int
     */
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
