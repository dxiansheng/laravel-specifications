<?php

namespace Pbmedia\ScoreMatcher\Laravel\Models;

use Pbmedia\ScoreMatcher\Specifications;

trait HasSpecificationsTrait
{
    protected $specifications;

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

    public function Scores()
    {
        return $this->morphMany(ScoreModel::class, 'scorable');
    }

    public static function bootHasSpecificationsTrait()
    {
        static::saved(function ($canBeSpecified) {
            $scoreIds = $canBeSpecified->Scores->pluck('id');

            if ($scoreIds->count() > 0) {
                ScoreModel::whereIn('id', $scoreIds)->delete();
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
