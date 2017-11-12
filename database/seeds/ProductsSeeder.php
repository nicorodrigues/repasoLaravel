<?php

use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
    * Run the database seeds.
    *
    * @return void
    */
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

            $elem->properties()->sync($properties->pluck('id'));
        });
    }
}
