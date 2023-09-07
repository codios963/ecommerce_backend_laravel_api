<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
     public function addOrder(OrderRequest $request){
        $data=$request->validated();
        $totalPrice = 0;
        foreach ($data['products'] as $productData) {
            $product = Product::findOrFail($productData['product_id']);
            $totalPrice += $productData['quantity'] * $product->price;
        }
        $order=Order::create([
            'user_id'=>auth()->user()->id,
            'totalprice'=>$totalPrice
        ]);
        foreach($data['products'] as $productData){
            $product=Product::findOrFail($productData['product_id']);
            $order->products()->attach($product, [
                'quantity' => $productData['quantity'],
                'price' => $product->price,
            ]);
        }

        return $this->apiResponse('Order Added Successfully');


    }
    public function index()
    {
        //
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
