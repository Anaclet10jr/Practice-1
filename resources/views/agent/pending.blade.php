@extends('layouts.app')
@section('title', 'Account Pending Approval')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full text-center">
        <div class="bg-white rounded-2xl shadow-xl p-10">
            <div class="text-6xl mb-4">⏳</div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Account Under Review</h1>
            <p class="text-gray-500 text-sm mb-6">
                Thanks for registering as an agent on Rwanda Realty. Our admin team is reviewing your application
                and will approve your account within 1–2 business days.
            </p>
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-left text-sm text-amber-800 mb-6">
                <p class="font-semibold mb-1">While you wait, you can:</p>
                <ul class="list-disc list-inside space-y-1 text-amber-700">
                    <li>Browse current property listings</li>
                    <li>Prepare your property photos and details</li>
                    <li>Contact us if you have questions</li>
                </ul>
            </div>
            <div class="flex flex-col gap-3">
                <a href="{{ route('properties.index') }}"
                   class="bg-primary text-white font-semibold py-3 rounded-xl hover:bg-primary-hover transition text-sm">
                    Browse Properties
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-sm text-gray-500 hover:text-gray-700 py-2">
                        Sign out
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
