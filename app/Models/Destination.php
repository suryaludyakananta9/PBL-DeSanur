<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    protected $fillable = ['category_id','name','slug','description','address','latitude','longitude','price_min','price_max','opening_hours','estimated_visit_minute','status','source_url','verified_at'];
    protected $casts = ['latitude'=>'float','longitude'=>'float','price_min'=>'float','price_max'=>'float','verified_at'=>'datetime'];

    public function category() { return $this->belongsTo(Category::class); }
    public function images() { return $this->hasMany(DestinationImage::class)->orderBy('sort_order'); }
    public function facilities() { return $this->belongsToMany(Facility::class, 'destination_facility'); }
    public function reviews() { return $this->hasMany(Review::class); }
    public function approvedReviews() { return $this->hasMany(Review::class)->where('status', 'approved'); }
    public function favorites() { return $this->hasMany(Favorite::class); }
}
