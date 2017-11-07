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
		<p>Propiedades:</p>
		<ul>
			@foreach ($properties as $property)
				<li>{{$property->name}}</li>
			@endforeach
		</ul>
    </body>
</html>
