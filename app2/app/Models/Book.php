<?php

namespace App\Models;

use Exception;
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

    public function checkout(User $user) 
    {
        $this->reservations()->create([
            'user_id' => $user->id,
            'checked_out_at' => now()
        ]);
    }

    public function checkin($user) 
    {
        $reservation = $this->reservations()->where('user_id', $user->id)
            ->whereNull('checked_in_at')
            ->first();

        if (!$reservation) {
            throw new Exception;
        }

        $reservation->update([
            'checked_in_at' => now(),
        ]);
    }

    public function reservations() 
    {
        return $this->hasMany(Reservation::class);
    }
}
