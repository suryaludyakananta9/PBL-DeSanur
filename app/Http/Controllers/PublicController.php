<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function home() {
        $destinations = \App\Models\Destination::with(['category','images'])->where('status','published')->latest()->take(4)->get();
        $categories = \App\Models\Category::withCount(['destinations'=>fn($q)=>$q->where('status','published')])->get();
        return view('pages.home', compact('destinations','categories'));
    }

    public function explore(Request $request) {
        $query = \App\Models\Destination::with(['category','images'])->where('status','published');
        if ($request->filled('category')) $query->where('category_id',$request->category);
        if ($request->filled('q')) $query->where('name','like','%'.$request->q.'%');
        $destinations = $query->paginate(12)->withQueryString();
        $categories = \App\Models\Category::orderBy('name')->get();
        return view('pages.explore', compact('destinations','categories'));
    }

    public function search(Request $request) {
        $query = \App\Models\Destination::with(['category','images'])->where('status','published');
        if ($request->filled('q')) $query->where(function($q) use($request) {$q->where('name','like','%'.$request->q.'%')->orWhere('description','like','%'.$request->q.'%');});
        if ($request->filled('category')) $query->where('category_id',$request->category);
        if ($request->filled('min_price')) $query->where('price_min','>=',$request->min_price);
        if ($request->filled('max_price')) $query->where(function($q) use($request) {$q->whereNull('price_max')->orWhere('price_max','<=',$request->max_price);});
        $destinations = $query->paginate(12)->withQueryString();
        $categories = \App\Models\Category::orderBy('name')->get();
        return view('pages.search', compact('destinations','categories'));
    }

    public function detail($slug) {
        $destination = \App\Models\Destination::with(['category','images','facilities','approvedReviews.user'])->where('slug',$slug)->where('status','published')->firstOrFail();
        $favorite = auth()->check() ? $destination->favorites()->where('user_id',auth()->id())->exists() : false;
        return view('pages.detail', compact('destination','favorite'));
    }

    public function recommendation(Request $request) {
        $results = collect();
        if ($request->filled('budget')) {
            $results = \App\Models\Destination::with(['category','images'])->where('status','published')->whereNotNull('price_min')->where('price_min','<=',$request->budget)->when($request->filled('category'), fn($q)=>$q->where('category_id',$request->category))->take(12)->get();
        }
        $categories = \App\Models\Category::orderBy('name')->get();
        return view('pages.recommendation', compact('results','categories'));
    }

    public function planner(Request $request) {
        $destinations = collect();
        if ($request->filled('duration') || $request->filled('budget')) {
            $budget = (float)($request->budget ?? PHP_INT_MAX);
            $destinations = \App\Models\Destination::with(['category','images'])->where('status','published')->whereNotNull('price_min')->where('price_min','<=',$budget)->take((int)($request->duration === 'day' ? 8 : ($request->duration === '5hours' ? 5 : 3)))->get();
        }
        return view('pages.planner', compact('destinations'));
    }
}
