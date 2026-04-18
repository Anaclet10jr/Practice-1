@extends('layouts.app')
@section('title', 'Register')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center py-12 px-4">
    <div class="max-w-lg w-full bg-white rounded-2xl shadow-xl p-8">

        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 bg-primary rounded-xl mb-4">
                <span class="text-white text-2xl font-bold">RR</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Create your account</h1>
            <p class="text-gray-500 text-sm mt-1">Join Rwanda's premier property platform</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            {{-- Role selection --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">I want to…</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="role-card cursor-pointer">
                        <input type="radio" name="role" value="client"
                               class="sr-only" {{ old('role', 'client') === 'client' ? 'checked' : '' }}>
                        <div class="border-2 rounded-xl p-4 text-center transition hover:border-primary
                                    {{ old('role', 'client') === 'client' ? 'border-primary bg-blue-50' : 'border-gray-200' }}">
                            <div class="text-2xl mb-1">🏠</div>
                            <div class="font-semibold text-sm">Find a Property</div>
                            <div class="text-xs text-gray-500">Browse & contact agents</div>
                        </div>
                    </label>
                    <label class="role-card cursor-pointer">
                        <input type="radio" name="role" value="agent"
                               class="sr-only" {{ old('role') === 'agent' ? 'checked' : '' }}>
                        <div class="border-2 rounded-xl p-4 text-center transition hover:border-primary
                                    {{ old('role') === 'agent' ? 'border-primary bg-blue-50' : 'border-gray-200' }}">
                            <div class="text-2xl mb-1">🏢</div>
                            <div class="font-semibold text-sm">List Properties</div>
                            <div class="text-xs text-gray-500">Agent / agency</div>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Name --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Full name</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none"
                       placeholder="Jean-Pierre Habimana">
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email address</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none"
                       placeholder="you@example.com">
            </div>

            {{-- Phone --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone (optional)</label>
                <input type="tel" name="phone" value="{{ old('phone') }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none"
                       placeholder="+250 78X XXX XXX">
            </div>

            {{-- Agent-only fields --}}
            <div id="agent-fields" class="{{ old('role') === 'agent' ? '' : 'hidden' }} space-y-4 bg-blue-50 rounded-xl p-4">
                <p class="text-xs font-semibold text-primary uppercase tracking-wider">Agent Information</p>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Agency / Company name <span class="text-red-500">*</span></label>
                    <input type="text" name="agency_name" value="{{ old('agency_name') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none"
                           placeholder="Kigali Property Group">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">License number (optional)</label>
                    <input type="text" name="license_number" value="{{ old('license_number') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none"
                           placeholder="RDB-2024-XXXXX">
                </div>
                <p class="text-xs text-amber-700 bg-amber-50 border border-amber-200 rounded-lg p-2">
                    ⚠️ Agent accounts require admin approval before you can list properties.
                </p>
            </div>

            {{-- Password --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirm password</label>
                <input type="password" name="password_confirmation" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none">
            </div>

            <button type="submit"
                    class="w-full bg-primary hover:bg-primary-hover text-white font-semibold py-3 rounded-xl transition text-sm">
                Create account →
            </button>
        </form>

        <p class="text-center text-sm text-gray-500 mt-6">
            Already have an account?
            <a href="{{ route('login') }}" class="text-primary font-semibold hover:underline">Sign in</a>
        </p>
    </div>
</div>

@push('scripts')
<script>
    // Toggle agent fields based on role selection
    document.querySelectorAll('input[name="role"]').forEach(radio => {
        radio.addEventListener('change', function () {
            const agentFields = document.getElementById('agent-fields');
            agentFields.classList.toggle('hidden', this.value !== 'agent');

            // Update card visual state
            document.querySelectorAll('.role-card > div').forEach(card => {
                card.classList.remove('border-primary', 'bg-blue-50');
                card.classList.add('border-gray-200');
            });
            this.closest('.role-card').querySelector('div').classList.add('border-primary', 'bg-blue-50');
            this.closest('.role-card').querySelector('div').classList.remove('border-gray-200');
        });
    });
</script>
@endpush
@endsection
