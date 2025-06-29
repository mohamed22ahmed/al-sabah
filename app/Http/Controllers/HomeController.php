<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(){
        $links = Category::all();
        $this->activateLink($links, '/');
        return Inertia::render('Welcome', [
            'links' => $links,
            'logo' => asset(public_path().'/images/elsabah.png'),
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
            'logo' => asset('images/elsabah.png'),
        ]);
    }
}
