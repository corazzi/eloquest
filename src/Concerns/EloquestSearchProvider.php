<?php
namespace Sachiano\Eloquest\Concerns;

use Illuminate\Database\Eloquent\Builder;

class EloquestSearchProvider
{
    /**
     * The current request
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * The Eloquent query
     *
     * @var Builder
     */
    protected $builder;

    /**
     * Provider constructor.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     */
    public function __construct(Builder $builder)
    {
        $this->request = request();
        $this->builder = $builder;
    }

    /**
     * Build the queries
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function run() : Builder
    {
        collect($this->map)->each(function ($method, $arg) {
            if (null !== $this->request->get($arg)) {
                call_user_func([$this, $method]);
            }
        });

        return $this->builder;
    }
}