<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = \App\Product::all();

        // $variable = [
        //   "nombre" => "Nicolas",
        //   "apellido" => "Rodrigues"
        // ];

        return response()->json($products);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $categories = \App\Category::all();
      $properties = \App\Property::all();

      $variables = [
          "categories" => $categories,
          "properties" => $properties,
      ];

      return view('products.testCreate', $variables);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(\App\Product $product)
    {
      return response()->json($product);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
