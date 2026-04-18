@extends('layouts.app')
@section('title', isset($property) ? 'Edit Property' : 'List a Property')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">

    <div class="mb-6">
        <a href="{{ route('agent.properties.index') }}" class="text-sm text-gray-500 hover:text-primary">← My Properties</a>
        <h1 class="text-2xl font-bold text-gray-900 mt-2">
            {{ isset($property) ? 'Edit Property' : 'List a New Property' }}
        </h1>
        @unless(isset($property))
        <p class="text-gray-500 text-sm mt-1">Your listing will be reviewed by our team before going live.</p>
        @endunless
    </div>

    <form method="POST"
          action="{{ isset($property) ? route('agent.properties.update', $property) : route('agent.properties.store') }}"
          enctype="multipart/form-data"
          class="space-y-6">
        @csrf
        @isset($property) @method('PUT') @endisset

        {{-- ── Basic Info ─────────────────────────────────────── --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-semibold text-gray-900 mb-4">Basic Information</h2>
            <div class="space-y-4">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Property title <span class="text-red-500">*</span></label>
                    <input type="text" name="title"
                           value="{{ old('title', $property->title ?? '') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none"
                           placeholder="e.g. Modern 3BR Apartment in Kiyovu, Kigali">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-red-500">*</span></label>
                    <textarea name="description" rows="5"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none resize-none"
                              placeholder="Describe the property in detail — location highlights, recent renovations, nearby amenities…">{{ old('description', $property->description ?? '') }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Listing type <span class="text-red-500">*</span></label>
                        <select name="type" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary outline-none">
                            @foreach(['sale' => 'For Sale', 'rent' => 'For Rent', 'both' => 'Sale or Rent'] as $val => $label)
                            <option value="{{ $val }}" {{ old('type', $property->type ?? '') === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Price (RWF) <span class="text-red-500">*</span></label>
                        <input type="number" name="price"
                               value="{{ old('price', $property->price ?? '') }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none"
                               placeholder="e.g. 45000000">
                    </div>
                </div>

                {{-- Price period (shown for rent) --}}
                <div id="price-period-row">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rent period</label>
                    <select name="price_period" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary outline-none">
                        <option value="">— select —</option>
                        <option value="monthly" {{ old('price_period', $property->price_period ?? '') === 'monthly' ? 'selected' : '' }}>Per month</option>
                        <option value="yearly"  {{ old('price_period', $property->price_period ?? '') === 'yearly'  ? 'selected' : '' }}>Per year</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- ── Specs ───────────────────────────────────────────── --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-semibold text-gray-900 mb-4">Property Specs</h2>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bedrooms</label>
                    <input type="number" name="bedrooms" min="0" max="20"
                           value="{{ old('bedrooms', $property->bedrooms ?? 0) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bathrooms</label>
                    <input type="number" name="bathrooms" min="0" max="10"
                           value="{{ old('bathrooms', $property->bathrooms ?? 0) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Area (m²)</label>
                    <input type="number" name="area_sqm" min="0" step="0.01"
                           value="{{ old('area_sqm', $property->area_sqm ?? '') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary outline-none">
                </div>
            </div>

            {{-- Features checkboxes --}}
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Features</label>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                    @foreach(['Parking','Generator','Swimming Pool','Garden','Security Guard','CCTV','Water Tank','Solar Panel','Furnished','Air Conditioning','Elevator','Gym'] as $feature)
                    <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                        <input type="checkbox" name="features[]" value="{{ $feature }}"
                               class="rounded text-primary"
                               {{ in_array($feature, old('features', $property->features ?? [])) ? 'checked' : '' }}>
                        {{ $feature }}
                    </label>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ── Location ─────────────────────────────────────────── --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-semibold text-gray-900 mb-4">Location</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full address <span class="text-red-500">*</span></label>
                    <input type="text" name="address"
                           value="{{ old('address', $property->address ?? '') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary outline-none"
                           placeholder="e.g. KG 12 Ave, Kiyovu">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">District <span class="text-red-500">*</span></label>
                        <select name="district" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary outline-none">
                            <option value="">— select district —</option>
                            @foreach($districts as $province => $districtList)
                            <optgroup label="{{ $province }}">
                                @foreach($districtList as $d)
                                <option value="{{ $d }}" {{ old('district', $property->district ?? '') === $d ? 'selected' : '' }}>{{ $d }}</option>
                                @endforeach
                            </optgroup>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sector</label>
                        <input type="text" name="sector"
                               value="{{ old('sector', $property->sector ?? '') }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary outline-none"
                               placeholder="e.g. Kimihurura">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Latitude</label>
                        <input type="text" name="latitude"
                               value="{{ old('latitude', $property->latitude ?? '') }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary outline-none"
                               placeholder="-1.9441">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Longitude</label>
                        <input type="text" name="longitude"
                               value="{{ old('longitude', $property->longitude ?? '') }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary outline-none"
                               placeholder="30.0619">
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Images ───────────────────────────────────────────── --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-semibold text-gray-900 mb-1">Photos</h2>
            <p class="text-sm text-gray-500 mb-4">Upload up to 10 photos (JPEG/PNG/WebP, max 5MB each). First image becomes the cover.</p>

            {{-- Existing images (edit mode) --}}
            @isset($property)
            @if(!empty($property->images))
            <div class="grid grid-cols-4 gap-2 mb-4">
                @foreach($property->images as $img)
                <div class="relative group rounded-lg overflow-hidden aspect-square">
                    <img src="{{ Storage::url($img) }}" class="w-full h-full object-cover">
                    <label class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center cursor-pointer">
                        <input type="checkbox" name="delete_images[]" value="{{ $img }}" class="sr-only">
                        <span class="text-white text-xs font-medium">✕ Remove</span>
                    </label>
                </div>
                @endforeach
            </div>
            @endif
            @endisset

            <label class="flex flex-col items-center justify-center w-full h-36 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer hover:border-primary hover:bg-blue-50 transition">
                <span class="text-3xl mb-2">📸</span>
                <span class="text-sm font-medium text-gray-600">Click to upload photos</span>
                <span class="text-xs text-gray-400">or drag and drop</span>
                <input type="file" name="images[]" multiple accept="image/jpeg,image/png,image/webp" class="sr-only" id="imageInput">
            </label>
            <div id="imagePreview" class="grid grid-cols-4 gap-2 mt-3"></div>
        </div>

        {{-- Submit --}}
        <div class="flex items-center justify-between">
            <a href="{{ route('agent.properties.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                Cancel
            </a>
            <button type="submit"
                    class="bg-primary hover:bg-primary-hover text-white font-semibold px-8 py-3 rounded-xl transition text-sm">
                {{ isset($property) ? 'Save Changes' : 'Submit for Review' }} →
            </button>
        </div>

    </form>
</div>

@push('scripts')
<script>
    // Image preview
    document.getElementById('imageInput').addEventListener('change', function () {
        const preview = document.getElementById('imagePreview');
        preview.innerHTML = '';
        Array.from(this.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = e => {
                const div = document.createElement('div');
                div.className = 'aspect-square rounded-lg overflow-hidden';
                div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                preview.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    });

    // Show/hide rent period
    const typeSelect = document.querySelector('select[name="type"]');
    const periodRow  = document.getElementById('price-period-row');
    function togglePeriod() {
        periodRow.classList.toggle('hidden', typeSelect.value === 'sale');
    }
    typeSelect.addEventListener('change', togglePeriod);
    togglePeriod();
</script>
@endpush
@endsection
