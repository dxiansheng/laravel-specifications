<?php

namespace Pbmedia\Specifications\Laravel\Models;

use Illuminate\Database\Eloquent\Model;
use Pbmedia\Specifications\Interfaces\Attribute;
use Pbmedia\Specifications\Laravel\Exceptions\AttributeModelNotSavedException;

class AttributeModel extends Model implements Attribute
{
    /**
     * {@inheritDoc}
     */
    protected $fillable = ['name'];

    /**
     * {@inheritDoc}
     */
    protected $table = 'specifications_attributes';

    /**
     * Helper method to quickly create a new AttributeModel.
     *
     * @return \Pbmedia\Specifications\Laravel\Models\AttributeModel
     */
    public static function createWithName($name)
    {
        return static::create(compact('name'));
    }

    /**
     * Returns the key by which we can identify this model.
     *
     * @throws \Pbmedia\Specifications\Laravel\Exceptions\AttributeModelNotSavedException
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
