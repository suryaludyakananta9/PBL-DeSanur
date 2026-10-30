<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItineraryItem extends Model
{
    public $timestamps = false;
    protected $fillable = ['itinerary_id','destination_id','sequence','planned_duration'];
    public function itinerary() { return $this->belongsTo(Itinerary::class); }
    public function destination() { return $this->belongsTo(Destination::class); }
}
