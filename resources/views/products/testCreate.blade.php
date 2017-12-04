<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" href="/css/app.css">
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="container">
        <h1>Agregar Productos</h1>
        @if (isset($errors))
            {{$errors}}
        @endif
        <form class="col-md-5" action="/productos/agregar" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Nombre</label>
                <input type="text" name="name" id="name" value="{{old('name')}}" class="form-control">
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
                <input type="text" name="cost" id="cost" value="{{old('cost')}}" class="form-control">
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
                <input type="text" name="profit_margin" id="profit_margin" value="{{old('profit_margin')}}" class="form-control">
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
                        <option value="{{$category->id}}">{{$category->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                @foreach ($properties as $property)
                    <label for="property{{$property->id}}">{{$property->name}}</label>
                    <input type="checkbox" name="properties[]" value="{{$property->id}}"
                    id="property{{$property->id}}">
                @endforeach
            </div>
            <div class="form-group">
                <label for="fotoPath">Imagen</label>
                <input type="file" name="fotoPath" id="fotoPath" class="form-control">
                @if ($errors->has('fotoPath'))
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->get('fotoPath') as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            <div class="form-group">
                <button type="submit" name="button" class="btn btn-primary">Enviar</button>
            </div>
        </form>
    </div>
      <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="{{asset('js/api2.js')}}"></script>
</body>
</html>
