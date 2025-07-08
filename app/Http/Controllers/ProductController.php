<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->paginate(10);
        return Inertia::render('admin/products/index', [
            'products' => ProductResource::collection($products),
        ]);
    }

    public function getProducts()
    {
        $products = Product::with('category')->get();
        return ProductResource::collection($products);
    }

    public function show($id)
    {
        $product = Product::find($id);
        return response()->json(new ProductResource($product));
    }

    public function store(ProductRequest $request){
        $path = $request->file('image')?->store('products', 'public');
        $code = bin2hex(random_bytes(4));
        Product::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'sold' => $request->sold,
            'price' => $request->price,
            'code' => $code,
            'weight' => $request->weight,
            'quantity' => $request->quantity,
            'image' => $path
        ]);

        return response()->json([
            'message' => 'تم حفظ المنتج بنجاح'
        ]);
    }

    public function update($id, ProductRequest $request)
    {
        $product = Product::find($id);
        $path = $product->image;
        if($request->hasFile('image')){
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $path = $request->file('image')?->store('products', 'public');
        }
        $product->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'sold' => $request->sold,
            'price' => $request->price,
            'weight' => $request->weight,
            'quantity' => $request->quantity,
            'image' =>$path
        ]);

        return response()->json([
            'message' => 'تم تعديل المنتج بنجاح'
        ]);
    }

    public function delete($id)
    {
        $product = Product::find($id);
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return response()->json([
            'message' => 'تم حذف المنتج بنجاح'
        ]);
    }
}
