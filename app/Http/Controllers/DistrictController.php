<?php

namespace App\Http\Controllers;

use App\Http\Resources\DistrictResource;
use App\Http\Resources\ZoneResource;
use App\Models\District;
use App\Models\Zone;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DistrictController extends Controller

{
    public function index()
    {
        $districts = District::with('zone')->paginate(10);
        return Inertia::render('admin/districts/index', [
            'districts' => DistrictResource::collection($districts),
        ]);
    }

    public function getDistricts()
    {
        $districts = District::with('zone')->get()->reverse();
        return DistrictResource::collection($districts);
    }

    public function store(Request $request)
    {
        District::create([
            'zone_id' => $request->zone_id,
            'name'    => $request->name,
            'name_ar' => $request->name_ar,
            'price'   => $request->price
        ]);

        return response()->json([
            'message' => 'تم حفظ الحي بنجاح'
        ]);
    }

    public function update($id, Request $request)
    {
        $district = District::find($id);
        $district->update([
            'zone_id' => $request->zone_id,
            'name'    => $request->name,
            'name_ar' => $request->name_ar,
            'price'   => $request->price
        ]);

        return response()->json([
            'message' => 'تم تعديل الحي بنجاح'
        ]);
    }

    public function delete($id)
    {
        $district = District::find($id);
        $district->delete();

        return response()->json([
            'message' => 'تم حذف الحي بنجاح'
        ]);
    }
}
