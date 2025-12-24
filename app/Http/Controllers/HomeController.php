<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Product;
use Inertia\Inertia;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        $links = Category::all();
        $this->activateLink($links, '/');

        return Inertia::render('index', [
            'links' => CategoryResource::collection($links),
        ]);
    }

    public function getBestOffers(){
        $bestOffers = Product::with('category')
            ->whereNotNull('discount_price')
            ->where('quantity', '>', 0)
            ->orderByRaw('(price - discount_price) DESC')
            ->limit(3)
            ->get();

        return response()->json([
            'success' => true,
            'data' => ProductResource::collection($bestOffers)
        ]);
    }

    private function activateLink(&$links, $url){
        $links->filter(function($category) use ($url){
            $category->active = false;
            if($category->url == $url)
                $category->active = true;
        });
    }

    public function show($url){
        $links = Category::all();
        $this->activateLink($links, '/'.$url);

        $category = Category::where('url', ('/'.$url))->first();

        if (!$category) {
            abort(404);
        }

        $products = Product::where('category_id', $category->id)->get();

        return Inertia::render('Show', [
            'links' => CategoryResource::collection($links),
            'slug' => $url,
            'category' => new CategoryResource($category),
            'products' => ProductResource::collection($products),
        ]);
    }

    public function aboutUs() {
        $links = Category::all();
        return Inertia::render('home/about_us', [
            'links' => CategoryResource::collection($links)
        ]);
    }

    public function privacy() {
        $links = Category::all();
        return Inertia::render('home/privacy', [
            'links' => CategoryResource::collection($links)
        ]);
    }

    public function terms() {
        $links = Category::all();
        return Inertia::render('home/terms', [
            'links' => CategoryResource::collection($links)
        ]);
    }

    public function returns() {
        $links = Category::all();
        return Inertia::render('home/returns', [
            'links' => CategoryResource::collection($links)
        ]);
    }

    public function complains() {
        $links = Category::all();
        return Inertia::render('home/complains', [
            'links' => CategoryResource::collection($links)
        ]);
    }

    public function cart() {
        $links = Category::all();
        return Inertia::render('Cart', [
            'links' => CategoryResource::collection($links)
        ]);
    }

    public function getCategoryProducts($id, Request $request) {
        $limit = $request->get('limit', 10);

        $products = Product::where('category_id', $id)
            ->where('quantity', '>', 0)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => ProductResource::collection($products)
        ]);
    }

    public function searchProducts(Request $request) {
        $query = $request->get('q', '');

        if (empty($query)) {
            return response()->json([
                'success' => false,
                'message' => 'يرجى إدخال كلمة البحث'
            ]);
        }

        $products = Product::with('category')
            ->where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%")
                  ->orWhere('code', 'LIKE', "%{$query}%");
            })
            ->where('quantity', '>', 0)
            ->limit(5)
            ->get();

        $results = $products->map(function($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'code' => $product->code,
                'category' => [
                    'id' => $product->category->id,
                    'name' => $product->category->name,
                    'url' => $product->category->url
                ]
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $results
        ]);
    }

    public function product($id) {
        $links = Category::all();
        $this->activateLink($links, '/');
        $product = Product::with('category')->find($id);

        if (!$product) {
            abort(404);
        }

        return Inertia::render('product', [
            'links' => CategoryResource::collection($links),
            'product' => new ProductResource($product),
        ]);
    }
}
