<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{$product->name}}</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>
    <h1>{{$product->name}}</h1>
    <p>{{$product->cost}}</p>
    <p>{{$product->getPrice()}}</p>
    <p>{{$category->name}}</p>
    <img src="{{ asset('storage/' . $product->fotopath) }}" alt="">
    <p>Propiedades:</p>
    <ul>
        @foreach ($properties as $property)
            <li>{{$property->name}}</li>
        @endforeach
    </ul>
    <form action="/productos/{{$product->id}}" method="post">
        {{ csrf_field() }}
        {{ method_field('delete') }}
        <button type="submit">Borrar</button>
    </form>
</body>
</html>
