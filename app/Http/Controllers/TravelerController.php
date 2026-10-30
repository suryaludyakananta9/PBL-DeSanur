<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TravelerController extends Controller
{
    public function favorites() {
        $favorites = auth()->user()->favorites()->with(['destination.category','destination.images'])->get();
        return view('pages.favorites', compact('favorites'));
    }
    public function toggleFavorite(Request $request, \App\Models\Destination $destination) {
        $favorite = \App\Models\Favorite::where('user_id',auth()->id())->where('destination_id',$destination->id);
        if ($favorite->exists()) { $favorite->delete(); $message='Destinasi dihapus dari favorit.'; }
        else { \App\Models\Favorite::create(['user_id'=>auth()->id(),'destination_id'=>$destination->id]); $message='Destinasi ditambahkan ke favorit.'; }
        return back()->with('success',$message);
    }
    public function review(Request $request, \App\Models\Destination $destination) {
        $data = $request->validate(['rating'=>'required|integer|min:1|max:5','comment'=>'required|string|max:1000']);
        \App\Models\Review::create(['user_id'=>auth()->id(),'destination_id'=>$destination->id,'rating'=>$data['rating'],'comment'=>$data['comment'],'status'=>'pending']);
        return back()->with('success','Review terkirim dan berstatus pending menunggu moderasi admin.');
    }
    public function itineraries() {
        $itineraries = auth()->user()->itineraries()->with(['items.destination'])->latest()->get();
        return view('pages.itineraries', compact('itineraries'));
    }
    public function storeItinerary(Request $request) {
        $data = $request->validate(['title'=>'required|string|max:255','duration_minutes'=>'required|integer|min:180','destination_ids'=>'required|array|min:1']);
        $itinerary = \App\Models\Itinerary::create(['user_id'=>auth()->id(),'title'=>$data['title'],'duration_minutes'=>$data['duration_minutes']]);
        foreach (array_slice(array_unique($data['destination_ids']),0,8) as $index=>$destinationId) {
            $destination = \App\Models\Destination::find($destinationId);
            if ($destination) \App\Models\ItineraryItem::create(['itinerary_id'=>$itinerary->id,'destination_id'=>$destination->id,'sequence'=>$index+1,'planned_duration'=>$destination->estimated_visit_minute ?: 60]);
        }
        return redirect()->route('traveler.itineraries')->with('success','Itinerary berhasil disimpan.');
    }
    public function deleteItinerary(\App\Models\Itinerary $itinerary) {
        if ($itinerary->user_id !== auth()->id()) abort(403);
        $itinerary->delete(); return back()->with('success','Itinerary berhasil dihapus.');
    }
    public function profile() { return view('pages.profile'); }
}
