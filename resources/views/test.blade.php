<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <div id="productos">

    </div>

    <select name="product_id" id="product_id">
      @foreach ($products as $product)
        <option value="{{$product->id}}">{{$product->name}}</option>
      @endforeach
    </select>

    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="{{ asset('js/api.js') }}"></script>
  </body>
</html>
