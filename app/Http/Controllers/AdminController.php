<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard() {
        return view('admin.dashboard', [
            'totalDestinations'=>\App\Models\Destination::count(), 'totalCategories'=>\App\Models\Category::count(),
            'pendingReviews'=>\App\Models\Review::where('status','pending')->count(), 'reviewTotal'=>\App\Models\Review::count(),
        ]);
    }
    public function destinations(Request $request) {
        $query=\App\Models\Destination::with(['category','images']);
        if($request->filled('q')) $query->where('name','like','%'.$request->q.'%');
        if($request->filled('status')) $query->where('status',$request->status);
        $destinations=$query->latest()->paginate(15)->withQueryString(); $categories=\App\Models\Category::orderBy('name')->get();
        return view('admin.destinations',compact('destinations','categories'));
    }
    public function storeDestination(Request $request) {
        $data=$request->validate(['category_id'=>'required|exists:categories,id','name'=>'required|max:255','slug'=>'required|max:255|unique:destinations,slug','description'=>'nullable|string','address'=>'nullable|max:255','latitude'=>'nullable|numeric','longitude'=>'nullable|numeric','price_min'=>'nullable|numeric|min:0','price_max'=>'nullable|numeric|min:0','opening_hours'=>'nullable|max:255','estimated_visit_minute'=>'nullable|integer|min:1','status'=>'required|in:draft,published','source_url'=>'nullable|url','image'=>'nullable|image|max:2048']);
        if($request->hasFile('image')) $data['image_path']=$request->file('image')->store('destinations','public');
        $destination=\App\Models\Destination::create(collect($data)->except(['image','image_path'])->all());
        if(!empty($data['image_path'])) \App\Models\DestinationImage::create(['destination_id'=>$destination->id,'image_path'=>'/storage/'.$data['image_path'],'alt_text'=>$destination->name,'sort_order'=>0]);
        $destination->verified_at=$destination->status==='published'?now():null; $destination->save();
        return back()->with('success','Destinasi berhasil ditambahkan.');
    }
    public function updateDestination(Request $request, \App\Models\Destination $destination) {
        if ($request->has('status') && !$request->has('category_id')) {
            $request->validate(['status'=>'required|in:draft,published']);
            $destination->update(['status'=>$request->status]);
            $destination->verified_at=$destination->status==='published'?($destination->verified_at ?: now()):null; $destination->save();
            return back()->with('success','Status publikasi berhasil diperbarui.');
        }
        $data=$request->validate(['category_id'=>'required|exists:categories,id','name'=>'required|max:255','slug'=>'required|max:255|unique:destinations,slug,'.$destination->id,'description'=>'nullable|string','address'=>'nullable|max:255','latitude'=>'nullable|numeric','longitude'=>'nullable|numeric','price_min'=>'nullable|numeric|min:0','price_max'=>'nullable|numeric|min:0','opening_hours'=>'nullable|max:255','estimated_visit_minute'=>'nullable|integer|min:1','status'=>'required|in:draft,published','source_url'=>'nullable|url']);
        $destination->update($data); $destination->verified_at=$destination->status==='published'?($destination->verified_at ?: now()):null; $destination->save();
        return back()->with('success','Destinasi berhasil diperbarui.');
    }
    public function deleteDestination(\App\Models\Destination $destination) { $destination->delete(); return back()->with('success','Destinasi berhasil dihapus.'); }
    public function categories() { return view('admin.categories',['categories'=>\App\Models\Category::withCount('destinations')->get()]); }
    public function storeCategory(Request $request) { $data=$request->validate(['name'=>'required|max:255','slug'=>'required|max:255|unique:categories,slug','description'=>'nullable|string']); \App\Models\Category::create($data); return back()->with('success','Kategori berhasil ditambahkan.'); }
    public function updateCategory(Request $request, \App\Models\Category $category) { $data=$request->validate(['name'=>'required|max:255','slug'=>'required|max:255|unique:categories,slug,'.$category->id,'description'=>'nullable|string']); $category->update($data); return back()->with('success','Kategori berhasil diperbarui.'); }
    public function deleteCategory(\App\Models\Category $category) { if($category->destinations()->exists()) return back()->withErrors(['delete'=>'Kategori tidak dapat dihapus karena masih digunakan.']); $category->delete(); return back()->with('success','Kategori berhasil dihapus.'); }
    public function eventKuliner() { return view('admin.event-kuliner'); }
    public function reviews() { return view('admin.reviews',['reviews'=>\App\Models\Review::with(['user','destination'])->latest()->paginate(20)]); }
    public function moderateReview(Request $request, \App\Models\Review $review) { $request->validate(['status'=>'required|in:approved,rejected']); $review->update(['status'=>$request->status]); return back()->with('success','Status review berhasil diperbarui.'); }
}
