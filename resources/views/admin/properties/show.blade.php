@extends('layouts.app')
@section('title', 'Review: ' . $property->title)

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">

    <a href="{{ route('admin.properties') }}" class="text-sm text-gray-500 hover:text-primary">← Back to Properties</a>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 mt-4 overflow-hidden">

        {{-- Header --}}
        <div class="flex items-start justify-between p-6 border-b">
            <div>
                <h1 class="text-xl font-bold text-gray-900">{{ $property->title }}</h1>
                <p class="text-gray-500 text-sm mt-1">Submitted by {{ $property->agent->name }} · {{ $property->created_at->diffForHumans() }}</p>
            </div>
            <span @class([
                'text-sm px-3 py-1 rounded-full font-medium',
                'bg-amber-100 text-amber-700' => $property->status === 'pending',
                'bg-green-100 text-green-700'  => $property->status === 'approved',
                'bg-red-100 text-red-700'      => $property->status === 'rejected',
            ])>{{ ucfirst($property->status) }}</span>
        </div>

        {{-- Images --}}
        @if(!empty($property->images))
        <div class="p-4 grid grid-cols-4 gap-2">
            @foreach($property->images as $img)
            <img src="{{ Storage::url($img) }}" class="rounded-lg aspect-square object-cover w-full">
            @endforeach
        </div>
        @endif

        {{-- Details --}}
        <div class="p-6 grid grid-cols-2 md:grid-cols-3 gap-4 border-t text-sm">
            <div><p class="text-gray-500 text-xs">Type</p><p class="font-medium mt-0.5 capitalize">{{ $property->type }}</p></div>
            <div><p class="text-gray-500 text-xs">Price</p><p class="font-medium mt-0.5">{{ $property->formatted_price }}</p></div>
            <div><p class="text-gray-500 text-xs">District</p><p class="font-medium mt-0.5">{{ $property->district }}</p></div>
            <div><p class="text-gray-500 text-xs">Bedrooms</p><p class="font-medium mt-0.5">{{ $property->bedrooms }}</p></div>
            <div><p class="text-gray-500 text-xs">Bathrooms</p><p class="font-medium mt-0.5">{{ $property->bathrooms }}</p></div>
            <div><p class="text-gray-500 text-xs">Area</p><p class="font-medium mt-0.5">{{ $property->area_sqm ? number_format($property->area_sqm) . ' m²' : '—' }}</p></div>
        </div>

        <div class="px-6 pb-6">
            <p class="text-xs text-gray-500 mb-1">Description</p>
            <p class="text-sm text-gray-700 leading-relaxed">{{ $property->description }}</p>
        </div>

        @if(!empty($property->features))
        <div class="px-6 pb-6">
            <p class="text-xs text-gray-500 mb-2">Features</p>
            <div class="flex flex-wrap gap-2">
                @foreach($property->features as $feature)
                <span class="text-xs bg-blue-50 text-blue-700 px-2.5 py-1 rounded-full">{{ $feature }}</span>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Moderation actions --}}
        <div class="flex flex-wrap gap-3 p-6 border-t bg-gray-50">

            @if($property->status !== 'approved')
            <form method="POST" action="{{ route('admin.properties.approve', $property) }}">
                @csrf
                <button class="bg-green-600 hover:bg-green-700 text-white font-semibold px-5 py-2.5 rounded-xl transition text-sm">
                    ✓ Approve & Publish
                </button>
            </form>
            @endif

            {{-- Reject with reason --}}
            <button onclick="document.getElementById('rejectForm').classList.toggle('hidden')"
                    class="bg-red-100 hover:bg-red-200 text-red-700 font-semibold px-5 py-2.5 rounded-xl transition text-sm">
                ✕ Reject
            </button>

            <form method="POST" action="{{ route('admin.properties.feature', $property) }}">
                @csrf
                <button class="bg-amber-100 hover:bg-amber-200 text-amber-700 font-semibold px-5 py-2.5 rounded-xl transition text-sm">
                    {{ $property->is_featured ? '★ Unfeature' : '☆ Feature' }}
                </button>
            </form>

            <form method="POST" action="{{ route('admin.properties.destroy', $property) }}"
                  onsubmit="return confirm('Permanently delete this property?')">
                @csrf @method('DELETE')
                <button class="text-sm text-red-500 hover:underline px-2 py-2.5">Delete</button>
            </form>
        </div>

        {{-- Reject form (hidden by default) --}}
        <div id="rejectForm" class="hidden px-6 pb-6">
            <form method="POST" action="{{ route('admin.properties.reject', $property) }}" class="space-y-3">
                @csrf
                <label class="block text-sm font-medium text-gray-700">Rejection reason (shown to agent)</label>
                <textarea name="rejection_reason" rows="3" required
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-400 outline-none resize-none"
                          placeholder="Please explain why this listing is being rejected…">{{ $property->rejection_reason }}</textarea>
                <button type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white font-semibold px-5 py-2.5 rounded-xl transition text-sm">
                    Confirm Rejection
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
