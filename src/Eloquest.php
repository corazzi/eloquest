<?php

namespace Sachiano\Eloquest;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Sachiano\Eloquest\Exceptions\NoSearchMappings;

trait Eloquest
{
    /**
     * The Eloquent Builder
     *
     * @var Builder
     */
    private $eloquestQuery;

    /**
     * Set up the query
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearchByRequest(Builder $query)
    {
        $this->assertSearchable()->getSearchMappings()->each(function ($map, $field) use (&$query) {
            $query = (new Clause(
                $map,
                $field,
                request()->all()
            ))->alterQuery($query);
        });

        return $query;
    }

    /**
     * Return the grammar method with an 'eloquest' prefix
     *
     * @param $grammar
     *
     * @return string
     */
    public function grammarMethod($grammar)
    {
        return sprintf('eloquest%s', $grammar);
    }

    /**
     * Return a Collection of the model's search mappings
     *
     * @return \Illuminate\Support\Collection
     */
    public function getSearchMappings() : Collection
    {
        return collect($this->searchMappings);
    }

    /**
     * Assert the model implementing Eloquest has a $searchMappings property
     *
     * @return Model
     *
     * @throws \Sachiano\Eloquest\Exceptions\NoSearchMappings
     */
    public function assertSearchable() : Model
    {
        if (property_exists($this, 'searchMappings')) {
            return $this;
        }

        throw new NoSearchMappings('The model has no search mappings available');
    }
}