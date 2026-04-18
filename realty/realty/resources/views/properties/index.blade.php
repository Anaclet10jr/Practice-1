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