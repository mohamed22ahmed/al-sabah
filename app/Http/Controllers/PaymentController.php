<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class PaymentController extends Controller
{
    public function showCheckout(Request $request)
    {
        $amount = $request->input('amount', 0);
        $orderData = $request->input('order_data');
        if ($orderData) {
            session(['pending_order_data' => $orderData]);
        }

        return response()->json([
            'publishable_key' => config('services.moyasar.publishable_key'),
            'amount' => $amount,
        ]);
    }

    public function callback(Request $request)
    {
        $paymentId = $request->input('id') ?? $request->query('id');
        if (!$paymentId) {
            return redirect()->route('payment.failed')->with('error', 'Payment ID missing.');
        }

        $secretKey = config('services.moyasar.secret_key');

        $response = Http::withBasicAuth($secretKey, '')
            ->get("https://api.moyasar.com/v1/payments/{$paymentId}");

        if ($response->successful() && $response->json('status') === 'paid') {
            $paymentMethod = $response->json('source.type');
            if ($paymentMethod !== 'creditcard') {
                return redirect()->route('payment.failed')->with('error', 'Payment must be made with card.');
            }

            // Retrieve order data from session
            $orderData = session('pending_order_data');

            if ($orderData) {
                $orderData = json_decode($orderData, true);

                if (!isset($orderData['products']) || !is_array($orderData['products'])) {
                    return redirect()->route('payment.failed')->with('error', 'Invalid order data: products missing.');
                }

                foreach ($orderData['products'] as $productData) {
                    $product = Product::find($productData['id']);
                    if (!$product) {
                        return redirect()->route('payment.failed')->with('error', 'Product not found.');
                    }
                }

                foreach ($orderData['products'] as $productData) {
                    $product = \App\Models\Product::find($productData['id']);
                    $product->decrement('quantity', $productData['quantity']);
                }

                // Append product name to each product in the products array
                foreach ($orderData['products'] as &$productData) {
                    $product = \App\Models\Product::find($productData['id']);
                    if ($product) {
                        $productData['name'] = $product->name;
                    }
                }
                unset($productData);

                $total = $orderData['subtotal'] + $orderData['delivery_cost'];

                Order::create([
                    'name' => $orderData['name'],
                    'phone' => $orderData['phone'],
                    'address' => $orderData['address'],
                    'products' => $orderData['products'],
                    'total' => $total,
                    'status' => 'paid',
                    'delivery_cost' => $orderData['delivery_cost'],
                    'zone' => $orderData['zone'],
                ]);

                session()->forget('pending_order_data');

                return redirect()->route('payment.success')->with('message', 'Payment successful.');
            }
        }

        return redirect()->route('payment.failed')->with('error', 'Payment failed.');
    }

    public function success()
    {
        return Inertia::render('payment/success', [
            'message' => session('message')
        ]);
    }

    public function failed()
    {
        return Inertia::render('payment/failed', [
            'error' => session('error')
        ]);
    }
}
