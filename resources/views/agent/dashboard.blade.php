@extends('layouts.app')
@section('title', 'Agent Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Welcome, {{ $user->name }}</h1>
            <p class="text-gray-500 text-sm mt-1">{{ $user->agency_name ?? 'Independent Agent' }}</p>
        </div>
        <a href="{{ route('agent.properties.create') }}"
           class="bg-primary text-white font-semibold px-5 py-2.5 rounded-xl hover:bg-primary-hover transition text-sm">
            + New Property
        </a>
    </div>

    {{-- Stats --}}
    @php
        $properties  = $user->properties;
        $totalCount  = $properties->count();
        $pending     = $properties->where('status', 'pending')->count();
        $approved    = $properties->where('status', 'approved')->count();
        $rejected    = $properties->where('status', 'rejected')->count();
        $totalViews  = $properties->sum('views_count');
    @endphp

    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
        @foreach([
            ['label' => 'Total',    'value' => $totalCount, 'color' => 'gray'],
            ['label' => 'Pending',  'value' => $pending,    'color' => 'amber'],
            ['label' => 'Approved', 'value' => $approved,   'color' => 'green'],
            ['label' => 'Rejected', 'value' => $rejected,   'color' => 'red'],
            ['label' => 'Views',    'value' => $totalViews, 'color' => 'blue'],
        ] as $stat)
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 text-center">
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stat['value']) }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $stat['label'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- Recent properties --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between p-5 border-b">
            <h2 class="font-semibold text-gray-900">My Properties</h2>
            <a href="{{ route('agent.properties.index') }}" class="text-xs text-primary hover:underline">View all</a>
        </div>

        @php $recent = $user->properties()->latest()->limit(8)->get(); @endphp

        @if($recent->isEmpty())
        <div class="p-12 text-center text-gray-400">
            <p class="text-4xl mb-3">🏠</p>
            <p class="font-medium">No properties yet</p>
            <a href="{{ route('agent.properties.create') }}" class="mt-4 inline-block text-primary text-sm hover:underline">
                List your first property →
            </a>
        </div>
        @else
        <div class="divide-y">
            @foreach($recent as $property)
            <div class="p-4 flex items-center justify-between">
                <div class="flex items-center gap-3 min-w-0">
                    {{-- Cover image thumbnail --}}
                    <div class="w-12 h-12 rounded-lg bg-gray-100 flex-shrink-0 overflow-hidden">
                        @if($property->cover_image)
                            <img src="{{ Storage::url($property->cover_image) }}"
                                 class="w-full h-full object-cover" alt="">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300 text-lg">🏠</div>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <p class="font-medium text-sm text-gray-900 truncate">{{ $property->title }}</p>
                        <p class="text-xs text-gray-500">{{ $property->district }} · {{ $property->formatted_price }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 ml-4 flex-shrink-0">
                    <span @class([
                        'text-xs px-2.5 py-1 rounded-full font-medium',
                        'bg-amber-100 text-amber-700' => $property->status === 'pending',
                        'bg-green-100 text-green-700'  => $property->status === 'approved',
                        'bg-red-100 text-red-700'      => $property->status === 'rejected',
                        'bg-gray-100 text-gray-700'    => in_array($property->status, ['sold', 'rented']),
                    ])>
                        {{ ucfirst($property->status) }}
                    </span>
                    <a href="{{ route('agent.properties.edit', $property) }}"
                       class="text-xs text-gray-500 hover:text-primary">Edit</a>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

</div>
@endsection
