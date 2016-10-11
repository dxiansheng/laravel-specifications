<?php

namespace Pbmedia\ScoreMatcher\Laravel\Models;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Pbmedia\ScoreMatcher\Specifications;

trait HasSpecificationsTrait
{
    /**
     * ScoreModel class name.
     *
     * @var string
     */
    protected $scoreModelClass = ScoreModel::class;

    /**
     * Instance of Specifications.
     *
     * @var \Pbmedia\ScoreMatcher\Specifications
     */
    protected $specifications;

    /**
     * Returns a Specifications object with the Scores and Attributes
     * loaded from the database.
     *
     * @return \Pbmedia\ScoreMatcher\Specifications
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
        return $this->morphMany($this->scoreModelClass, 'scorable');
    }

    /**
     * Binds a callback to the 'saved' event of the model which syncs the
     * AttributeScore objects in the Specifications instance with
     * ones in the database.
     */
    public static function bootHasSpecificationsTrait()
    {
        static::saved(function ($canBeSpecified) {
            $scoreIds = $canBeSpecified->Scores->pluck('id');

            if ($scoreIds->count() > 0) {
                app($this->scoreModelClass)->whereIn('id', $scoreIds)->delete();
            }

            $attributScoreCollection = $canBeSpecified->specifications()->all();

            if ($attributScoreCollection->isEmpty()) {
                return;
            }

            $attributScoreCollection->map(function ($attributeScore) {
                return [
                    'attribute_id' => $attributeScore->getAttribute()->id,
                    'value'        => $attributeScore->getScoreValue(),
                ];
            })->each(function ($scoreModelAttributes) use ($canBeSpecified) {
                $canBeSpecified->Scores()->create($scoreModelAttributes);
            });
        });
    }
}
