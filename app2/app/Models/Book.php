<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{ 
    use HasFactory;

    protected $guarded = [];

    public function path() 
    {
        return "/books/{$this->id}";
    }

    public function setAuthorIdAttribute($value)
    {
        $this->attributes['author_id'] = Author::firstOrCreate([
            'name' => $value,
        ])->id;
    }
}