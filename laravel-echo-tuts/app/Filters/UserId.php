<?php

namespace App\Filters;

use App\Filters\AbstractFilter;

class UserId extends AbstractFilter
{
    protected function filter($builder)
    {
        return $builder->whereIn('user_id', [request('user_id'), 2]);
    }
}
