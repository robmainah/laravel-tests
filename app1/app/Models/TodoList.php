<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TodoList extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    // public static function boot()
    // {
    //     parent::boot();
    //     self::deleting(function ($todo_list) {
    //         $todo_list->tasks->each->delete();
    //         // $todo_list->tasks()->delete();
    //     });
    // }
}
