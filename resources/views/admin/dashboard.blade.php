@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
        <span class="text-sm text-gray-500">{{ now()->format('l, d M Y') }}</span>
    </div>

    {{-- Stats grid --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        @foreach([
            ['label' => 'Total Properties', 'value' => $stats['total_properties'],   'icon' => '🏘️',  'color' => 'blue'],
            ['label' => 'Pending Review',   'value' => $stats['pending_properties'],  'icon' => '⏳',  'color' => 'amber', 'link' => route('admin.properties', ['status' => 'pending'])],
            ['label' => 'Total Agents',     'value' => $stats['total_agents'],        'icon' => '👔',  'color' => 'indigo'],
            ['label' => 'Pending Agents',   'value' => $stats['pending_agents'],      'icon' => '🔔',  'color' => 'red', 'link' => route('admin.agents', ['status' => 'pending'])],
        ] as $stat)
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-gray-500">{{ $stat['label'] }}</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stat['value'] }}</p>
                </div>
                <span class="text-2xl">{{ $stat['icon'] }}</span>
            </div>
            @isset($stat['link'])
            <a href="{{ $stat['link'] }}" class="text-xs text-primary hover:underline mt-2 inline-block">View all →</a>
            @endisset
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Pending properties --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between p-5 border-b">
                <h2 class="font-semibold text-gray-900">Pending Properties</h2>
                <a href="{{ route('admin.properties', ['status' => 'pending']) }}" class="text-xs text-primary hover:underline">View all</a>
            </div>
            <div class="divide-y">
                @forelse($recentProperties as $property)
                <div class="p-4 flex items-center justify-between">
                    <div class="min-w-0">
                        <p class="font-medium text-sm text-gray-900 truncate">{{ $property->title }}</p>
                        <p class="text-xs text-gray-500">by {{ $property->agent->name }} · {{ $property->district }}</p>
                    </div>
                    <div class="flex items-center gap-2 ml-4 flex-shrink-0">
                        <form method="POST" action="{{ route('admin.properties.approve', $property) }}">
                            @csrf
                            <button class="text-xs bg-green-100 text-green-700 hover:bg-green-200 px-3 py-1 rounded-full font-medium transition">
                                Approve
                            </button>
                        </form>
                        <a href="{{ route('admin.properties.show', $property) }}"
                           class="text-xs bg-gray-100 text-gray-700 hover:bg-gray-200 px-3 py-1 rounded-full font-medium transition">
                            Review
                        </a>
                    </div>
                </div>
                @empty
                <p class="p-5 text-sm text-gray-400 text-center">No pending properties 🎉</p>
                @endforelse
            </div>
        </div>

        {{-- Pending agents --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between p-5 border-b">
                <h2 class="font-semibold text-gray-900">Pending Agent Applications</h2>
                <a href="{{ route('admin.agents', ['status' => 'pending']) }}" class="text-xs text-primary hover:underline">View all</a>
            </div>
            <div class="divide-y">
                @forelse($pendingAgents as $agent)
                <div class="p-4 flex items-center justify-between">
                    <div class="min-w-0">
                        <p class="font-medium text-sm text-gray-900">{{ $agent->name }}</p>
                        <p class="text-xs text-gray-500">{{ $agent->agency_name ?? $agent->email }}</p>
                    </div>
                    <div class="flex items-center gap-2 ml-4 flex-shrink-0">
                        <form method="POST" action="{{ route('admin.agents.approve', $agent) }}">
                            @csrf
                            <button class="text-xs bg-green-100 text-green-700 hover:bg-green-200 px-3 py-1 rounded-full font-medium transition">
                                Approve
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.agents.reject', $agent) }}">
                            @csrf
                            <button class="text-xs bg-red-100 text-red-700 hover:bg-red-200 px-3 py-1 rounded-full font-medium transition">
                                Reject
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <p class="p-5 text-sm text-gray-400 text-center">No pending applications 🎉</p>
                @endforelse
            </div>
        </div>

    </div>

    {{-- Quick links --}}
    <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-3">
        @foreach([
            ['label' => 'All Properties', 'href' => route('admin.properties'), 'icon' => '🏘️'],
            ['label' => 'Manage Agents',  'href' => route('admin.agents'),     'icon' => '👔'],
            ['label' => 'All Users',      'href' => route('admin.users'),      'icon' => '👥'],
            ['label' => 'View Site',      'href' => route('home'),             'icon' => '🌐'],
        ] as $link)
        <a href="{{ $link['href'] }}"
           class="flex items-center gap-2 bg-white border border-gray-200 rounded-xl p-4 text-sm font-medium text-gray-700 hover:border-primary hover:text-primary transition">
            <span>{{ $link['icon'] }}</span> {{ $link['label'] }}
        </a>
        @endforeach
    </div>

</div>
@endsection
