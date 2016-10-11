<?php

namespace Pbmedia\Specifications\Laravel\Tests;

use Pbmedia\Specifications\Laravel\Exceptions\AttributeModelNotSavedException;
use Pbmedia\Specifications\Laravel\Models\AttributeModel;

class AttributeModelTest extends TestCase
{
    public function testAttributeModel()
    {
        $attribute = AttributeModel::create([
            'name' => 'Internal Memory',
        ]);

        $this->seeInDatabase('specifications_attributes', [
            'id'   => 1,
            'name' => 'Internal Memory',
        ]);

        $this->assertEquals(1, $attribute->getIdentifier());
    }

    public function testHelperMethod()
    {
        $attribute = AttributeModel::createWithName('Internal Memory');

        $this->seeInDatabase('specifications_attributes', [
            'id'   => 1,
            'name' => 'Internal Memory',
        ]);

        $this->assertEquals(1, $attribute->getIdentifier());
    }

    /**
     * @expectedException \Pbmedia\Specifications\Laravel\Exceptions\AttributeModelNotSavedException
     **/
    public function testNonExistingAttributeModel()
    {
        $attribute = new AttributeModel([
            'name' => 'Internal Memory',
        ]);

        $attribute->getIdentifier();
    }
}
