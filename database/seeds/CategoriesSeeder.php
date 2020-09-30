<?php

use App\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker\Generator $faker)
    {
        //
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Category::truncate();

        for ($i = 0; $i < 30; $i++) {
            $category_name = rtrim($faker->sentence(1),'.');

            Category::create([
                'name' => $category_name,
                'slug' => Str::slug($category_name)
            ]);
        } 

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
       
    }
}
