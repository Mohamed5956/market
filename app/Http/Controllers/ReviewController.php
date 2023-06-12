<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function list_review($prd_id)
    {
        $reviews = Review::where('product_id', $prd_id)->get();
        if (count($reviews) <= 0) {
            return response()->json(['data' => $reviews, 'message' => 'No Data Found.'], 200);
        }else{
            return response()->json([ 'data' => $reviews ], 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @throws ValidationException
     */
    public function store_review(StoreReviewRequest $request)
    {
            $existingReview = Review::where('user_id', Auth::id())->first();
            if ($existingReview) {
                throw ValidationException::withMessages([
                    'user_id' => 'You have already submitted a review.',
                ]);
            }

            $review['user_id'] = Auth::id();
            $review['product_id'] = (int)$request->product_id;

            if ($request->comment == null){
                $review['comment'] = "";
            }else{
                $review['comment'] = $request->comment;
            }

            if ($request->rating == null){
                $review['rating'] = 0;
            }else{
                $review['rating'] = $request->rating;
            }

            $new_review = Review::create($review);
            $user_review = Review::with('user', 'product')->get();
            $review_collection = ReviewResource::collection($user_review);
            $data = $this->list_review((int)$request->product_id);
            return response()->json(['message'=>'Review add successfully', 'new_review'=>$review_collection,  'data'=>$data]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update_review(UpdateReviewRequest $request)
    {
            $product_id = (int)$request->route('id');
            $review_to_update = Review::where('user_id', Auth::id())->where('product_id', $product_id)->first();

            if ($review_to_update){
                $review_to_update['comment'] = $request->comment;
                $review_to_update['rating'] = $request->rating;

                $review_to_update->update();
                    return response()->json(["data" => $this->list_review($product_id), 'message'=>'Review updated successfully'], 200);
            }else{
                return response()->json(['error' => 'No review for this user'], 500);
            }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function delete_review(string $id)
    {
        $review_to_delete = Review::where('user_id', Auth::id())->where('product_id', (int)$id)->first()->delete();
        return response()->json(['data' => $this->list_review((int)$id), 'message' => 'Item deleted'], 200);
    }
}
