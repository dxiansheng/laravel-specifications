<?php

namespace Pbmedia\ScoreMatcher\Laravel\Tests;

use Pbmedia\ScoreMatcher\Laravel\Exceptions\AttributeModelNotSavedException;
use Pbmedia\ScoreMatcher\Laravel\Models\AttributeModel;

class AttributeModelTest extends TestCase
{
    public function testAttributeModel()
    {
        $attribute = AttributeModel::create([
            'name' => 'Internal Memory',
        ]);

        $this->seeInDatabase('score_matcher_attributes', [
            'id'   => 1,
            'name' => 'Internal Memory',
        ]);

        $this->assertEquals(1, $attribute->getIdentifier());
    }

    public function testHelperMethod()
    {
        $attribute = AttributeModel::createWithName('Internal Memory');

        $this->seeInDatabase('score_matcher_attributes', [
            'id'   => 1,
            'name' => 'Internal Memory',
        ]);

        $this->assertEquals(1, $attribute->getIdentifier());
    }

    /**
     * @expectedException \Pbmedia\ScoreMatcher\Laravel\Exceptions\AttributeModelNotSavedException
     **/
    public function testNonExistingAttributeModel()
    {
        $attribute = new AttributeModel([
            'name' => 'Internal Memory',
        ]);

        $attribute->getIdentifier();
    }
}
