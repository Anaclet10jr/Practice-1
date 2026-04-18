@extends('layouts.app')
@section('title', 'Admin — Properties')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Properties</h1>
        <div class="flex gap-2">
            @foreach(['all' => 'All', 'pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'] as $val => $label)
            <a href="{{ route('admin.properties', $val === 'all' ? [] : ['status' => $val]) }}"
               class="text-xs px-3 py-1.5 rounded-full font-medium transition
                {{ request('status', 'all') === $val ? 'bg-primary text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                {{ $label }}
            </a>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs uppercase tracking-wider text-gray-500">
                <tr>
                    <th class="text-left px-4 py-3">Property</th>
                    <th class="text-left px-4 py-3 hidden md:table-cell">Agent</th>
                    <th class="text-left px-4 py-3 hidden md:table-cell">Price</th>
                    <th class="text-left px-4 py-3">Status</th>
                    <th class="text-right px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($properties as $property)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-gray-100 flex-shrink-0 overflow-hidden">
                                @if($property->cover_image)
                                    <img src="{{ Storage::url($property->cover_image) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-300">🏠</div>
                                @endif
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 line-clamp-1">{{ $property->title }}</p>
                                <p class="text-xs text-gray-500">{{ $property->district }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 hidden md:table-cell text-gray-600">{{ $property->agent->name }}</td>
                    <td class="px-4 py-3 hidden md:table-cell font-medium text-gray-700">{{ $property->formatted_price }}</td>
                    <td class="px-4 py-3">
                        <span @class([
                            'text-xs px-2.5 py-1 rounded-full font-medium',
                            'bg-amber-100 text-amber-700' => $property->status === 'pending',
                            'bg-green-100 text-green-700'  => $property->status === 'approved',
                            'bg-red-100 text-red-700'      => $property->status === 'rejected',
                        ])>{{ ucfirst($property->status) }}</span>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.properties.show', $property) }}"
                               class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1 rounded-full transition">
                                Review
                            </a>
                            @if($property->status !== 'approved')
                            <form method="POST" action="{{ route('admin.properties.approve', $property) }}">
                                @csrf
                                <button class="text-xs bg-green-100 hover:bg-green-200 text-green-700 px-3 py-1 rounded-full transition font-medium">
                                    Approve
                                </button>
                            </form>
                            @endif
                            <form method="POST" action="{{ route('admin.properties.feature', $property) }}">
                                @csrf
                                <button class="text-xs {{ $property->is_featured ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-600' }} hover:bg-amber-200 px-3 py-1 rounded-full transition">
                                    {{ $property->is_featured ? '⭐' : '☆' }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-12 text-center text-gray-400">No properties found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $properties->links() }}</div>
</div>
@endsection
