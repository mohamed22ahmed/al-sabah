<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(){
        $links = Category::all();
        $this->activateLink($links, '/');
        return Inertia::render('index', [
            'links' => $links
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
        return Inertia::render('Show', [
            'links' => $links,
            'slug' => $url,
        ]);
    }

    public function aboutUs() {
        $links = Category::all();
        return Inertia::render('home/about_us', [
            'links' => $links
        ]);
    }

    public function privacy() {
        $links = Category::all();
        return Inertia::render('home/privacy', [
            'links' => $links
        ]);
    }

    public function terms() {
        $links = Category::all();
        return Inertia::render('home/terms', [
            'links' => $links
        ]);
    }
}
