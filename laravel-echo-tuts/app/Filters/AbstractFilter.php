<?php

namespace App\Filters;

use Closure;
use Illuminate\Support\Str;

abstract class AbstractFilter
{
    public function handle($request, Closure $next)
    {
        if (!request()->has($this->filterName())) {
            return $next($request);
        }

        $builder = $next($request);

        return $this->filter($builder);
    }

    protected function filterName() 
    {
        return Str::snake(class_basename($this));
    }

    protected abstract function filter($builder);
}
