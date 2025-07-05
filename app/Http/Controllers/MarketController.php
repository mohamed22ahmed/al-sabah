<?php

namespace App\Http\Controllers;

use App\Models\Market;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class MarketController extends Controller
{
    public function index()
    {
        $markets = Market::all();
        return Inertia::render('admin/markets/index', [
            'markets' => $markets,
        ]);
    }

    public function show($id)
    {
        $market = Market::find($id);
        return response()->json($market);
    }

    public function store(Request $request){
        $path = $request->file('image')?->store('markets', 'public');
        Market::create([
            'image' => $path,
            'show' => $request->show
        ]);

        return response()->json([
            'message' => 'تم حفظ المعرض بنجاح'
        ]);
    }

    public function update($id, Request $request)
    {
        $market = Market::find($id);
        $path = $market->image;
        if($request->hasFile('image')){
            if ($market->image && Storage::disk('public')->exists($market->image)) {
                Storage::disk('public')->delete($market->image);
            }
            $path = $request->file('image')?->store('markets', 'public');
        }
        $market->update([
            'image' => $path,
            'show' => $request->show
        ]);
        return response()->json([
            'message' => 'تم تعديل المعرض بنجاح'
        ]);
    }

    public function delete($id)
    {
        $market = Market::find($id);
        if ($market->image && Storage::disk('public')->exists($market->image)) {
            Storage::disk('public')->delete($market->image);
        }
        $market->delete();

        return response()->json([
            'message' => 'تم حذف المعرض بنجاح'
        ]);
    }
}
