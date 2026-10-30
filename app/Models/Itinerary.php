<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Itinerary extends Model
{
    protected $fillable = ['user_id','title','duration_minutes'];
    public function user() { return $this->belongsTo(User::class); }
    public function items() { return $this->hasMany(ItineraryItem::class)->orderBy('sequence'); }
}
