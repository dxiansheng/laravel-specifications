<?php

namespace Pbmedia\ScoreMatcher\Laravel\Tests;

use Pbmedia\ScoreMatcher\Laravel\Models\AttributeModel;
use Pbmedia\ScoreMatcher\Laravel\Models\ScoreModel;
use Pbmedia\ScoreMatcher\Laravel\Tests\ProductModel;

class ScoreModelTest extends TestCase
{
    public function testScoreModel()
    {
        $product = ProductModel::create([
            'name' => 'MacBook',
        ]);

        $attribute = AttributeModel::create([
            'name' => 'Internal Memory',
        ]);

        $score = new ScoreModel([
            'value' => 4096,
        ]);

        $product->specifications()->set($attribute, $score);
        $product->save();

        $this->seeInDatabase('score_matcher_scores', [
            'id'            => 1,
            'attribute_id'  => 1,
            'scorable_type' => app(ProductModel::class)->getMorphClass(),
            'scorable_id'   => 1,
            'value'         => 4096,
        ]);
    }
}
