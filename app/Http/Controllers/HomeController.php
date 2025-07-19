<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Product;
use Inertia\Inertia;

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

        // Find the category by URL
        $category = Category::where('url', ('/'.$url))->first();

        if (!$category) {
            abort(404);
        }

        // Get products for this category
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

    public function cart() {
        $links = Category::all();
        return Inertia::render('Cart', [
            'links' => CategoryResource::collection($links)
        ]);
    }
}
