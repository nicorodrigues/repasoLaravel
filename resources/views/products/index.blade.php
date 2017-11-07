<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title></title>
        <link rel="stylesheet" href="/css/app.css">
    </head>
    <body>
    <ul>
        @foreach ($products as $product)
			<li><a href="/productos/{{$product->id}}">{{$product->name}}</a></li>
		@endforeach
    </ul>
    </body>
</html>
