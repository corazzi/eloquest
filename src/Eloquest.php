<?php

namespace Sachiano\Eloquest;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Sachiano\Eloquest\Exceptions\NoSearchProvider;

trait Eloquest
{
    /**
     * Use Eloquest to search based on the request input
     *
     * @param $query
     *
     * @return Builder
     */
    public function scopeSearchByRequest($query) : Builder
    {
        $this->assertSearchable();

        $provider = $this->eloquestSearchProvider;

        return (new $provider($query))->run();
    }

    /**
     * Assert the model implementing Eloquest has an $eloquestSearchProvider property
     *
     * @return Model
     *
     * @throws \Sachiano\Eloquest\Exceptions\NoSearchProvider
     */
    public function assertSearchable() : Model
    {
        if (property_exists($this, 'eloquestSearchProvider')) {
            return $this;
        }

        throw new NoSearchProvider(
            sprintf('%s does not have an Eloquest search provider', self::class)
        );
    }
}