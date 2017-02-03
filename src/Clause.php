<?php

namespace Sachiano\Eloquest;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Sachiano\Eloquest\Traits\BuildsQueries;

class Clause
{
    use BuildsQueries;

    /**
     * Clause constructor.
     *
     * @param $map
     * @param $field
     * @param $parameters
     */
    public function __construct($map, $field, $parameters)
    {
        // Set the clause, e.g., where, whereIn
        $this->clause = Arr::get($map, 'clause');

        // Any extra options for the query
        $this->options = collect(
            Arr::except($map, 'clause')
        );

        // The field/column to search in
        $this->field = $field;

        // The request parameters
        $this->parameters = collect($parameters);
    }

    /**
     * Get an option from the mapping
     *
     * @param $key
     *
     * @return mixed
     */
    public function getOption($key)
    {
        return $this->options->get($key);
    }

    /**
     * Does the mapping have any field aliases? For example,
     * we can use either 'name' or 'client_name'
     *
     * @return bool
     */
    public function hasAlias() : bool
    {
        return $this->options->has('aliases');
    }

    /**
     * Get the alias parameter value
     *
     * @return mixed
     */
    public function getAliasParameter()
    {
        $aliases = collect($this->getOption('aliases'))->filter(function ($alias) {
           return $this->parameters->has($alias);
        });

        return $this->parameters->get(
            $aliases->first()
        );
    }

    /**
     * Get the parameter value
     *
     * @return mixed|null
     */
    public function getRequestParameter()
    {
        // If there's an alias parameter present, e.g.,
        // 'client_name' rather than 'name', use that value
        if ($this->hasAlias() && $this->getAliasParameter()) {
            return $this->getAliasParameter();
        }

        // Otherwise get the value from the field name
        return $this->parameters->has($this->field)
            ? $this->parameters->get($this->field)
            : null;
    }
}