<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $product = Product::latest()->paginate(5);
        return response()->json([
            'code' => 200,
            'message' => 'Get data success',
            'data' => $product,
        ]);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'price' => 'required',
            'stock' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                // 'message'=> $validator->errors()->first(),
                $validator->errors()
            ], 422);
        }

        $image = $request->file('image');
        $image->storeAs('public/product', $image->hashName());

        $product = Product::create([
            'name' => $request->name,
            'image' => $image->hashName(),
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description,
        ]);

        return response()->json([
            'code' => 200,
            'message' => 'Login successfully',
            'data' => $product,
        ], 200);

    }

    public function show($id){
        $product = Product::find($id);
        if($product != null) {
            return response()->json([
                'code' => 200,
                'message'=> 'Success get data',
                'data'=> $product,
            ], 200);
        } else {
            return response()->json([
                'code' => 422,
                'message'=> 'Product ID not found'
            ], 422);
        }
    }

    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'price' => 'required',
            'stock' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                $validator->errors()
            ], 422);
        }

        $product = Product::find($id);
        if($product == null) {
            return response()->json([
                'code' => 422,
                'message'=> 'Data not found',
            ], 422);
        } 

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->storeAs('public/product', $image->hashName());
            
            Storage::delete('public/product/'.basename($product->image));

            $product->update([
                'name' => $request->name,
                'image' => $image->hashName(),
                'price' => $request->price,
                'stock' => $request->stock,
                'description' => $request->description,
            ]);
        } else {
            $product->update([
                'name' => $request->name,
                'price' => $request->price,
                'stock' => $request->stock,
                'description' => $request->description,
            ]);
        }

        return response()->json([
            'code' => 200,
            'message'=> 'Success update data',
            'data' => $product,
        ], 200);
    }

    public function destroy($id) {
        $product = Product::find($id);
        if($product == null) {
            return response()->json([
                'code' => 422,
                'message'=> 'Data not found',
            ], 422);
        } 

        Storage::delete('public/product/'.basename($product->image));
        $product->delete();
        return response()->json([
            'code'=> 200,
            'message'=> 'Success delete data',
        ]);
    }
}
