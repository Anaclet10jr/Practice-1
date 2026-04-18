@extends('layouts.app')
@section('title', 'Properties')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

    {{-- ── Filter bar ───────────────────────────────────────────── --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
        <form method="GET" action="{{ route('properties.index') }}" class="flex flex-wrap gap-3 items-end">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Type</label>
                <select name="type" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary outline-none">
                    <option value="">All Types</option>
                    <option value="sale" {{ request('type') === 'sale' ? 'selected' : '' }}>For Sale</option>
                    <option value="rent" {{ request('type') === 'rent' ? 'selected' : '' }}>For Rent</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">District</label>
                <select name="district" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary outline-none">
                    <option value="">All Districts</option>
                    @foreach($districts as $d)
                    <option value="{{ $d }}" {{ request('district') === $d ? 'selected' : '' }}>{{ $d }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Min Price (RWF)</label>
                <input type="number" name="min_price" value="{{ request('min_price') }}"
                       class="w-36 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary outline-none"
                       placeholder="0">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Max Price (RWF)</label>
                <input type="number" name="max_price" value="{{ request('max_price') }}"
                       class="w-36 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary outline-none"
                       placeholder="Any">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Min Beds</label>
                <select name="bedrooms" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary outline-none">
                    <option value="">Any</option>
                    @foreach([1,2,3,4,5] as $n)
                    <option value="{{ $n }}" {{ request('bedrooms') == $n ? 'selected' : '' }}>{{ $n }}+</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-medium text-gray-600 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary outline-none"
                       placeholder="Keyword, address, district…">
            </div>
            <button type="submit"
                    class="bg-primary text-white font-semibold px-5 py-2 rounded-lg hover:bg-primary-hover transition text-sm">
                Search
            </button>
            @if(request()->hasAny(['type','district','min_price','max_price','bedrooms','search']))
            <a href="{{ route('properties.index') }}" class="text-sm text-gray-500 hover:text-gray-700 py-2">Clear</a>
            @endif
        </form>
    </div>

    {{-- ── Results header ───────────────────────────────────────── --}}
    <div class="flex items-center justify-between mb-4">
        <p class="text-sm text-gray-600">
            <span class="font-semibold text-gray-900">{{ number_format($properties->total()) }}</span>
            {{ Str::plural('property', $properties->total()) }} found
        </p>
    </div>

    {{-- ── Property grid ─────────────────────────────────────────── --}}
    @if($properties->isEmpty())
    <div class="text-center py-20 text-gray-400">
        <p class="text-4xl mb-3">🔍</p>
        <p class="font-medium text-gray-600">No properties match your search</p>
        <a href="{{ route('properties.index') }}" class="text-primary text-sm mt-2 inline-block hover:underline">Clear filters</a>
    </div>
    @else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        @foreach($properties as $property)
        <a href="{{ route('properties.show', $property) }}"
           class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md hover:-translate-y-0.5 transition group">

            {{-- Cover image --}}
            <div class="aspect-[4/3] bg-gray-100 overflow-hidden relative">
                @if($property->cover_image)
                    <img src="{{ Storage::url($property->cover_image) }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                         alt="{{ $property->title }}">
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-200 text-5xl">🏠</div>
                @endif
                {{-- Type badge --}}
                <span class="absolute top-2 left-2 text-xs font-bold px-2 py-1 rounded-full
                    {{ $property->type === 'rent' ? 'bg-purple-600 text-white' : 'bg-green-600 text-white' }}">
                    For {{ ucfirst($property->type === 'both' ? 'Sale/Rent' : $property->type) }}
                </span>
                @if($property->is_featured)
                <span class="absolute top-2 right-2 text-xs font-bold bg-accent text-white px-2 py-1 rounded-full">⭐ Featured</span>
                @endif
            </div>

            {{-- Card body --}}
            <div class="p-4">
                <p class="font-semibold text-gray-900 text-sm line-clamp-2 mb-1">{{ $property->title }}</p>
                <p class="text-xs text-gray-500 mb-3">📍 {{ $property->district }}{{ $property->sector ? ', ' . $property->sector : '' }}</p>

                <div class="flex items-center gap-3 text-xs text-gray-500 mb-3">
                    @if($property->bedrooms)  <span>🛏 {{ $property->bedrooms }} bed</span> @endif
                    @if($property->bathrooms) <span>🚿 {{ $property->bathrooms }} bath</span> @endif
                    @if($property->area_sqm)  <span>📐 {{ number_format($property->area_sqm) }}m²</span> @endif
                </div>

                <p class="font-bold text-primary text-base">{{ $property->formatted_price }}</p>
            </div>
        </a>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $properties->links() }}
    </div>
    @endif

</div>
@endsection

<h1 class="text-2xl font-bold mb-4">Properties</h1>

<a href="/properties/create" class="bg-blue-500 text-white px-4 py-2 rounded">
    Add Property
</a>

@foreach($properties as $property)
    <div class="border p-4 mt-4 rounded">
        <h2 class="text-xl font-bold">{{ $property->title }}</h2>
        <p>{{ $property->description }}</p>
        <p class="text-green-600">{{ $property->price }} USD</p>
        <p>{{ $property->address }}</p>
    </div>
@endforeach
