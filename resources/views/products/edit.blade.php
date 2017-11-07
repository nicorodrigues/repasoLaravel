<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>
    <div class="container">
        <h1>Editando: {{$product->name}}</h1>
        <form class="col-md-5" action="/productos/{{$product->id}}" method="post">
            {{ csrf_field() }}
            {{ method_field('patch') }}
            <div class="form-group">
                <label for="name">Nombre</label>
                <input type="text" name="name" id="name" value="{{$product->name}}" class="form-control">
                @if ($errors->has('name'))
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->get('name') as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label for="cost">Costo</label>
                <input type="text" name="cost" id="cost" value="{{$product->cost}}" class="form-control">
                @if ($errors->has('cost'))
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->get('cost') as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label for="profit_margin">Margen de Ganancia</label>
                <input type="text" name="profit_margin" id="profit_margin" value="{{$product->profit_margin}}" class="form-control">
                @if ($errors->has('profit_margin'))
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->get('profit_margin') as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            <div class="form-group">
                <select name="category_id">
                    @foreach ($categories as $category)
                        @if ($category->id == $product->id)
                            <option value="{{$category->id}}" selected>{{$category->name}}</option>
                        @else
                            <option value="{{$category->id}}">{{$category->name}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                @foreach ($properties as $property)
                    <label for="property{{$property->id}}">{{$property->name}}</label>
                    @if (in_array($property->id, $product->properties()->pluck('id')->toArray()))
                    <input type="checkbox" name="properties[]" value="{{$property->id}}"
                    id="property{{$property->id}}" checked>
                    @else
                    <input type="checkbox" name="properties[]" value="{{$property->id}}" id="property{{$property->id}}">
                    @endif
                @endforeach
            </div>
            <div class="form-group">
                <button type="submit" name="button" class="btn btn-primary">Enviar</button>
            </div>
        </form>
    </div>
</body>
</html>
