<?php

namespace Sachiano\Eloquest\Traits;

use Illuminate\Database\Eloquent\Builder;

trait BuildsQueries
{
    /**
     * Add on the extra query methods
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function alterQuery(Builder $query) : Builder
    {
        // Check the request even includes the field we're querying by
        if (! $this->getRequestParameter()) {
            return $query;
        }

        $this->query = $query;

        // In the case we have multiple parameters, loop over them
        // and add a separate clause for each
        //
        // Caveat: only supports AND for now
        collect($this->getRequestParameter())->each(function ($parameter) {
            $this->{$this->clause}($parameter);
        });

        return $this->query;
    }

    /**
     * Add a where($foo, 'LIKE', $bar) clause to the query
     *
     * @param $parameter
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function whereLike($parameter) : Builder
    {
        switch ($this->getOption('match')) {
            case 'left':
                $parameter = sprintf('%%%s', $parameter);
                break;
            case 'right':
                $parameter = sprintf('%s%%', $parameter);
                break;
            case 'loose':
            default:
                $parameter = sprintf('%%%s%%', $parameter);
        }

        return $this->query->where(
            $this->field,
            'LIKE',
            $parameter
        );
    }

    /**
     * Add a ->whereHas($relation) clause
     *
     * @param $relation
     *
     * @return mixed
     */
    public function whereHas($relation)
    {
        return $this->query->whereHas($relation);
    }

    /**
     * Add a ->whereDoesntHave($relation) clause
     *
     * @param $relation
     *
     * @return mixed
     */
    public function whereDoesntHave($relation)
    {
        return $this->query->whereDoesntHave($relation);
    }
}