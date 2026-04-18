@extends('layouts.app')
@section('title', 'Rwanda Realty — Buy, Rent & Sell Property in Rwanda')

@section('content')

{{-- ── Hero ──────────────────────────────────────────────────────── --}}
<section class="bg-gradient-to-br from-blue-700 to-indigo-800 text-white py-20 px-4">
    <div class="max-w-4xl mx-auto text-center">
        <h1 class="text-4xl md:text-5xl font-extrabold mb-4 leading-tight">
            Find Your Perfect Property<br>in Rwanda
        </h1>
        <p class="text-blue-100 text-lg mb-10">
            Browse thousands of verified listings across all 30 districts.
            Buy, rent, or list your property today.
        </p>

        {{-- Quick search --}}
        <form method="GET" action="{{ route('properties.index') }}"
              class="bg-white rounded-2xl p-2 flex flex-wrap gap-2 max-w-2xl mx-auto shadow-2xl">
            <select name="type" class="flex-1 min-w-[120px] text-gray-700 text-sm px-3 py-2 rounded-xl outline-none">
                <option value="">Buy or Rent</option>
                <option value="sale">Buy</option>
                <option value="rent">Rent</option>
            </select>
            <input type="text" name="search"
                   class="flex-1 min-w-[180px] text-gray-700 text-sm px-3 py-2 rounded-xl outline-none border border-gray-100"
                   placeholder="District, neighbourhood, keyword…">
            <button type="submit"
                    class="bg-primary text-white font-semibold px-6 py-2.5 rounded-xl hover:bg-primary-hover transition text-sm">
                Search
            </button>
        </form>

        {{-- Quick stats --}}
        <div class="flex justify-center gap-8 mt-10 text-blue-100 text-sm">
            <span>🏘 <strong class="text-white">500+</strong> Listings</span>
            <span>👔 <strong class="text-white">50+</strong> Agents</span>
            <span>📍 <strong class="text-white">30</strong> Districts</span>
        </div>
    </div>
</section>

{{-- ── Featured properties ─────────────────────────────────────────── --}}
@if($featured->isNotEmpty())
<section class="max-w-7xl mx-auto px-4 py-14">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Featured Properties</h2>
        <a href="{{ route('properties.index') }}" class="text-sm text-primary hover:underline font-medium">
            View all →
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($featured as $property)
        <a href="{{ route('properties.show', $property) }}"
           class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg hover:-translate-y-1 transition group">
            <div class="aspect-[4/3] bg-gray-100 overflow-hidden relative">
                @if($property->cover_image)
                    <img src="{{ Storage::url($property->cover_image) }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                         alt="{{ $property->title }}">
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-200 text-5xl">🏠</div>
                @endif
                <span class="absolute top-3 left-3 text-xs font-bold px-2.5 py-1 rounded-full
                    {{ $property->type === 'rent' ? 'bg-purple-600 text-white' : 'bg-green-600 text-white' }}">
                    For {{ ucfirst($property->type === 'both' ? 'Sale/Rent' : $property->type) }}
                </span>
                <span class="absolute top-3 right-3 bg-accent text-white text-xs font-bold px-2.5 py-1 rounded-full">
                    ⭐ Featured
                </span>
            </div>
            <div class="p-5">
                <p class="font-semibold text-gray-900 mb-1 line-clamp-1">{{ $property->title }}</p>
                <p class="text-sm text-gray-500 mb-3">📍 {{ $property->district }}</p>
                <div class="flex items-center gap-4 text-xs text-gray-500 mb-3">
                    @if($property->bedrooms)  <span>🛏 {{ $property->bedrooms }}</span> @endif
                    @if($property->bathrooms) <span>🚿 {{ $property->bathrooms }}</span> @endif
                    @if($property->area_sqm)  <span>📐 {{ number_format($property->area_sqm) }}m²</span> @endif
                </div>
                <p class="font-bold text-primary text-lg">{{ $property->formatted_price }}</p>
            </div>
        </a>
        @endforeach
    </div>
</section>
@endif

{{-- ── Call to action ───────────────────────────────────────────────── --}}
<section class="bg-gray-900 text-white py-16 px-4">
    <div class="max-w-4xl mx-auto text-center">
        <h2 class="text-3xl font-bold mb-3">Are You a Property Agent?</h2>
        <p class="text-gray-400 mb-8">
            List your properties on Rwanda's fastest growing real estate platform
            and reach thousands of qualified buyers and renters.
        </p>
        <a href="{{ route('register') }}"
           class="bg-accent hover:bg-accent-hover text-white font-semibold px-8 py-4 rounded-xl inline-block transition text-sm">
            Register as an Agent →
        </a>
    </div>
</section>

@endsection
