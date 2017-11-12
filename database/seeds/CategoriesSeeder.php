<?php

use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {
        factory(\App\Category::class, 5)->create()->each(function ($elem) {
            $elem->products()->save(factory(App\Product::class)->make());
            $elem->products()->save(factory(App\Product::class)->make());
        });
    }
}
