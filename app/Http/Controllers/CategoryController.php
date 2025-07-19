<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return Inertia::render('admin/categories/index', [
            'categories' => CategoryResource::collection($categories),
        ]);
    }

    public function getCategories()
    {
        $categories = Category::all();
        return response()->json(CategoryResource::collection($categories));
    }

    public function getProductsByCategory($id){
        $products = Product::where('category_id', $id)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        return response()->json(ProductResource::collection($products));
    }

    public function getCategoriesWithoutIndex(){
        $categories = Category::where('url', '!=', '/')->get();
        return response()->json(CategoryResource::collection($categories));
    }

    public function show($id)
    {
        $category = Category::find($id);
        return response()->json(new CategoryResource($category),);
    }

    public function store(Request $request){
        $path = $request->file('image')?->store('categories', 'public');
        Category::create([
           'name' => $request->name,
           'url' => $request->url,
           'image' => $path
        ]);

        return response()->json([
            'message' => 'تم حفظ القسم بنجاح'
        ]);
    }

    public function update(Request $request)
    {
        $category = Category::find($request->id);
        $path = $category->image;
        if($request->hasFile('image')){
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }
            $path = $request->file('image')?->store('categories', 'public');
        }

        $category->update([
            'name' => $request->name,
            'url' => $request->url,
            'image' => $path
        ]);
        return response()->json([
            'message' => 'تم تعديل القسم بنجاح'
        ]);
    }

    public function delete($id)
    {
        $category = Category::find($id);
        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }
        $category->products()->delete();
        $category->delete();

        return response()->json([
            'message' => 'تم حذف القسم بنجاح'
        ]);
    }
}
