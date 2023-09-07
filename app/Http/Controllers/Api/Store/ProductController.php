<?php

namespace App\Http\Controllers\Api\Store;


use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Product = ProductResource::collection(Product::all());
        return $this->apiResponse($Product);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest  $request)
    {
        $imagePath = null;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('public/images');
        }
        $Product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'image'=>$imagePath,
            'price' => $request->price,
            'sub_category_id' => $request->sub_category_id,
            'user_id' => Auth::id(),

        ]);
        $array = [
            new ProductResource($Product),
            'name_user' => $user = Auth::user()->name,
        ];
        if ($Product) {
            return $this->successResponse($array, 'the Product  Save');
        }
        return $this->errorResponse('the Product Not Save');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $Product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Product = Product::with('reviews')->find($id);
        
      
        if ($Product) {
            $reviews = [];
            foreach ($Product->reviews as $review) {
                $reviews[] = $review->commit;
                $reviews[] = $review->Rating;
                $reviews[] = $review ->user()->name;
            }
            $array = [
                "Product" =>   new ProductResource($Product),
                "reviews" => $reviews
            ];
       
            return $this->successResponse($array, 'ok');
        }
        return $this->errorResponse('the Product Not Found');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $Product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $id)
    {
        $Product = Product::find($id);
        
        if (!$Product) {
            return $this->errorResponse('the Product Not Found', 404);
        }

        if ($Product->user_id === Auth::id()) {
            $imagePath = null;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('public/images');
        }
            $Product->update([
                'name' => $request->name,
                'description' => $request->description,
                'image'=>$imagePath,
                'price' => $request->price,
                'sub_category_id' => $request->sub_category_id,

                'user_id' => Auth::id(),

            ]);
            $array = [
                new ProductResource($Product),
                'name_user' => $user = Auth::user()->name,
            ];
            if ($Product) {
                return $this->successResponse($array, 'the Product update');
            }
        }
        return $this->errorResponse('you con not updet the Product Because you are not authorized', 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $Product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $Product = Product::find($id);

        if ($Product->user_id === Auth::id()) {
            $Product->delete();
            if ($Product) {
                return $this->successResponse(null, 'the Product deleted');
            }
            return $this->errorResponse('you con not delete the Product', 400);
        }
        return $this->errorResponse('you con not delete the Product Because you are not authorized', 401);
    }
    public function showsoft()
    {
        $Products = Product::onlyTrashed()->get();
        return $this->apiResponse($Products);
    }
    public function restor($id)
    {
        $Product = Product::withTrashed()->where('id', $id)->restore();
        return $this->successResponse($Product, 'the Product restor');
    }
    public function finldelet($id)
    {
        $Product = Product::withTrashed()->where('id', $id)->forceDelete();
        if ($Product) {
            return $this->successResponse(null, 'the Product deleted');
        }
        return $this->errorResponse('you con not delete the Product', 400);
    }
    public function subcategoryProducts($id)
    {
        $subcategory = SubCategory::find($id);
        if(!$subcategory){
            return $this->errorResponse('Subcategory Not Found.', 404);
        }
        $products = $subcategory->products()->get();
        return $this->successResponse($products, 'Show Products.', 200);
    }
    public function search(Request $request,$subcat){
        $scat=SubCategory::findOrFail($subcat);
        $product=Product::where('subcat_id',$scat->id)->where('name','like','%'.$request->name.'%')->get();

        return $this->apiResponse($product,'About this product',200);

    }

    public function FilterPrice(Request $request){
        $product=Product::where('price','>',$request->min)->where('price','<',$request->max)->get();
        return $this->apiResponse($product,'Filter About Price',200);
    }
}
