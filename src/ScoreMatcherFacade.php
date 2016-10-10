<?php

namespace Pbmedia\ScoreMatcher\Laravel;

use Illuminate\Support\Facades\Facade;

class ScoreMatcherFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-score-matcher';
    }
}
