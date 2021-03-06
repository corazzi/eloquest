<?php
namespace Corazzi\Eloquest\Concerns;

use Illuminate\Database\Eloquent\Builder;

class EloquestSearchResolver
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
        // Run the methods for query parameters that exist and are not empty
        collect($this->map)->each(function ($method, $arg) {
            if (! empty($this->request->get($arg))) {
                call_user_func([$this, $method]);
            }
        });


        // Run any set up defaults for parameters that are empty
        $this->setDefaults();

        return $this->builder;
    }

    /**
     * Build the queries for any defaults if there's a default map
     *
     * @return EloquestSearchResolver
     */
    public function setDefaults() : EloquestSearchResolver
    {
        if (isset($this->defaults)) {
            collect($this->defaults)->each(function ($method, $arg) {
               if (empty($this->request->get($arg))) {
                   call_user_func([$this, $method]);
               }
            });
        }

        return $this;
    }

    /**
    * Format a string as a WHERE LIKE operator, %like this%, or %this, or this%
    *
    * @param string  $string
    * @param string  $type
    *
    * @return string
    */
    public function formatLikeString(string $string, string $type = 'loose') : string
    {
       switch ($type) {
           case 'left':
               return sprintf('%%%s', $string);
           case 'right':
               return sprintf('%s%%', $string);
           case 'loose':
           default:
               return sprintf('%%%s%%', $string);
       }
    }
}
