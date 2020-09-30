<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 
        return $this->apiResponse(ResultType::Success,Category::all(), 'Categoris Fethced', 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $input = $request->all();
        $categoryObject = new Category;
        $categoryObject->name = $input["name"];
        $categoryObject->slug = Str::slug($input["name"]);
        $categoryObject->save();
        return $this->apiResponse(ResultType::Success,$categoryObject, 'Categorie Created', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
        return $this->apiResponse(ResultType::Success,$category, 'Categorie Created', 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        // 
        $input = $request->all();
        $categoryObject = $category;
        $categoryObject->name = $input["name"];
        $categoryObject->slug = Str::slug($input["name"]);
        $categoryObject->save();
        return $this->apiResponse(ResultType::Success,$categoryObject, 'Category Updated', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return $this->apiResponse(ResultType::Success,null, 'Category Deleted', 200);
    }

    public function custom1()
    {
        return Category::pluck('id', 'name');
    }

    public function report1()
    {
        return DB::table('product_categories as pc')
            ->selectRaw('c.name , COUNT(*) as total')
            ->join('categories as c', 'c.id', '=', 'pc.category_id')
            ->join('products as p', 'p.id', '=', 'pc.product_id')
            ->groupBy('c.name')
            ->OrderByRaw('COUNT(*) DESC')
            ->get();
    }
}
