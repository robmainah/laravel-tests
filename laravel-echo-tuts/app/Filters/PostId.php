<?php

namespace App\Filters;

use App\Filters\AbstractFilter;

class PostId extends AbstractFilter
{
    protected function filter($builder)
    {
        return $builder->where($this->filterName(), request($this->filterName()));
    }
}
