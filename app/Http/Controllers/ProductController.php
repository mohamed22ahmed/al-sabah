<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category', 'images')->paginate(10);
        return Inertia::render('admin/products/index', [
            'products' => ProductResource::collection($products),
        ]);
    }

    public function getProducts()
    {
        $products = Product::with('category', 'images')->get()->reverse();
        return ProductResource::collection($products);
    }

    public function show($id)
    {
        $product = Product::with('category', 'images')->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'المنتج غير موجود'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new ProductResource($product)
        ]);
    }

    public function store(ProductRequest $request){
        $path = $request->file('image')?->store('products', 'public');
        $code = bin2hex(random_bytes(4));
        $product = Product::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'sold' => $request->sold,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'code' => $code,
            'weight' => $request->weight,
            'quantity' => $request->quantity,
            'image' => $path
        ]);

        // Handle multiple images upload
        if ($request->hasFile('images')) {
            $images = $request->file('images');
            if (is_array($images)) {
                foreach ($images as $index => $image) {
                    try {
                        $imagePath = $image->store('products', 'public');
                        ProductImage::create([
                            'product_id' => $product->id,
                            'image_path' => $imagePath,
                            'is_primary' => $index === 0 // First image is primary
                        ]);
                    } catch (\Exception $e) {
                        return response()->json([
                            'message' => 'فشل رفع الصورة ' . ($index + 1),
                            'error' => $e->getMessage()
                        ], 500);
                    }
                }
            }
        }

        return response()->json([
            'message' => 'تم حفظ المنتج بنجاح'
        ]);
    }

    public function update($id, ProductRequest $request)
    {
        $product = Product::with('images')->find($id);
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
            'discount_price' => $request->discount_price,
            'weight' => $request->weight,
            'quantity' => $request->quantity,
            'image' =>$path
        ]);

        // Handle multiple images upload
        if ($request->hasFile('images')) {
            // Delete all existing images and add new ones
            foreach ($product->images as $existingImage) {
                if (Storage::disk('public')->exists($existingImage->image_path)) {
                    Storage::disk('public')->delete($existingImage->image_path);
                }
                $existingImage->delete();
            }

            // Add new images
            $images = $request->file('images');
            if (is_array($images)) {
                foreach ($images as $index => $image) {
                    try {
                        $imagePath = $image->store('products', 'public');
                        ProductImage::create([
                            'product_id' => $product->id,
                            'image_path' => $imagePath,
                            'is_primary' => $index === 0 // First image is primary
                        ]);
                    } catch (\Exception $e) {
                        \Log::error('Image upload failed: ' . $e->getMessage());
                        return response()->json([
                            'message' => 'فشل رفع الصورة ' . ($index + 1),
                            'error' => $e->getMessage()
                        ], 500);
                    }
                }
            }
        }

        return response()->json([
            'message' => 'تم تعديل المنتج بنجاح'
        ]);
    }

    public function delete($id)
    {
        $product = Product::with('images')->find($id);

        // Delete all product images
        foreach ($product->images as $productImage) {
            if (Storage::disk('public')->exists($productImage->image_path)) {
                Storage::disk('public')->delete($productImage->image_path);
            }
            $productImage->delete();
        }

        // Delete main image
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return response()->json([
            'message' => 'تم حذف المنتج بنجاح'
        ]);
    }
}
