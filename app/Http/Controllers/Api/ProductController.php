<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductCategoriesResource;
use App\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
 
class ProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // 

        //return response()->json(Product::paginate(10),200);
        $offset = $request->has('offset') ? $request->query('offset') : 0;
        $limit = $request->has('limit') ? $request->query('limit') : 10;

        $qb = Product::query()->with('categories');

        if($request->has('q'))
            $qb->where('name','like','%'.$request->query('q').'%');

        if($request->has('sortBy'))
            $qb->orderBy($request->query('sortBy'),$request->query('sort','DESC'));
        
        $data = $qb->offset($offset)->limit($limit)->get();
        $data = $data->makeHidden('slug');
        return response($data,200);
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
        $productObject = new Product;
        $productObject->name = $input["name"];
        $productObject->slug = Str::slug($input["name"]);
        $productObject->description = $input["description"];
        $productObject->price = $input["price"];
        $productObject->save();
        return response([
            'data' => $productObject,
            'message' => 'Product Created'
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //

        try{
            $product = Product::findOrFail($id);
            return $this->apiResponse(ResultType::Success,$product,'Product Not Fount',200);

        }catch(ModelNotFoundException $expection){
            return $this->apiResponse(ResultType::Error,null,'Product Not Fount',404);
        }
        return $product;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        // 
        $input = $request->all(); 
        $productObject = $product;
        $productObject->name = $input["name"];
        $productObject->slug = Str::slug($input["name"]);
        $productObject->description = $input["description"];
        $productObject->price = $input["price"];
        $productObject->save();

        return response([
            'data' => $productObject,
            'message' => 'Product Updated'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        // 
        $product->delete();
        return response([
            'data' => $product,
            'message' => 'Product deleted'
        ], 201);
    }

    public function custom1(){
        //return Product::select('id','name')->orderBy('created_at','desc')->take(10)->get();
        return Product::selectRaw('id as product_id ,name as product_name')->orderBy('created_at','desc')->take(10)->get();

    }

    public function custom2(){
        $product =  Product::select('id','name')->orderBy('created_at','desc')->take(10)->get();
        
        $mapped = $product->map(function($product){
            return [
                '_id' => $product["id"],
                'product_name' => $product["name"],
                'product_price' =>  $product["price"] * 1.03
            ];
        });

        return $mapped->all(); 
    }

    public function custom3(){
        $product = Product::paginate(10);
        return ProductResource::collection($product);
    }

    public function listWithCategories(){
        $product = Product::paginate(10);
        return ProductCategoriesResource::collection($product); 
    }
}
