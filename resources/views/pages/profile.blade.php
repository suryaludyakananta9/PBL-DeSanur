@extends('layouts.app')
@section('title','Profil')
@section('content')
<section class="content-page profile-page"><div class="profile-card"><span class="profile-avatar">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</span><h1>{{ auth()->user()->name }}</h1><p>{{ auth()->user()->email }}</p><nav><a href="{{ route('traveler.favorites') }}">Favorit Saya</a><a href="{{ route('traveler.itineraries') }}">Itinerary Saya</a></nav><form method="POST" action="{{ route('logout') }}">@csrf<button class="btn btn-danger">Logout</button></form></div></section>@endsection