@extends("layouts.app")

@section("content")
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-4">{{ $product->name }}</h1>
    <div class="bg-white rounded-lg shadow-md p-6">
        <p><strong>Description:</strong> {{ $product->description }}</p>
        <p><strong>Prix:</strong> {{ $product->base_price }}€</p>
        <p><strong>Catégorie:</strong> {{ $product->category }}</p>
        @if($product->material)
            <p><strong>Matériaux:</strong> {{ $product->material }}</p>
        @endif
    </div>
    <a href="{{ route("products.index") }}" class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Retour</a>
</div>
@endsection
