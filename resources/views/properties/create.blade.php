<h1 class="text-2xl font-bold mb-4">Add Property</h1>

<form method="POST" action="/properties" class="space-y-3">
    @csrf

    <input name="title" placeholder="Title" class="border p-2 w-full">

    <textarea name="description" placeholder="Description" class="border p-2 w-full"></textarea>

    <input name="price" placeholder="Price" class="border p-2 w-full">

    <input name="type" placeholder="sell or rent" class="border p-2 w-full">

    <input name="address" placeholder="Address" class="border p-2 w-full">

    <input name="lat" placeholder="Latitude" class="border p-2 w-full">

    <input name="lng" placeholder="Longitude" class="border p-2 w-full">

    <button class="bg-green-500 text-white px-4 py-2 rounded">
        Save Property
    </button>
</form>