@extends('layouts.app')
@section('title', 'My Properties')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">My Properties</h1>
        <a href="{{ route('agent.properties.create') }}"
           class="bg-primary text-white font-semibold px-5 py-2.5 rounded-xl hover:bg-primary-hover transition text-sm">
            + New Property
        </a>
    </div>

    @if($properties->isEmpty())
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-16 text-center text-gray-400">
        <p class="text-4xl mb-3">🏠</p>
        <p class="font-medium text-gray-600 mb-1">No properties yet</p>
        <p class="text-sm mb-6">List your first property and start connecting with buyers and renters.</p>
        <a href="{{ route('agent.properties.create') }}"
           class="bg-primary text-white font-semibold px-6 py-2.5 rounded-xl hover:bg-primary-hover transition text-sm inline-block">
            List a Property
        </a>
    </div>
    @else
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs uppercase tracking-wider text-gray-500">
                <tr>
                    <th class="text-left px-4 py-3">Property</th>
                    <th class="text-left px-4 py-3 hidden md:table-cell">Type</th>
                    <th class="text-left px-4 py-3 hidden md:table-cell">Price</th>
                    <th class="text-left px-4 py-3">Status</th>
                    <th class="text-left px-4 py-3 hidden sm:table-cell">Views</th>
                    <th class="text-right px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($properties as $property)
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
                    <td class="px-4 py-3 hidden md:table-cell">
                        <span class="capitalize">{{ $property->type }}</span>
                    </td>
                    <td class="px-4 py-3 hidden md:table-cell text-gray-700 font-medium">
                        {{ $property->formatted_price }}
                    </td>
                    <td class="px-4 py-3">
                        <span @class([
                            'text-xs px-2.5 py-1 rounded-full font-medium',
                            'bg-amber-100 text-amber-700' => $property->status === 'pending',
                            'bg-green-100 text-green-700'  => $property->status === 'approved',
                            'bg-red-100 text-red-700'      => $property->status === 'rejected',
                            'bg-blue-100 text-blue-700'    => in_array($property->status, ['sold','rented']),
                        ])>{{ ucfirst($property->status) }}</span>
                        @if($property->status === 'rejected' && $property->rejection_reason)
                            <p class="text-xs text-red-500 mt-1 max-w-xs">{{ Str::limit($property->rejection_reason, 60) }}</p>
                        @endif
                    </td>
                    <td class="px-4 py-3 hidden sm:table-cell text-gray-500">
                        {{ number_format($property->views_count) }}
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('agent.properties.edit', $property) }}"
                               class="text-xs text-primary hover:underline">Edit</a>
                            <form method="POST" action="{{ route('agent.properties.destroy', $property) }}"
                                  onsubmit="return confirm('Delete this property? This cannot be undone.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-red-500 hover:underline">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $properties->links() }}
    </div>
    @endif
</div>
@endsection
