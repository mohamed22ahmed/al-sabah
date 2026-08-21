<?php
namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class PaymentController extends Controller
{
    public function showCheckout(Request $request)
    {
        $amount = $request->input('amount', 1000) * 100;

        return response()->json([
            'publishable_key' => config('services.moyasar.publishable_key'),
            'amount' => $amount,
        ]);
    }

    public function callback(Request $request)
    {
        $paymentId = $request->input('id') ?? $request->query('id');
        $orderId = $request->input('order_id') ?? $request->query('order_id');

        if (!$paymentId) {
            Log::error('Payment ID missing from callback');
            return redirect()->route('payment.failed')->with('error', 'Payment ID missing.');
        }

        $secretKey = config('services.moyasar.secret_key');

        $response = Http::withBasicAuth($secretKey, '')
            ->get("https://api.moyasar.com/v1/payments/{$paymentId}");

        if ($response->successful() && $response->json('status') === 'paid') {
            if ($orderId) {
                $order = Order::find($orderId);
                if ($order) {
                    $order->update(['status' => 'paid']);
                    return redirect()->route('payment.success')->with('message', 'Payment successful.');
                }
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
