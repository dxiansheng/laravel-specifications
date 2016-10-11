<?php

namespace Pbmedia\Specifications\Laravel\Tests;

use Pbmedia\Specifications\Laravel\Models\AttributeModel;
use Pbmedia\Specifications\Laravel\Models\ScoreModel;
use Pbmedia\Specifications\Laravel\Tests\ProductModel;
use Pbmedia\Specifications\Matcher;

class MatcherTest extends TestCase
{
    private $memoryAttribute;

    private function setupMacbooks()
    {
        $this->memoryAttribute = AttributeModel::create([
            'name' => 'Internal Memory',
        ]);

        $macbookAir = ProductModel::create([
            'name' => 'MacBook Air',
        ]);

        $macbookAir->specifications()->set(
            $this->memoryAttribute, ScoreModel::withValue(4096)
        );

        $macbookAir->save();

        $macbookPro = ProductModel::create([
            'name' => 'MacBook Pro',
        ]);

        $macbookPro->specifications()->set(
            $this->memoryAttribute, ScoreModel::withValue(8196)
        );

        // $macbookPro->save();
    }

    public function testMatcherBasedOnMemory()
    {
        $this->setupMacbooks();

        $matcher = new Matcher();

        ProductModel::all()->each(function ($macbook) use ($matcher) {
            $matcher->addCandidate($macbook);
        });

        $matcher->specifications()->set(
            $this->memoryAttribute,
            ScoreModel::withValue(2048)
        );

        $results = $matcher->get();

        $this->assertEquals('MacBook Air', $results->get(0)->name);
        $this->assertEquals('MacBook Pro', $results->get(1)->name);

        $matcher->specifications()->set(
            $this->memoryAttribute,
            ScoreModel::withValue(16384)
        );

        $results = $matcher->get();

        $this->assertEquals('MacBook Pro', $results->get(0)->name);
        $this->assertEquals('MacBook Air', $results->get(1)->name);
    }
}
