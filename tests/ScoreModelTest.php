<?php

namespace Pbmedia\Specifications\Laravel\Tests;

use Pbmedia\Specifications\AttributeScore;
use Pbmedia\Specifications\Laravel\Models\AttributeModel;
use Pbmedia\Specifications\Laravel\Models\ScoreModel;
use Pbmedia\Specifications\Laravel\Tests\ProductModel;

class ScoreModelTest extends TestCase
{
    public function testScoreModel()
    {
        $macbookProduct = ProductModel::create([
            'name' => 'MacBook',
        ]);

        $memoryAttribute = AttributeModel::create([
            'name' => 'Internal Memory',
        ]);

        $memoryScore = new ScoreModel([
            'value' => 4096,
        ]);

        $macbookProduct->specifications()->set($memoryAttribute, $memoryScore);
        $macbookProduct->save();

        $this->seeInDatabase('specifications_scores', [
            'id'               => 1,
            'attribute_id'     => 1,
            'specifiable_type' => app(ProductModel::class)->getMorphClass(),
            'specifiable_id'   => 1,
            'value'            => 4096,
        ]);

        $this->assertCount(1, ScoreModel::all());

        $attributeScore = ScoreModel::first()->getAttributeScore();

        $this->assertInstanceOf(AttributeScore::class, $attributeScore);
    }

    public function testTwoScoreModels()
    {
        $macbookProduct = ProductModel::create([
            'name' => 'MacBook',
        ]);

        $memoryAttribute = AttributeModel::create([
            'name' => 'Internal Memory',
        ]);

        $memoryScore = new ScoreModel([
            'value' => 4096,
        ]);

        $screenSizeAttribute = AttributeModel::create([
            'name' => 'Screen size',
        ]);

        $screenScore = new ScoreModel([
            'value' => 13.3,
        ]);

        $macbookProduct->specifications()->set($memoryAttribute, $memoryScore);
        $macbookProduct->specifications()->add(new AttributeScore($screenSizeAttribute, $screenScore));
        $macbookProduct->save();

        $this->seeInDatabase('specifications_scores', [
            'id'               => 1,
            'attribute_id'     => 1,
            'specifiable_type' => app(ProductModel::class)->getMorphClass(),
            'specifiable_id'   => 1,
            'value'            => 4096,
        ]);

        $this->seeInDatabase('specifications_scores', [
            'id'               => 2,
            'attribute_id'     => 2,
            'specifiable_type' => app(ProductModel::class)->getMorphClass(),
            'specifiable_id'   => 1,
            'value'            => 13.3,
        ]);

        $this->assertCount(2, ScoreModel::all());
    }

    public function testScoresWithFreshModel()
    {
        $macbookProduct = ProductModel::create([
            'name' => 'MacBook',
        ]);

        $memoryAttribute = AttributeModel::create([
            'name' => 'Internal Memory',
        ]);

        $memoryScore = new ScoreModel([
            'value' => 4096,
        ]);

        $screenSizeAttribute = AttributeModel::create([
            'name' => 'Screen size',
        ]);

        $screenScore = new ScoreModel([
            'value' => 13.3,
        ]);

        $macbookProduct->specifications()->set($memoryAttribute, $memoryScore);
        $macbookProduct->specifications()->add(new AttributeScore($screenSizeAttribute, $screenScore));
        $macbookProduct->save();

        $this->assertEquals(2, $macbookProduct->specifications()->count());
    }

    public function testUpdatedScores()
    {
        $macbookProduct = ProductModel::create([
            'name' => 'MacBook',
        ]);

        $memoryAttribute = AttributeModel::create([
            'name' => 'Internal Memory',
        ]);

        $memoryScore = new ScoreModel([
            'value' => 4096,
        ]);

        $screenSizeAttribute = AttributeModel::create([
            'name' => 'Screen size',
        ]);

        $screenScore = new ScoreModel([
            'value' => 13.3,
        ]);

        $macbookProduct->specifications()->set($memoryAttribute, $memoryScore);
        $macbookProduct->specifications()->add(new AttributeScore($screenSizeAttribute, $screenScore));
        $macbookProduct->save();

        $bigScreenScore = new ScoreModel([
            'value' => 15.4,
        ]);

        $macbookProduct->specifications()->forget($memoryAttribute);
        $macbookProduct->specifications()->set($screenSizeAttribute, $bigScreenScore);

        $this->assertEquals(1, $macbookProduct->specifications()->count());

        $scoreValue = $macbookProduct->specifications()->get($screenSizeAttribute)->getScoreValue();

        $this->assertEquals(15.4, $scoreValue);
    }
}
