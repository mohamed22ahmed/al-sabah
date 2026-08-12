<?php

namespace App\Http\Controllers;

use App\Http\Resources\ZoneResource;
use App\Models\Zone;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ZoneController extends Controller
{
    public function index()
    {
        $zones = Zone::with('districts')->paginate(10);
        return Inertia::render('admin/zones/index', [
            'zones' => ZoneResource::collection($zones),
        ]);
    }

    public function getZones()
    {
        $zones = Zone::query()->get()->reverse();
        return ZoneResource::collection($zones);
    }

    public function store(Request $request)
    {
        Zone::create([
            'name' => $request->name,
            'name_ar' => $request->name_ar
        ]);

        return response()->json([
            'message' => 'تم حفظ المنطقة بنجاح'
        ]);
    }

    public function update($id, Request $request)
    {
        $zone = Zone::find($id);
        $zone->update([
            'name'    => $request->name,
            'name_ar' => $request->name_ar
        ]);

        return response()->json([
            'message' => 'تم تعديل المنطقة بنجاح'
        ]);
    }

    public function delete($id)
    {
        $zone = Zone::find($id);
        $zone->delete();

        return response()->json([
            'message' => 'تم حذف المنطقة بنجاح'
        ]);
    }
}
