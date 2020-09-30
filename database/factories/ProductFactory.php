<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product; 
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Product::class, function (Faker $faker) {
    $product_name = $faker->sentence(3);
    return [
            'name' => $product_name,
            'slug' => Str::slug($product_name),
            'description' => $faker->paragraph(3),
            'price' => mt_rand(10,100)/10,
        ];
});
