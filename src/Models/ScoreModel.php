<?php

namespace Pbmedia\ScoreMatcher\Laravel\Models;

use Illuminate\Database\Eloquent\Model;
use Pbmedia\ScoreMatcher\Interfaces\Score;

class ScoreModel extends Model implements Score
{
    protected $fillable = ['attribute_id', 'value'];

    protected $table = 'score_matcher_scores';

    protected $casts = [
        'value' => 'json',
    ];

    public function getValue()
    {
        return $this->value;
    }

    public function Attribute()
    {
        return $this->belongsTo(AttributeModel::class);
    }

    public function Scorable()
    {
        return $this->morphTo();
    }
}
