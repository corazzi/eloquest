<?php

namespace Sachiano\Eloquest;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Sachiano\Eloquest\Exceptions\NoSearchResolver;

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

        $provider = $this->eloquestSearchResolver;

        return (new $provider($query))->run();
    }

    /**
     * Assert the model implementing Eloquest has an $eloquestSearchResolver property
     *
     * @return Model
     *
     * @throws \Sachiano\Eloquest\Exceptions\NoSearchResolver
     */
    public function assertSearchable() : Model
    {
        if (property_exists($this, 'eloquestSearchResolver')) {
            return $this;
        }

        throw new NoSearchResolver(
            sprintf('%s does not have an Eloquest search provider', self::class)
        );
    }
}
