# **Repaso de Laravel**

## **Creamos un nuevo proyecto**

Arranquemos poniendo en la terminal, en cualquier carpeta que querramos, el siguiente comando:
```bash
composer create-project laravel/laravel repaso
```

>Tengamos en cuenta que este comando, va a crear una carpeta dentro de la carpeta donde estemos trabajando actualmente. Por ejemplo, si estamos en la carpeta `php` y creamos el proyecto `repaso`, se va a crear la carpeta `repaso` dentro de `php` que es donde vamos a trabajar.

---
## **Descargamos la base de datos**
http://repaso.nicorodrigues.com.ar/repaso.sql

---
## **Configurar el proyecto de github (opcional)**
> git clone https://github.com/nicorodrigues/repasoLaravel
> cd repasoLaravel
> composer install
> cp .env.example .env
> php artisan key:generate

---
## **Configuramos el proyecto**
Intentamos levantar el servidor para ver si no hubo problemas

```bash
cd repaso
php artisan serve
```

Si no se puede entrar por alguna razón, lo más probable es que haya habido un error en la creación del .env, por eso cambiamos el .env.example a .env y generamos la key

```bash
cp .env.example .env
php artisan key:generate
```

Intentamos levantar el servidor de nuevo

```bash
php artisan serve
```

Si entramos a la dirección que nos muestra la consola, podemos ver la vista por defecto de laravel:

> http://127.0.0.1:8000   - o su equivalente -   http://localhost:8000

> Si quisieramos que laravel se levante en otro puerto, podemos especificarselo cuando lo levantamos
> `php artisan serve --port 8080`


Si sale todo bien, cerramos nuestro servidor (vamos a la consola y apretamos ctrl+c)
Usando el archivo del campus, importamos la base de datos en el workbench.

Configuramos nuestro archivo .env con los datos de la base de datos:

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=repaso
    DB_USERNAME=root
    DB_PASSWORD=

> La contraseña es la misma que usamos para entrar al workbench (generalmente no tiene password o es root)

## **Rutas - Vistas - Controladores**
Volvemos a levantar el servidor sabiendo que todo está en orden...
Borramos nuestra ruta default en el archivo web.php y arrancamos de 0 con una ruta que use get:

```php
Route::get('/', 'HomeController@index');
```

Creamos el controlador necesario:
```bash
php artisan make:controller HomeController
```
Dentro del controlador, generamos el método index, el cual declaramos que ibamos a usar en la ruta '/':
```php
public function index() {
	return view('index');
}
```
Teniendo el sistema ruta-controlador armado, nos falta la vista!
La creamos a mano con el formato 'nombreDeVista.blade.php' en la carpeta resources/views:

> index.blade.php
```html
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title></title>
<style media="screen">
.contenedor {
display: flex;
width: 500px;
}

.rawr {
width: 50%;
margin-top: 110px;
}

.dino {
width: 50%;
}
</style>
</head>
<body>
<div class="contenedor">

<div class="rawr">
<marquee>RAWR RAWRRRR</marquee>
<marquee>RAWR RAWRRRR</marquee>
</div>
<div class="dino">
<pre>
        .-=-==--==--.
  ..-=="  ,'o`)      `.
,'         `"'         \
:  (                     `.__...._
|                  )    /         `-=-.
:       ,vv.-._   /    /               `---==-._
\/\/\/VV ^ d88`;'    /                         `.
   ``  ^/d88P!'    /             ,              `._
       ^/    !'   ,.      ,      /                  "-,,__,,--'""""-.
      ^/    !'  ,'  \ . .(      (         _           )  ) ) ) ))_,-.\
     ^(__ ,!',"'   ;:+.:%:a.     \:.. . ,'          )  )  ) ) ,"'    '
     ',,,'','     /o:::":%:%a.    \:.:.:         .    )  ) _,'
      """'       ;':::'' `+%%%a._  \%:%|         ;.). _,-""
             ,-='_.-'      ``:%::)  )%:|        /:._,"
            (/(/"           ," ,'_,'%%%:       (_,'
                           (  (//(`.___;        \
                            \     \    `         `
                             `.    `.   `.        :
                               \. . .\    : . . . :
                                \. . .:    `.. . .:
                                 `..:.:\     \:...\
                                  ;:.:.;      ::...:
                                  ):%::       :::::;
                              __,::%:(        :::::
                           ,;:%%%%%%%:        ;:%::
                             ;,--""-.`\  ,=--':%:%:\
                            /"       "| /-".:%%%%%%%\
                                            ;,-"'`)%%)
                                           /"      "|
</pre>
</div>
</div>

</body>
</html>
```

---
## **Modelos**

Como sabemos que vamos a trabajar con una base de datos de productos, empezamos por armar los modelos correspondientes necesarios...

```php
php artisan make:model Product    -> Esto correspondería a la tabla products
php artisan make:model Category   -> Esto correspondería a la tabla categories
php artisan make:model Property   -> Esto correspondería a la tabla properties
```

> Ojo, siempre tener en cuenta que el modelo por convención se escribe con la primera letra mayúscula y en singular.
Mostramos los productos de la base de datos siguiendo el sistema ruta-controlador-vista:

```php
Route::get('/productos', 'ProductsController@index');
```

Creamos el controlador:

```php
php artisan make:controller ProductsController
```

Creamos el método para la ruta:

```php
public function index() {
	$products = \App\Product::all();

	$variables = [
		"products" => $products,
	];

	return view('products.index', $variables);
}
```

Imprimimos los productos en la vista!

>index.blade.php
``` php
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
			<li>{{$product->name}}</li>
		@endforeach
    </ul>
    </body>
</html>

```

> Este foreach nos muestra el producto, de ahí podemos sacar los datos que querramos.
> En este caso $producto es una collection (lo cual es un array potenciado, de la mano de eloquent) y nos permite acceder a sus propiedades de la misma forma que accedemos a atributos de un objeto.


Si nosotros quisieramos ver un solo producto, armaríamos el sistema de forma parecida.

Arrancamos con la ruta:
```php
Route::get('/productos/{id}', 'ProductsController@show');
```
>Utilizamos `{id}` para tomar como parámetro el ID necesario y enviarselo a la función show, la cual nos va a devolver la vista con el producto elegido. En el caso de no encontrar un producto, nos devuelve `False`

Aprovechando el hecho de que queremos ser específicos con lo que vamos a mostrar, le agregamos al modelo `Product` una función que nos permita saber el precio total de un producto (`cost` + `profit_margin`)

```php
public function getPrice() {
	return $this->cost + ($this->cost * $this->profit_margin / 100);
}
```

> El `profit_margin` es un porcentaje de `cost` que se agrega al costo base para generar el precio final.

Seguimos con el método necesario:

```php
public function show($id) {
	$product = \App\Product::find($id);

	$variables = [
		"product" => $product,
	];

	return view('products.show', $variables);
}
```

Y por último, como corresponde, armamos la vista!!
>show.blade.php
```php
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
		<p>{{$product->profit_margin}}</p>
		<p>{{$product->getPrice()}}</p>
    </body>
</html>
```

Ya que tenemos una forma de listar los productos y otra forma que nos permite ver un producto concreto, vamos a permitir que cada uno de los productos de nuestra lista, nos lleve a su descripción.
Para esto, lo único que tenemos que hacer es modificar la vista del listado:

>index.blade.php
``` php
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

```

Ahora... hace falta agregar productos!
Para esto vamos a hacer el recorrido de nuevo, pero esta vez necesitamos 2 rutas! get y post

```php
Route::get('/productos/agregar', 'ProductsController@create');
Route::post('/productos/agregar', 'ProductsController@store');
```
>Tengamos en cuenta que al poner estas rutas y teniendo la ruta anterior `/productos/{id}`, si entramos a `/productos/agregar` vamos a estar pasando la palabra `agregar` como `{id}`. Para solucionar esto, ambas rutas las ponemos ANTES de la ruta `/productos/{id}`.


La primera ruta nos va a permitir ir al formulario que vamos a usar para guardar los productos!

```php
public function create() {
	return view('products.create');
}
```

Creamos el formulario!
>create.blade.php
```html
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
	<link rel="stylesheet" href="/css/app.css">
</head>
<body>
	<div class="container">
		<h1>Agregar Productos</h1>
		<form class="col-md-5" action="/productos/agregar" method="post">
			{{ csrf_field() }}
			<div class="form-group">
				<label for="name">Nombre</label>
				<input type="text" name="name" id="name" value="" class="form-control">
			</div>
			<div class="form-group">
				<label for="cost">Precio</label>
				<input type="text" name="cost" id="cost" value="" class="form-control">
			</div>
			<div class="form-group">
				<label for="profit_margin">Ganancias</label>
				<input type="text" name="profit_margin" id="profit_margin" value="" class="form-control">
			</div>
            <div class="form-group">
				<label for="category_id">Categoría</label>
				<input type="text" name="category_id" id="category_id" value="" class="form-control">
			</div>
			<div class="form-group">
				<button type="submit" name="button" class="btn btn-primary">Enviar</button>
			</div>
		</form>
	</div>
</body>
</html>
```

> Nunca olvidarse, cuando se crea un formulario de agregar el `{{ csrf_field() }}` para que Laravel nos permita recibir los datos pasando su validación de seguridad.


La segunda ruta nos va a validar y guardar los datos!

```php
public function store(Request $request) {
    $rules = [
        "name" => "required|unique:products",
        "cost" => "required|numeric",
        "profit_margin" => "required|numeric",
        "category_id" => "required|numeric|between:1,3"
    ];

    $messages = [
        "required" => "El :attribute es requerido!",
        "unique" => "El :attribute tiene que ser único!",
        "numeric" => "El :attribute tiene que ser numérico!",
        "between" => "El :attribute tiene que estar entre :min y :max!"
    ];

    $request->validate($rules, $messages);

    $producto = \App\Product::create([
        'name' => $request->input('name'),
        'cost' => $request->input('cost'),
        'profit_margin' => $request->input('profit_margin'),
        'category_id' => $request->input('category_id')
    ]);

    return redirect('/productos');
}
```

Si tenemos un error en nuestra validación, Laravel se encarga de volvernos al formulario y entregarnos los errores en una variable llamada casualmente `$errors`, lo que nos queda es encargarnos de mostrar sus contenidos! Para esto, vamos a modificar el formulario que teníamos:

>agregar.blade.php
```html
<form class="col-md-5" action="/productos/agregar" method="post">
	{{ csrf_field() }}
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
		<label for="profit_margin">Ganancias</label>
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
                <label for="category_id">Categoría (1-3):</label>
                <input type="text" name="category_id" id="category_id" value="{{old('category_id')}}" class="form-control">
                @if ($errors->has('category_id'))
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->get('category_id') as $error)
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
```

> Hay que recordar que para que la función "create" funcione, necesitamos declarar alguna de las siguientes variables en el modelo elegido:
> ```php
> protected $fillable = [ "columnasPermitidas" ];
> protected $guarded = [ " columnasProhibidas" ];
> ```

Ya tenemos la posibilidad de guardar datos en la base de datos...

Ahora, qué pasa si queremos agregarle categorías a nuestros productos...?

---
## **Relaciones**
Nosotros ya trajimos cosas de la base de datos, pero todavía no jugamos con las categorías o las propiedades.

### **Uno a muchos**
En el caso de las categorías, vamos a estar teniendo una relación de "uno a muchos".

En este caso estaríamos trabajando con la columna `category_id` de la tabla `products` que referencia a la columna `id` de `categories`.

Es decir: `products.category_id` --&rarr; `categories.id`

Para esto vamos a agregar las relaciones correspondientes en ambos modelos:

#### Product
Al producto le agregamos su relación con la categoría, en su caso sería con `belongsTo`
```php
public function category() {
	return $this->belongsTo('\App\Category', 'category_id', 'id');
}
```
>El orden de parametros es:
>
      1. Modelo de los datos que se van a traer
      2. Foreign Key de ese modelo apuntando al actual
      3. Primary Key del modelo actual

#### Category
A la categoría le agregamos su relación con los productos, en su caso sería con `hasMany`
```php
public function products() {
	return $this->hasMany('\App\Product', 'category_id', 'id');
}
```
>El orden de parametros es:
>
      1. Modelo de los datos que se van a traer
      2. Foreign Key de ese modelo apuntando al actual
      3. Primary Key del modelo actual

---
#### **Accediendo a datos de relaciones**
Una vez seteados ambas funciones, ya podemos llamar a sus productos/categoria como si fuera un atributo más.

En el caso que quisieramos obtener la categoría de un producto lo haríamos de la siguiente forma:

```php
$product->category
```

En el caso de querer obtener los productos de una categoría sería de la misma forma:
```php
$category->products
```

Vamos a modificar nuestro sistema para que podamos ver la categoría de un producto, por lo cual, vamos a empezar por editar el controlador de productos:

```php
public function show($id) {
	$product = \App\Product::find($id);

	$variables = [
		"product" => $product,
		"category" => $product->category
	];

	return view('products.show', $variables);
}
```

Una vez modificado el controlador, pasamos a modificar la vista para mostrar la categoría:

>show.blade.php
```html
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>{{$product->name}}</title>
    </head>
    <body>
        <h1>{{$product->name}}</h1>
		<p>{{$product->cost}}</p>
		<p>{{$product->getPrice()}}</p>
		<p>{{$category->name}}</p>
    </body>
</html>
```

Fiuuu... Ya terminamos con las categorías!!
El tema es que todavía nos quedan propiedades por aplicarles a los productos...
Si vemos nuestra base de datos, las propiedades se encuentran en su propia tabla, separada de los productos y categorias. El problema está en que ni la tabla de productos ni la tabla de propiedades tienen Foreign Key relacionadolas.

Para esto, tenemos una tabla llamada product_property que contiene 2 columnas:

 - product_id
 - property_id

En este caso vamos a necesitar especificarle a Laravel que lo que tenemos es una relación de muchos a muchos, ya que muchas propiedades pueden relacionarse con muchos productos y muchos productos pueden relacionarse con muchas propiedades.

Arranquemos por decirle al modelo de productos que va a tener muchas propiedades agregandole la siguiente función:
```php
public function properties() {
	return $this->belongsToMany('\App\Property', 'product_property', 'product_id', 'property_id');
}
```
>El orden de parametros es:
>
      1. Modelo de los datos que se van a traer
      2. Tabla que une a ambos modelos
      3. Foreign Key del modelo actual
      4. Foreign Key del otro modelo

De la misma forma que le implementamos esa función al modelo de Productos, le implementamos una parecida al modelo Propierty!

```php
public function products() {
	return $this->belongsToMany('\App\Product', 'product_property', 'property_id', 'product_id');
}
```
>El orden de parametros es:
>
      1. Modelo de los datos que se van a traer
      2. Tabla que une a ambos modelos
      3. Foreign Key del modelo actual
      4. Foreign Key del otro modelo

Ya tenemos las relaciones de muchos a muchos creadas! Solo nos queda editar nuestro sistema para poder trabajar con las mismas.

Arrancamos como siempre por el método:

```php
public function show($id) {
	$product = \App\Product::find($id);

	$variables = [
		"product" => $product,
		"category" => $product->category,
		"properties" => $product->properties
	];

	return view('products.show', $variables);
}
```

Nos vamos para la vista y hacemos un foreach ya que tenemos que estar preparados para más de una propiedad:

>show.blade.php
```html
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
```

Ya podemos traer datos de la base de datos usando 3 tablas...
Y si queremos agregar datos...?

---
### **Añadir datos con relaciones**

Puede que al principio parezca complicado, o incluso asuste, pero añadir datos utilizando más de una tabla, no es nada difícil.

---
#### **Uno a Muchos**
Cuando tenemos una relación como la de `Product` y `Category` en la cual una categoría puede tener más de un producto, nos referimos a una relación de Uno a Muchos, para la cual vamos a usar la función llamada `associate()` la cual nos permite asociar un producto a una categoría.

Empezamos modificando la función que devuelve la vista del formulario para que además le lleve las categorías, de esta forma vamos a obligar a que seleccionen de nuestra lista!


```php
public function create() {
	$categories = \App\Category::all();
	$variables = [
		"categories" => $categories,
	];

	return view('products.create', $variables);
}
```

Una vez modificada la función, modificamos la vista para que efectivamente tengamos un campo para seleccionar la categoría:
>create.blade.php
```html
<form class="col-md-5" action="/productos/agregar" method="post">
	{{ csrf_field() }}
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
        <label for="profit_margin">Margen de Ganancias</label>
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
		<button type="submit" name="button" class="btn btn-primary">Enviar</button>
	</div>
</form>
```

Modificamos el método para guardar productos con su correspondiente categoría usando los métodos provistos por Laravel:

```php
public function store(Request $request) {
	$rules = [
		"name" => "required|unique:products",
		"cost" => "required|numeric",
		"profit_margin" => "required|numeric",
        "category_id" => "required|numeric|between:1,3"
	];

	$messages = [
		"required" => "El :attribute es requerido!",
		"unique" => "El :attribute tiene que ser único!",
		"numeric" => "El :attribute tiene que ser numérico!",
        "between" => "El :attribute tiene que estar entre :min y :max."
	];

    $request->validate($rules, $messages);

	$product = \App\Product::create([
		'name' => $request->input('name'),
		'cost' => $request->input('cost'),
		'profit_margin' => $request->input('profit_margin'),
	]);

	$category = \App\Category::find($request->input('category_id'));

	$product->category()->associate($category);
	$product->save();

	return redirect('/productos');
}
```

> `associate()`nos pide como parametro un objeto del tipo que querramos asociar a nuestro producto, en este caso le pasamos un objeto del tipo `category`.

Parece mucho trabajo, no?      (╯°□°）╯︵ ┻━┻

Si comparamos todo lo que hicimos hasta ahora con lo que sabemos php plano, nos damos cuenta que Laravel nos permite hacer todo de forma mucho más sencilla.


---
#### **Muchos a Muchos**
Ahora vamos con algo más interesante pero igual de fácil. Cuando manejemos 3 tablas, Laravel nos provee de la función `sync()`.

Como venimos haciendo, arrancamos modificando el método que devuelve la vista:

```php
public function create() {
	$categories = \App\Category::all();
	$properties = \App\Property::all();

	$variables = [
		"categories" => $categories,
		"properties" => $properties,
	];

	return view('products.create', $variables);
}
```

Seguimos nuestro camino, esta vez hacia la vista!

>create.blade.php
```html
<form class="col-md-5" action="/productos/agregar" method="post">
    {{ csrf_field() }}
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
        <button type="submit" name="button" class="btn btn-primary">Enviar</button>
    </div>
</form>
```
> Haciendo un `foreach` de `$properties`, podemos crear una lista de `checkbox` que nos permita elegir las propiedades que queremos en nuestro producto.

Con el formulario ya preparado, vamos al método que recibe los datos para decirle que además nos guarde las propiedades del producto!

```php
public function store(Request $request) {
	$input = $request->except('_token');
	$rules = [
		"name" => "required|unique:products",
		"cost" => "required|numeric",
		"profit_margin" => "required|numeric"
	];

	$messages = [
		"required" => "El :attribute es requerido!",
		"unique" => "El :attribute tiene que ser único!",
		"numeric" => "El :attribute tiene que ser numérico!"
	];


	$validator = Validator::make($input, $rules, $messages);

	$product = \App\Product::create([
		'name' => $request->input('name'),
		'cost' => $request->input('cost'),
		'profit_margin' => $request->input('profit_margin')
	]);

	$category = \App\Category::find($request->input('category'));

	$product->properties()->sync($request->input('properties'));
	$product->category()->associate($category);
	$product->save();

	return redirect('/productos');
}
```

> Para sincronizar nuestras propiedades, seleccionamos al producto y le aclaramos que queremos utilizar la función `sync()` asociada a nuestra función `properties()`. A su vez, le pasamos como parámetro a `sync()`, un array con los `id` de cada una de las properties que queremos agregarle.

>Como nosotros utilizamos un `foreach` para generar los `checkbox`, nos aseguramos que cada uno tenga en su `name` el string `"properties[]"`, lo que nos devuelve un array que contiene los `id` de cada una de las seleccionadas.

De esta forma ya tenemos nuestro sistema completo. Podemos agregar y ver productos con sus categorías y propiedades correspondientes!

## **Eliminación de datos**
Ahora que tenemos la posibilidad de crear y ver productos. Qué pasa si queremos eliminarlos?

### **Uno a Muchos**
Ambos sistemas de relaciones se manejan de formas diferentes, las relaciones de unos a muchos son relativamente fácil de eliminar.

Como siempre, empezamos por crear la ruta!

```php
Route::delete('/productos/{id}', 'ProductsController@destroy');
```

> Hay algo que llama la atención, tenemos un `delete` como método en la ruta. Esto es uno de los nuevos métodos de envío que vamos a usar en Laravel.
> Si bien `delete` sigue siendo `post`, Laravel sabe distinguirlo de un `post` común.

Sigamos por crear el controlador:

```php
public function destroy($id) {
    $product = \App\Product::find($id);

    $product->delete();

    return redirect('/productos');
}
```

Para poder usar el método `delete`, vamos a modificar nuestra ruta `show` de forma que nos permita borrar un producto que querramos.

```html
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
    <form action="/productos/{{$product->id}}" method="post">
        {{ csrf_field() }}
        {{ method_field('delete') }}
        <button type="submit">Borrar</button>
    </form>
</body>
</html>
```

> Como se ve, para trabajar con métodos fuera de `get` y `post`, tenemos que mandar un formulario a través de `post` y agregarle, primero el `csrf_field` como siempre y además una nueva función llamada `method_field` al cual le pasamos como parámetro el verdadero método que queremos utilizar.

### **Muchos a Muchos**
Ahora... tenemos un problema...
Si nosotros eliminamos un producto que tiene propiedades, estas propiedades no se eliminan.
Qué podemos hacer para modificar esto?

Simplemente vamos a la función `destroy` y agregamos una linea:

```php
public function destroy($id) {
    $product = \App\Product::find($id);

    $product->properties()->sync([]);
    $product->delete();

    return redirect('/productos');
}
```
> Simplemente agregando el `sync()` con un array vacío, Laravel se encarga de eliminar las propiedades correspondientes.

Continuará...

┻━┻︵  \(°□°)/ ︵ ┻━┻
