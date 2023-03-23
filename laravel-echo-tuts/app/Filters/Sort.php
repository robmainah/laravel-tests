<?php

namespace App\Filters;

use App\Filters\AbstractFilter;

class Sort extends AbstractFilter
{
    protected function filter($builder) 
    {
        return $builder->orderBy('user_id', request($this->filterName()));
    }
}
