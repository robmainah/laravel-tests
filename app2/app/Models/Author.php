<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $dates = ['dob'];

    public function setDobAttribute($value)
    {
        return $this->attributes['dob'] = Carbon::parse($value);
    }

    public function getDobAttribute($value)
    {
        return $this->attributes['dob'] = Carbon::parse($value);
    }
}
