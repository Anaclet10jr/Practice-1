@extends('layouts.app')
@section('title', 'Admin — Agents')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Agents</h1>
        <div class="flex gap-2">
            @foreach(['all' => 'All', 'pending' => 'Pending', 'approved' => 'Approved'] as $val => $label)
            <a href="{{ route('admin.agents', $val === 'all' ? [] : ['status' => $val]) }}"
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
                    <th class="text-left px-4 py-3">Agent</th>
                    <th class="text-left px-4 py-3 hidden md:table-cell">Agency</th>
                    <th class="text-left px-4 py-3 hidden sm:table-cell">Properties</th>
                    <th class="text-left px-4 py-3">Status</th>
                    <th class="text-right px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($agents as $agent)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-primary flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                {{ strtoupper(substr($agent->name, 0, 2)) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $agent->name }}</p>
                                <p class="text-xs text-gray-500">{{ $agent->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 hidden md:table-cell text-gray-600">
                        {{ $agent->agency_name ?? '—' }}
                        @if($agent->license_number)
                        <p class="text-xs text-gray-400">{{ $agent->license_number }}</p>
                        @endif
                    </td>
                    <td class="px-4 py-3 hidden sm:table-cell text-gray-600">
                        {{ $agent->properties_count }}
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-xs px-2.5 py-1 rounded-full font-medium
                            {{ $agent->is_approved ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                            {{ $agent->is_approved ? 'Approved' : 'Pending' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            @if(!$agent->is_approved)
                            <form method="POST" action="{{ route('admin.agents.approve', $agent) }}">
                                @csrf
                                <button class="text-xs bg-green-100 hover:bg-green-200 text-green-700 px-3 py-1 rounded-full font-medium transition">
                                    Approve
                                </button>
                            </form>
                            @else
                            <form method="POST" action="{{ route('admin.agents.reject', $agent) }}">
                                @csrf
                                <button class="text-xs bg-amber-100 hover:bg-amber-200 text-amber-700 px-3 py-1 rounded-full font-medium transition">
                                    Revoke
                                </button>
                            </form>
                            @endif
                            <form method="POST" action="{{ route('admin.agents.destroy', $agent) }}"
                                  onsubmit="return confirm('Remove this agent? Their properties will also be deleted.')">
                                @csrf @method('DELETE')
                                <button class="text-xs text-red-500 hover:underline">Remove</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-12 text-center text-gray-400">No agents found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $agents->links() }}</div>
</div>
@endsection
