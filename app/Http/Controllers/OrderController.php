<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::orderBy('created_at', 'desc')->get();
        return Inertia::render('admin/orders/index', [
            'orders' => OrderResource::collection($orders),
        ]);
    }

    public function getOrders()
    {
        $orders = Order::orderBy('created_at', 'desc')->get();
        return response()->json(OrderResource::collection($orders));
    }

    public function show($id)
    {
        $order = Order::find($id);
        return response()->json(new OrderResource($order));
    }

    public function store(OrderRequest $request)
    {
        // Check if products have enough quantity
        $products = $request->products;
        foreach ($products as $productData) {
            $product = Product::find($productData['id']);
            if (!$product) {
                return response()->json([
                    'message' => 'المنتج غير موجود'
                ], 400);
            }
        }

        // Subtract quantities from products
        foreach ($products as $productData) {
            $product = Product::find($productData['id']);
            $product->decrement('quantity', $productData['quantity']);
        }

        // Append product name to each product in the products array
        foreach ($products as &$productData) {
            $product = Product::find($productData['id']);
            if ($product) {
                $productData['name'] = $product->name;
            }
        }
        unset($productData);

        Order::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'products' => $products,
            'taxes' => $request->taxes,
            'total' => $request->total,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'تم حفظ الطلب بنجاح'
        ]);
    }

    public function update($id, OrderRequest $request)
    {
        $order = Order::find($id);
        $oldProducts = $order->products ?? [];
        $newProducts = $request->products;

        // Return quantities from old order
        foreach ($oldProducts as $productData) {
            if (isset($productData['id']) && isset($productData['quantity'])) {
                $product = Product::find($productData['id']);
                if ($product) {
                    $product->increment('quantity', $productData['quantity']);
                }
            }
        }

        // Check if new products have enough quantity
        foreach ($newProducts as $productData) {
            $product = Product::find($productData['id']);
            if (!$product) {
                return response()->json([
                    'message' => 'المنتج غير موجود'
                ], 400);
            }

            if ($product->quantity < $productData['quantity']) {
                return response()->json([
                    'message' => "الكمية المتوفرة للمنتج {$product->name} غير كافية. المتوفر: {$product->quantity}"
                ], 400);
            }
        }

        // Subtract quantities from new products
        foreach ($newProducts as $productData) {
            $product = Product::find($productData['id']);
            $product->decrement('quantity', $productData['quantity']);
        }

        // Append product name to each product in the products array
        foreach ($newProducts as &$productData) {
            $product = Product::find($productData['id']);
            if ($product) {
                $productData['name'] = $product->name;
            }
        }
        unset($productData);

        $order->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'products' => $newProducts,
            'taxes' => $request->taxes,
            'total' => $request->total,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'تم تعديل الطلب بنجاح'
        ]);
    }

    public function changeStatus($id, $status)
    {
        $order = Order::find($id);
        $order->update(['status' => $status]);
        return response()->json([
            'message' => 'تم تعديل حالة الطلب بنجاح'
        ]);
    }

    public function delete($id)
    {
        $order = Order::find($id);

        // Return quantities to products when order is deleted
        $products = $order->products ?? [];
        foreach ($products as $productData) {
            if (isset($productData['id']) && isset($productData['quantity'])) {
                $product = Product::find($productData['id']);
                if ($product) {
                    $product->increment('quantity', $productData['quantity']);
                }
            }
        }

        $order->delete();

        return response()->json([
            'message' => 'تم حذف الطلب بنجاح'
        ]);
    }

    public function testQuantityManagement()
    {
        $products = Product::all();
        $result = [];

        foreach ($products as $product) {
            $result[] = [
                'id' => $product->id,
                'name' => $product->name,
                'quantity' => $product->quantity,
            ];
        }

        return response()->json([
            'message' => 'اختبار إدارة الكميات',
            'products' => $result
        ]);
    }
}
