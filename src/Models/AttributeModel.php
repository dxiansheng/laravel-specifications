<?php

namespace Pbmedia\ScoreMatcher\Laravel\Models;

use Illuminate\Database\Eloquent\Model;
use Pbmedia\ScoreMatcher\Interfaces\Attribute;

class AttributeModel extends Model implements Attribute
{
    protected $fillable = ['name'];

    protected $table = 'score_matcher_attributes';

    public function getIdentifier()
    {
        return $this->getKey();
    }
}
