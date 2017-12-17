# **Repaso de Laravel**

## **Creamos un nuevo proyecto**

Arranquemos poniendo en la terminal, en cualquier carpeta que querramos, el siguiente comando:
```bash
composer create-project laravel/laravel repaso
```

>Tengamos en cuenta que este comando, va a crear una carpeta dentro de la carpeta donde estemos trabajando actualmente. Por ejemplo, si estamos en la carpeta `php` y creamos el proyecto `repaso`, se va a crear la carpeta `repaso` dentro de `php` que es donde vamos a trabajar.

---
## **Descargamos la base de datos**
#### **Base de datos sin campo DNI**
http://repaso.nicorodrigues.com.ar/repaso.sql

#### **Base de datos con campo DNI**
http://repaso.nicorodrigues.com.ar/repasoConDni.sql

---
## **Configurar el proyecto de github (opcional)**
```bash
git clone https://github.com/nicorodrigues/repasoLaravel
cd repasoLaravel
composer install
cp .env.example .env
php artisan key:generate
```

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

En este caso estaríamos trabajando con la columna `category_id` de la tabla `products` que referencia a la columna `id` de `categories`. Es decir, cada categoría va a tener muchos productos, pero cada producto solamente va a tener 1 categoría, por eso los productos van a ser los que digan en sus datos, a qué categoría coresponden `category_id`.

Es decir: `products.category_id` --&rarr; `categories.id`

Para esto vamos a agregar las relaciones correspondientes en ambos modelos, utilizando `belongsToMany` (pertenece a muchos) y `hasMany` (tiene muchos):

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

## **Edición de datos**

Para la edición de datos, vamos a hacer como con la creación de datos y pasamos a crear 2 rutas nuevas:

```php
Route::get('/productos/{id}/edit', 'ProductsController@edit');
Route::patch('/productos/{id}', 'ProductsController@update');
```

Como vimos con la eliminación de datos, no hay mucha diferencia del proceso entre las diferentes relaciones.

Veamos como modificar datos en una relación de uno a muchos, arrancando por el método que nos va a devolver la vista con el formulario de edición:

```php
public function edit($id) {
    $product = \App\Product::find($id);
    $categories = \App\Category::all();
    $properties = \App\Property::all();

    $variables = [
        'product' => $product,
        'categories' => $categories,
        'properties' => $properties,
    ];

    return view('products.edit', $variables);
}
```
> Recordemos que nuestro método recibe por parámetro un `id` y usamos este `id` para recuperar de la base de datos el producto a modificar.

Teniendo el método creado, vamos a generar la vista necesaria para poder mostrar y modificar los datos de nuestro producto.
Tomemos de referencia nuestro formulario de creación pero lo alteramos un poco

> edit.blade.php

```php
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


```
> Como se ve, hay muchísimos cambios en el formulario de edición pero no son nada complicados. Lo que hacemos por ejemplo en la sección de categorías es dentro del `foreach` controlar cual es la categoría que tenemos seteada en el producto y la marcamos.
> En el caso de las propiedades, evaluamos cada `checkbox` para saber si está entre las propiedades del producto. De ser así, las imprimimos como `checked`.

Ya tenemos las rutas creadas y la vista con el formulario accesible, simplemente nos falta guardar los nuevos valores dentro del producto elegido.
Para esto, necesitamos solamente un método más que reciba los datos y los procese.

```php
public function update(Request $request, $id) {
    $product = \App\Product::find($id);
    $category = \App\Category::find($request->input('category_id'));

    $product->name = $request->input('name');
    $product->cost = $request->input('cost');
    $product->profit_margin = $request->input('profit_margin');
    $product->category()->associate($category);
    $product->save();

    $product->properties()->sync($request->input('properties'));

    return redirect('/productos/' . $id);
}
```
> Recibimos por parametros el `Request` y el `id` para poder traer los datos necesarios de la base de datos y actualizar todos y cada uno de los datos manualmente con los nuevos valores.

---
## **Autenticación (Auth)**
Ya podemos crear, ver, editar y eliminar productos... Pero de qué nos sirve todo esto sin usuarios?

Para solucionar este problema, tenemos la ayuda de Laravel, que como siempre, nos hace la vida fácil...

Si queremos crear un sistema de autenticación, lo único que tenemos que hacer es escribir en consola el siguiente comando:

```bash
php artisan make:auth
```
> Este comando nos crea las rutas, vistas y controladores necesarios para nuestro sistema de usuarios.

### **Rutas**
Empecemos viendo las rutas que nos regala Laravel:

```php
// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');
```

> Si bien en el web.php solo vamos a ver `Auth::Routes()`, esa linea sola contiene todas las rutas de arriba. Si quisieramos modificar alguna, simplemente la ponemos debajo de `Auth::Routes()` y va a pisar la correspondiente.
> Por ejemplo, si quisieramos pasar /register a /registro pondríamos lo siguiente:
> Route::get('registro', 'Auth\RegisterController@showRegistrationForm')->name('register');

### **Vistas**
Tenemos MUCHAS rutas preparadas, todas apuntando a un controlador. Esperemos un poco más antes de verlos, por ahora vayamos a las vistas.

En este momento tenemos 5 vistas nuevas creadas:

```php
auth\login.blade.php
auth\register.blade.php
auth\passwords\email.blade.php
auth\passwords\reset.blade.php
home.blade.php
```

Estas vistas están repartidas en diferentes carpetas.
- `auth` contiene las vistas de `login` / `register` / `carpeta passwords`.
- `passwords` que se encuentra dentro de `auth`, contiene las dos vistas de recuperación de contraseña que nos proveé Laravel (más adelante vemos como usarlas).
- `views` que es la carpeta principal de vistas, contiene la última vista, que es la pantalla que vemos una vez que estamos logueados.

No vamos a meternos muy adentro en las especificaciones de cada vista, pero veamos un resumen de cada una:

#### **- auth\login.blade.php**
Esta vista nos permite simplemente loguearnos a nuestro sitio web utilizando nuestro `email` y `password`.

#### **- auth\register.blade.php**
Esta vista nos permite registrarnos a nuestro sitio web, completando los campos `name`, `email`, `password` y `password_confirmation`.

#### **- auth\passwords\email.blade.php**
Esta es la vista que nos permite ingresar el correo para recuperar nuestro `password` via `email`.

#### **- auth\passwords\reset.blade.php**
A diferencia de la vista anterior, esta es la vista que nos permite realizar el cambio de contraseña después de acceder al link recibido en el mail que se envió desde `auth\email.blade.php`

#### **- home.blade.php**
La última vista, esta hecha para mostrar como podemos controlar las sesiones desde Laravel, es decir, solo podemos acceder a ella si estamos logueados. Si nos logueamos, nos redirige automáticamente a la vista.

Entiendo, entiendo, es un tema complicado... Vale la pena entender qué es lo que hace cada una de las vistas ya que nos sirven de ejemplo para modificarlas o incluso crear las nuestras propias.

### **Controllers**
Bien, ya tenemos una noción de qué es lo que hacen las vistas, vamos ahora por los controladores correspondientes:

Tenemos 4 controladores nuevos dentro de la carpeta `auth`:
```php
Auth\ForgotPasswordController.php
Auth\LoginController.php
Auth\RegisterController.php
Auth\ResetPasswordController.php
```
Son pocos, pero tenemos muchas explicaciones para hacer al respecto!
Arranquemos por explicar como se manejan estos controladores...

Si bien tenemos un controlador para cada una de las 4 mayores vistas nuevas, estos controladores funcionan a través del uso de `traits` (tal vez los recuerden de películas como: `Por dios, cuando se acaba objetos?` o `No entiendo nada, por favor ayuda.`), los cuales permiten que usen funciones declaradas dentro de la carpeta `vendor`, los cuales **NO TENEMOS QUE TOCAR** ya que son cosas que no se comparten entre los diferentes desarrolladores.

La forma correcta de trabajar con estos archivos es por ejemplo, si quisieramos modificar la validación del login, vamos a nuestro `Auth\LoginController.php` y vemos que dice `use AuthenticatesUsers`, lo que quiere decir que está utilizando el `trait` `AuthenticatesUsers`, con una simple busqueda (ctrl + p), vemos que ese trait está en `\vendor\laravel\framework\src\Illuminate\Foundation\Auth\AuthenticatesUsers.php`... COMO???

Ese es el problema con la carpeta vendor, todo nuestro sistema está ahí!! Por eso vamos a modificarlo lo menos posible.
En este archivo vemos que hay una función que se llama `validateLogin`, la cual recibe un `$request` y genera una validación...

Para modificarla, lo que vamos a hacer es tan simple como copiarla textualmente y pegarla en nuestro `Auth\LoginController.php`, debajo del `__construct` y modificamos lo que querramos. Por ejemplo:

```php
protected function validateLogin(Request $request)
{
    $this->validate($request, [
        $this->username() => 'required|string',
        'password' => 'required|string|min:6',
    ]);
}
```

Ahora nuestro `login` requiere que el password tenga al menos 6 caracteres!! Parecía complicado, no?
De la misma forma que logramos eso, podemos cambiar cualquier tipo de función en los controladores que nos dió el comando `php artisan make:auth`!!

Ahora, qué pasa por ejemplo si quisieramos modificar nuestro registro para agregar campos?

Tenemos un camino de 5 pasos:

Agreguemos primero el campo a la vista!

> auth\register.blade.php
```php
<div class="form-group{{ $errors->has('dni') ? ' has-error' : '' }}">
    <label for="dni" class="col-md-4 control-label">DNI</label>

    <div class="col-md-6">
        <input id="dni" type="dni" class="form-control" name="dni" required>

        @if ($errors->has('dni'))
            <span class="help-block">
                <strong>{{ $errors->first('dni') }}</strong>
            </span>
        @endif
    </div>
</div>
```

Tenemos el campo, vamos por el controlador!

En el controlador tenemos que modificar 2 métodos, agregandole lo necesario sobre nuestro campo:
> Auth\RegisterController.php
```php
protected function validator(array $data)
{
    return Validator::make($data, [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6|confirmed',
        'dni' => 'required|numeric|between:1,100000000'
    ]);
}
```

```php
protected function create(array $data)
{
    return User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => bcrypt($data['password']),
        'dni' => $data['dni']
    ]);
}
```

Con el controlador ya modificado, nos vamos para el modelo!

> User.php
```php
protected $fillable = [
    'name', 'email', 'password', 'dni'
];
```
> Hacemos esto para permitirle a la función create, modificar la columna `dni` de la base de datos.

Por último, y más importante, hay que agregarle la columna a la tabla de la base de datos!!
(como yo se que hay conocimiento que se sabe de memoria, obviamente, no voy a explicar esa parte).


---
## **Middlewares**

Uno de los conceptos más importantes en Laravel, es el de `middleware`.
Un `middleware` es una capa de abstracción más entre una ruta y un controlador.

Qué significa esto?

Pongamos como ejemplo la edición de un producto. Nosotros ya tenemos el sistema que permite la edición de un producto, pero que pasaría si quisieramos que una vez subido un producto, el único que tuviera acceso a la vista de edición fuese el administrador?
Ahí es donde entran los `middleware`. Estos nos dejan compartir funcionalidad entre las diferentes rutas, por ejemplo una barrera que se fija si sos el administrador o sos un usuario común; si sos un administrador, seguís de largo, si sos un usuario, volvés al home!

Para crear un middleware que controle el rol de nuestro usuario, ponemos el siguiente comando:
```bash
php artisan make:middleware CheckName
```
Esto nos va a generar un archivo en la carpeta `App\Http\Middleware` llamado `CheckName`. Este archivo ya viene con código, vamos a analizar el único método que tiene mientras le agregamos nuestro propio código:

```php
public function handle($request, Closure $next, $name)
{
    if (\Auth::check() && \Auth::user()->name === $name) {
        return $next($request);
    }

    return redirect('/');
}
```
> Nos preparamos el middleware agregandole un parámetro más, llamado `$name` y luego checkeamos a través de un `if`, si el usuario está logueado y si el nombre recibido es el mismo al que tiene el usuario. De ser así, va a correr la linea `return $next($request);`. Esta linea permite que el flujo siga, en cambio, si no fuera el nombre que esperamos, lo redirigimos a la ruta `'/'`.

Ya tenemos el código del middleware, ahora tenemos que registrarlo en el `Kernel`, esto nos va a permitir acceder al middleware desde las diferentes rutas.

> app\Http\kernel.php

En este archivo vamos a tener 3 tipos diferentes de formas de llamar a un `middleware`.
#### **- protected $middleware**
En este `array`, llamamos a los `middleware` que queremos que corran en TODAS las rutas, sin importar cual sea.

#### **- protected $middlewareGroups**
A diferencia del primer `array`, acá empezamos a especificarnos. Este es un `array de arrays asociativos`. La `key` que le pongamos al `array asociativo` va a ser el nombre por el cual vamos a utilizar los `middleware` contenidos.

#### **- protected $routeMiddleware**
Este es el `array` que vamos a utilizar. Acá definimos en la `key` la forma en la cual queremos llamar a nuestro `middleware` y en el `value` ponemos la ruta del mismo.

En nuestro caso, usamos la tercera forma, para ser más específicos agregando la siguiente linea al array `$routeMiddleware`:
```php
'checkname' => \App\Http\Middleware\CheckName::class,
```

Bien, ya tenemos la configuración hecha... Ahora, cómo los usamos?

Vamos a la ruta a la cual queremos ponerselo, en este caso queremos evitar que puedan editar los usuarios:

```php
Route::get('/productos/{id}/edit', 'ProductsController@edit')->middleware('checkname:admin');
```
> Podemos ver que le agregamos a la ruta el `middleware` utilizando `->middleware()` a la vez que le pasamos por parámetro `checkname:admin`. Qué es esto? `checkname` es el nombre que pusimos en el archivo `kernel.php` y al ponerle :admin le estamos pasando por parámetro la palabra `admin` que luego, se va a guardar en la variable `$name` dentro del `middleware`.

---
## **Migrations**
Ya vimos que Laravel nos hace la vida más fácil en todo sentido, por qué no agregar a la lista la creación de base de datos?
Las migraciones nos permiten escribir de forma ordenada, los cambios que vamos haciendo en la base de datos (creación de tablas, agregado de columnas, modificación de campos, etc). Para esto vamos a crear (idealmente) un archivo por cada cambio que fuesemos a hacerle a nuestra base de datos.

Creémos la base de datos que puse al principio de la guía desde 0:

Empezamos creando la base de datos (o schema) desde workbench/terminal con el nombre que querramos.

Después configuramos nuestro .env con la base de datos creada y vamos a crear la migración para la tabla de `products` corriendo el siguiente comando desde una terminal:
```bash
php artisan make:migration create_products_table
```
> Con ese comando estamos siguiendo la convención de `Laravel` sobre como crear migraciones de creación de tablas, poniendo de nombre `create_nombredetabla_table` ya nos genera las funciones necesarias para trabajar, que simplemente tenemos que modificar.

Dentro de toda migración vamos a encontrar 2 funciones:

#### **- up()**
En la función `up()` vamos a poner la estructura que queremos crear, siempre utilizando la clase `Schema`:

```php
Schema::create('products', function (Blueprint $table) {
    $table->increments('id');
    $table->string('name', 191);
    $table->string('image', 191)->nullable();
    $table->tinyInteger('active')->default(1);
    $table->double('cost', 8, 2);
    $table->double('profit_margin', 5, 2);
    $table->integer('category_id')->nullable()->unsigned();
    $table->timestamps();
});
```
> Como estamos CREANDO una tabla, utilizamos la función `create` que recibe como parametros el nombre de la tabla a crear y una función que a su vez recibe `Blueprint $table`, donde vamos a escribir nuestros campos de la tabla de la siguiente forma:
> `$table->tipodedato('nombredecolumna')`;
> Podemos concatenarle más modificadores, para ver una lista completa de todo, se puede usar el siguiente link: https://laravel.com/docs/5.5/migrations#columns


#### **- down()**
En la función `down()` nosotros vamos a decirle a Laravel como revertir el proceso hecho en `up()`, para que cuando quisieramos volver atrás una migración, lo podamos hacer de forma sencilla:

```php
Schema::dropIfExists('products');
```

> En este caso, lo que hacemos es eliminar la tabla `products` si es que existe.

De esa forma creamos las tablas restantes:

### **Categories**
```bash
php artisan make:migration create_categories_table
```
#### **- up()**
```php
Schema::create('categories', function (Blueprint $table) {
    $table->increments('id');
    $table->string('name', 191);
    $table->tinyInteger('active')->default(1);
    $table->timestamps();
});
```
#### **- down()**
```php
Schema::dropIfExists('categories');
```
### **Properties**
```bash
php artisan make:migration create_properties_table
```
#### **- up()**
```php
Schema::create('properties', function (Blueprint $table) {
    $table->increments('id');
    $table->string('name', 191);
    $table->tinyInteger('active')->default(1);
    $table->timestamps();
});
```
#### **- down()**
```php
Schema::dropIfExists('properties');
```

### **product_property**
Con la tabla `product_property` es un poco diferente, ya que vamos a estar creando una tabla de relaciones:

```bash
php artisan make:migration create_product_property_table
```

#### **- up()**
```php
Schema::create('product_property', function (Blueprint $table) {
    $table->integer('product_id')->nullable()->unsigned();
    $table->integer('property_id')->nullable()->unsigned();

    $table->foreign('product_id')->references('id')->on('products');
    $table->foreign('property_id')->references('id')->on('properties');
});
```
> Solo necesitamos dos campos, ya que vamos a usar esta tabla para relacionar productos con propiedades. Lo que hacemos además es crear las relaciones utilizando la siguiente lógica:
> `$table->foreign('nombredetablaensingular_id')->references('id')->on('nombredetabla');`
> Tenemos que asegurarnos que los dos campos `product_id` y `property_id` sean del tipo exacto que sea el campo de `id` original.


#### **- down()**
```php
Schema::dropIfExists('product_property');
```

Tenemos las tablas creadas... Pero hay un problema! Nos olvidamos de ponerle la relación al campo `category_id` de la tabla `products`. No hay que desesperar, podemos hacerlo con una migración más, para modificar la tabla!

```bash
php artisan make:migration add_relation_to_products_table
```
Si bien tenemos la migración creada, esta vez vamos a escribir el código un poco diferente:

#### **- up()**
```php
Schema::table('products', function (Blueprint $table) {
    $table->foreign('category_id')->references('id')->on('categories');
});
```
#### **- down()**
```php
Schema::table('products', function (Blueprint $table) {
    $table->dropForeign('posts_user_id_foreign');
});
```

Ahora sí tenemos toda la estructura de base de datos creada.
Ahora, solo a base de ejemplo, no voy a aplicar esta migración en el cóðigo, pero dejo una muestra de como sería una migración para modificar el largo de la columna name:

```bash
php artisan make:migration alter_name_column_products
```
#### **- up()**
```php
Schema::table('products', function (Blueprint $table) {
    $table->string('name', 160)->change();
});
```
#### **- down()**
```php
Schema::table('products', function (Blueprint $table) {
    $table->string('name', 191)->change();
});
```
> Lo que hacemos de esta forma es modificar la columna, con el mismo comando que la creariamos, pero agregandole `->change()` al final de la misma.
> Para la función `down()` hacemos lo contrario a lo que hicimos en `up()`.

## **Factories y Seeders**

Espectacular! Ya tenemos nuestras tablas de la base de datos creadas, ahora necesitamos rellenarlas con datos para poder probar nuestro sistema! Para esto vamos a usar `Seeders` junto a `Factories` y `Faker`. Qué es todo esto?

#### **- Seeders**
Los seeders son los encargados de decir qué vamos a agregar en la base de datos.

#### **- Factories**
Las factories se encargan de diseñar (en nuestro caso junto a `faker`) la estructura de un dato que vayamos a generar. Es decir, vamos a decirle a Laravel específicamente como tienen que ser los datos que vayan en la tabla.

#### **- Faker**
La mejor parte de las factories está en `Faker`. Faker es una librería que nos permite crear datos fictícios con casi cualquier tipo de formato que quisieramos, desde un número al azar hasta párrafos enteros.
> Para más info sobre qué cosas podemos crear con `Faker` ver el siguiente link: https://github.com/fzaninotto/Faker#formatters

### **Factory**
Arranquemos por `factory`, así le mostramos a Laravel cómo queremos que sean nuestros productos.

```bash
php artisan make:factory ProductFactory
```
Al correr ese comando vamos a tener un archivo nuevo, al cual vamos a ingresar para agregarle nuestros datos:

> database\factories\ProductFactory.php

```php
$factory->define(\App\Product::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'cost' => $faker->numberBetween(1, 2000),
        'profit_margin' => $faker->numberBetween(1, 100),
    ];
});
```
> Dentro del return, especificamos utilizando el formato `key => value` el nombre de la columna y el contenido que vamos a darle, aplicando `$faker` para generarlo.

Hacemos lo mismo con `Categories` y `Properties`:

#### **- Category**
```bash
php artisan make:factory CategoryFactory
```
> database\factories\CategoryFactory.php

```php
$factory->define(\App\Category::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
    ];
});
```

#### **- Property**

```bash
php artisan make:factory PropertyFactory
```
> database\factories\PropertyFactory.php

```php
$factory->define(\App\Property::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
    ];
});
```

Ya tenemos todos nuestras `Factory` creadas, pero nunca especificamos los datos de relaciones... Eso es porque eso lo hacemos en nuestros `Seeders`!!

Para los seeders, el camino es el mismo.

Generamos nuestro seeder:

```bash
php artisan make:seeder CategoriesSeeder
```

Si entramos al archivo creado, podemos ver que tenemos solamente una función, la cual vamos a modificar con nuestro código:
> database\seeds\CategoriesSeeder.php
```php
public function run()
{
    factory(\App\Category::class, 5)->create()->each(function ($elem) {
        $elem->products()->save(factory(App\Product::class)->make());
    });
}
```

Si quisieramos hacer más de uno por categoría:
```php
public function run()
{
    factory(\App\Category::class, 5)->create()->each(function ($elem) {
        $elem->products()->saveMany(factory(App\Product::class, 5)->make());
    });
}
```

> Arrancamos con un seeder un poco avanzado, para que se vaya viendo las posibilidades de lo que se puede hacer:
> `factory(\App\Category::class, 5)->create()` nos crea nuestra categoría utilizando la `factory` de `Category`
> A eso le hacemos le agregamos ->each() que recibe como parametro una función y nos permite correr esa función a cada uno de las categorías creadas.
> Por último le agregamos a cada uno de nuestros productos, la categoría creada utilizando la siguiente linea:
> `$elem->products()->save(factory(App\Product::class)->make());` donde $elem es la categoría actual, de ahí llamamos a los productos de esa categoría y le decimos que a esa categoría le agregue un elemento creado dentro del método save utilizando `factory(App\Product::class)->make()`
> Si nosotros quisieramos crear y agregar más de un solo producto por categoría, bastaría con realizar un `for` dentro de la función que recibe `each` o simplemente repetir la linea `$elem->products()->save(factory(App\Product::class)->make());` más de una vez.

Ya sabemos como crear y seedear una base de datos en Laravel, vamos a realizar los pasos restantes!!

```bash
php artisan make:seeder PropertiesSeeder
```
> database\seeds\PropertiesSeeder.php

```php
public function run()
{
    factory(\App\Property::class, 10)->create();
}
```

Y por último:

```bash
php artisan make:seeder ProductsSeeder
```
> database\seeds\ProductsSeeder.php

Para nuestra último `Seeder` vamos a correr un código un tanto complicado:

```php
public function run()
{
    factory(\App\Product::class, 10)->create()->each(function ($elem) {
        $properties = [];
        $maxCat = \App\Category::all()->count();
        $maxProp = \App\Property::all()->count();

        for ($i=0; $i < 3; $i++) {
            $properties[] = \App\Property::find(rand(1,$maxProp));
        }

        $properties = collect($properties);

        $elem->category()->associate(\App\Category::find(rand(1, $maxCat)));
        $elem->save();

        $elem->properties()->sync($properties);
    });
}
```
> La lógica de arriba es la siguiente:
> Creamos nuestro producto y con un `each()` le corremos a cada uno, una función.

> Dentro de esta función hacemos varias cosas:
> - Creamos un `array` dondo vamos a guardar nuestras propiedades
> - Contamos la cantidad de categorías que tenemos y las guardamos en `$maxCat`
> - Contamos la cantidad de propiedades que tenemos y las guardamos en `$maxProp`
> - Corremos un `for` que nos permite cargar a nuestro array de propiedades, 3 propiedades existentes en la base de datos.
> - Transformamos nuestro array en una collection
> - Al elemento actual, le cargamos utilizando `associate()` una categoría existente en la base de datos y lo guardamos
> - Por último, al elemento actual, le sincronizamos todos los tags que agarramos de la base de datos, pero solamente le pasamos sus `id` utilizando la función `pluck()` de eloquent.

Una vez terminado esto, solamente nos falta decirle a Laravel que use estos seeders al momento de llenar la base de datos! Para esto, vamos al archivo `database\seeds\DatabaseSeeder.php` y le agregamos las siguientes lineas en su función `run()`:
```php
$this->call(PropertiesSeeder::class);
$this->call(CategoriesSeeder::class);
$this->call(ProductsSeeder::class);
```

Ahora que tenemos nuestros seeders con sus factories y las migraciones creadas, solamente nos falta correrlos!

Vamos a la terminal y escribimos lo siguiente:
```bash
php artisan migrate --seed
```
> Esto nos corre todas las migraciones y además, una vez terminada la migración, corre nuestros seeders.
Si quisieramos hacer todo por separado, haríamos lo siguiente:

```bash
php artisan migrate
php artisan db:seed
```

### **Notas finales sobre migración**
En el caso de que tuvieramos problemas con alguna migración, o simplemente quisieramos volver atrás, podemos utilizar el siguiente comando

```bash
php artisan migrate:rollback --step 2
```
> Este comando nos permite volver atrás nuestras últimas 2 migraciones. Es decir, vamos a ejecutar la funcion `down()` de las últimas 2 migraciones realizadas.

Si por otra parte, quisieramos resetear completamente nuestra base de datos, haríamos lo siguiente:
```bash
php artisan migrate:refresh
```

En la nueva versión de Laravel, tenemos un comando que nos permite **borrar** todas nuestras tablas y correr las migraciones de nuevo, sin utilizar las funciones `down()`:
```bash
php artisan migrate:fresh
```
Por último, si nosotros quisieramos seedear nuestra base de datos mientras corremos cualquier comando de `migrate`, lo único que deberíamos hacer es agregarle `--seed` al final.

---
## **Storage**
Upa, ya tenemos todo armado... Ah, no... NOS FALTAN LAS FOTOS!
Para empezar, si quisieramos guardar cosas en Laravel, lo recomendable es correr el comando que vamos a ver a continuacion para generar un `soft link`. Qué es esto? Simple, nos genera un acceso directo a otra carpeta. De esta forma acceder a archivos que físicamente pueden o no estar en nuestra carpeta public, como si lo estuvieran.

Corremos el siguiente comando en la terminal:
```bash
php artisan storage:link
```

Si van a la carpeta `public`, ahora tenemos una nueva carpeta llamada `storage`, la cual realmente es un acceso directo a la verdadera carpeta `storage/app/public`.

Propongamonos ahora guardar una foto en nuestros productos. Para esto, necesitaríamos otra columna en nuestra tabla de productos... Creémos una migración!!

```bash
php artisan make:migration add_fotopath_to_products_table
```
Le agregamos funcionalidad a la migración:


#### **- Up()**
```php
Schema::table('products', function (Blueprint $table) {
    $table->string('fotopath')->nullable();
});
```

#### **- Down()**
```php
Schema::table('products', function (Blueprint $table) {
    $table->dropColumn('fotopath');
});
```

Como último paso, corremos la migración poniendo en consola el siguiente comando!

```bash
php artisan migrate
```

Ya con los pasos hechos hasta ahora tenemos la base de datos preparada para que nuestros productos tengan imágenes... Lo que nos falta es subirlas!!!

Arranquemos agreguemosle a nuestra vista de creación de productos lo necesario para que se pueda subir una imagen junto a los datos:

Arranquemos por modificar el `<form>` para que efectivamente envíe archivos:
```html
<form class="col-md-5" action="/productos/agregar" method="post" enctype="multipart/form-data">
```
> Al agregarle `enctype="multipart/form-data"` le estamos diciendo a nuestro formulario, que aparte de enviar texto plano, va a estar enviando archivos.

Ya tenemos el formulario preparado, vamos a agregarle el campo de selección de imagen:

```php
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
```

Perfecto! Ya tenemos el sistema preparado para enviar imagenes... Ahora a recibirlas!

Arrancamos adaptando nuestro `fillable` para que podamos guardar el path en nuestra tabla:

> app/Product.php
```php
protected $fillable = ['name', 'cost', 'profit_margin', 'category_id', 'fotopath'];
```

Modifiquemos el método `store` de nuestro `ProductsController`.

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

    $extensionImagen = $request->file('fotoPath')->getClientOriginalExtension();

    $fotoPath = $request->file('fotoPath')->storeAs('productos', uniqid() . "." . $extensionImagen, 'public');

    $product = \App\Product::create([
        'name' => $request->input('name'),
        'cost' => $request->input('cost'),
        'profit_margin' => $request->input('profit_margin'),
        'fotopath' => $fotoPath
    ]);

    $category = \App\Category::find($request->input('category_id'));

    $product->properties()->sync($request->input('properties'));
    $product->category()->associate($category);
    $product->save();

    return redirect('/productos');
}
```
> Ya que nuestro producto va a tener imagen de forma opcional, no le hacemos una validación. Simplemente le agregamos las siguientes lineas:
>
> `$extensionImagen = $request->file('fotoPath')->getClientOriginalExtension();`
> `$fotoPath = $request->file('fotoPath')->storeAs('productos', $request->name . "." . $extensionImagen, 'public');`
>
> En la primera linea guardamos la extensión de la imagen subida para utilizarla luego en la función `storeAs`.
> Para guardar nuestro archivo, primero creamos una variable donde finalmente vamos a tener el `path` llmada `$fotoPath`. Empezamos por agarrar nuestro archivo `$request->file('fotoPath')`, a esto le concatenamos la función `storeAs('productos', $request->name . "." . $extensionImagen, 'public');`
> A la función le agregamos parametros en este orden:
> `'productos'` es la carpeta donde se va a guardar nuestra imagen
> `$request->id . "." . $extensionImagen` es el nombre con el cual vamos a guardar nuestro archivo, en este caso le pusimos la funcion `uniqid()` que nos genera un `string` único y su extensión original, con un . en el medio para que mantenga su formato de archivo.
> `'public'` es el nombre del lugar donde va a estar guardado nuestro, generalmente se pone 'public'

Ya podemos guardar fotos en nuestros productos! Pero ahora... cómo las vemos?

Vamos a la vista donde podemos ver productos específicos y le agregamos la siguiente linea donde querramos:

> products/show.blade.php
```php
<img src="{{ asset('storage/' . $product->fotopath) }}" alt="">
```
> Utilizando la función `asset()` generamos una URL hacia nuestra imagen. Le pasamos como parámetro la ubicación de nuestro archivo, en este caso lo tenemos en nuestra base de datos: `$product->fotopath`. El problema está en que ese no es el path que utilizamos para recuperarla, sino que tenemos que agregarle `'storage/'` antes, para que podamos acceder correctamente.

De esta forma ya tenemos nuestro sistema de imagenes totalmente funcional!!!

Continuará...

┻━┻︵  \(°□°)/ ︵ ┻━┻
