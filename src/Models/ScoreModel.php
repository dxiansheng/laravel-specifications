<?php

namespace Pbmedia\ScoreMatcher\Laravel\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Pbmedia\ScoreMatcher\AttributeScore;
use Pbmedia\ScoreMatcher\Interfaces\Score;

class ScoreModel extends Model implements Score
{
    /**
     * {@inheritDoc}
     */
    protected $fillable = ['attribute_id', 'value'];

    /**
     * {@inheritDoc}
     */
    protected $table = 'score_matcher_scores';

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'value' => 'json',
    ];

    /**
     * Helper method to quickly instantiate a new ScoreModel.
     *
     * @return \Pbmedia\ScoreMatcher\Laravel\Models\ScoreModel
     */
    public static function withValue($value): ScoreModel
    {
        return new static(compact('value'));
    }

    /**
     * Returns the value attribute.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Returns the value attribute.
     *
     * @return mixed
     */
    public function getAttributeScore(): AttributeScore
    {
        return new AttributeScore($this->Attribute, $this);
    }

    /**
     * Defines the relationship with the AttributeModel.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Attribute(): BelongsTo
    {
        return $this->belongsTo(AttributeModel::class);
    }

    /**
     * Defines the relationship with the model that will be specified.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function Scorable(): MorphTo
    {
        return $this->morphTo();
    }
}
