<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Http\Requests\BannerRequest;
use App\Http\Resources\BannerResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::all();
        return Inertia::render('admin/banners/index', [
            'banners' => BannerResource::collection($banners),
        ]);
    }

    public function getBanners()
    {
        $banners = Banner::all();
        return BannerResource::collection($banners);
    }

    public function show($id)
    {
        $banner = Banner::find($id);
        return response()->json(new BannerResource($banner));
    }

    public function store(BannerRequest $request){
        $path = $request->file('image')?->store('banners', 'public');
        Banner::create([
            'show' => $request->show,
            'image' => $path
        ]);

        return response()->json([
            'message' => 'تم حفظ البانر بنجاح'
        ]);
    }

    public function update($id, BannerRequest $request)
    {
        $banner = Banner::find($id);
        $path = $banner->image;
        if($request->hasFile('image')){
            if ($banner->image && Storage::disk('public')->exists($banner->image)) {
                Storage::disk('public')->delete($banner->image);
            }
            $path = $request->file('image')?->store('banners', 'public');
        }
        $banner->update([
            'image' => $path,
            'show' => $request->show
        ]);
        return response()->json([
            'message' => 'تم تعديل البانر بنجاح'
        ]);
    }

    public function delete($id)
    {
        $banner = Banner::find($id);
        if ($banner->image && Storage::disk('public')->exists($banner->image)) {
            Storage::disk('public')->delete($banner->image);
        }
        $banner->delete();

        return response()->json([
            'message' => 'تم حذف البانر بنجاح'
        ]);
    }
}
