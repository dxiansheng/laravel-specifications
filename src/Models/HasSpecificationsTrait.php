<?php

namespace Pbmedia\Specifications\Laravel\Models;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Pbmedia\Specifications\AttributeScore;
use Pbmedia\Specifications\Interfaces\CanBeSpecified;
use Pbmedia\Specifications\Specifications;

trait HasSpecificationsTrait
{
    /**
     * ScoreModel class name.
     *
     * @var string
     */
    protected static $scoreModelClass = ScoreModel::class;

    /**
     * Instance of Specifications.
     *
     * @var \Pbmedia\Specifications\Specifications
     */
    protected $specifications;

    /**
     * Returns a Specifications object with the Scores and Attributes
     * loaded from the database.
     *
     * @return \Pbmedia\Specifications\Specifications
     */
    public function specifications(): Specifications
    {
        if (!$this->specifications) {
            $this->specifications = new Specifications;

            $this->getRelationValue('Scores')->load('Attribute')->each(function ($scoreModel) {
                $this->specifications->add($scoreModel->getAttributeScore());
            });
        }

        return $this->specifications;
    }

    /**
     * Defines the relationship with the Scores attached to this model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function Scores(): MorphMany
    {
        return $this->morphMany(static::$scoreModelClass, 'specifiable');
    }

    /**
     * Binds a callback to the 'saved' event of the model which syncs the
     * AttributeScore objects in the Specifications instance with
     * ones in the database.
     */
    public static function bootHasSpecificationsTrait()
    {
        static::saved(function (CanBeSpecified $canBeSpecified) {
            $scoreIds = $canBeSpecified->getRelationValue('Scores')->pluck('id');

            if ($scoreIds->count() > 0) {
                app(static::$scoreModelClass)->whereIn('id', $scoreIds)->delete();
            }

            $attributScoreCollection = $canBeSpecified->specifications()->all();

            if ($attributScoreCollection->isEmpty()) {
                return;
            }

            $attributScoreCollection->map(function (AttributeScore $attributeScore) {
                return [
                    'attribute_id' => $attributeScore->getAttribute()->id,
                    'value'        => $attributeScore->getScoreValue(),
                ];
            })->each(function (array $scoreModelAttributes) use ($canBeSpecified) {
                $canBeSpecified->Scores()->create($scoreModelAttributes);
            });
        });
    }
}
