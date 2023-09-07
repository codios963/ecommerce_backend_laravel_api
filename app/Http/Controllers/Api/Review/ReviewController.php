<?php

namespace App\Http\Controllers\Api\Review;

use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        $review=Review::create([
            'commit'=>$request->commit,
            'Rating'=>$request->Rating,
            'product_id'=>$request->product_id,
            'user_id'=>Auth::id(),
        ]);
        $array=[
            new ReviewResource($review),
            
            'name_user' => $user = Auth::user()->name,
        ];
        return $this->successResponse($array, 'the review  Save');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function show(Review $review ,$prodect_id)
    {
        $products = Product::withCount('reviews')
        ->withAvg('reviews', 'rating')
        ->orderByDesc('reviews_count')
        ->orderByDesc('reviews_avg_rating')
        ->get();
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Review $review)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function destroy(Review $review)
    {
        //
    }
}
