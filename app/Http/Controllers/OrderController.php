<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::all();
        return Inertia::render('admin/orders/index', [
            'orders' => $orders,
        ]);
    }

    public function show($id)
    {
        $order = Order::find($id);
        return response()->json($order);
    }

    public function store(Request $request){
        Order::create($request->all());
        return response()->json([
            'message' => 'تم حفظ الطلب بنجاح'
        ]);
    }

    public function update($id, Request $request)
    {
        Order::find($id)->update($request->all());
        return response()->json([
            'message' => 'تم تعديل حالة الطلب بنجاح'
        ]);
    }

    public function changeStatus($id, $status)
    {
        Order::find($id)->update(['status' => $status]);
        return response()->json([
            'message' => 'تم تعديل حالة الطلب بنجاح'
        ]);
    }

    public function delete($id)
    {
        Order::find($id)->delete();
        return response()->json([
            'message' => 'تم حذف الطلب بنجاح'
        ]);
    }
}
