<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $cart = $this->getOrCreateCart();
        $cart->load('items.product');
        $items = $cart->items->map(function ($item) {
            return [
                'id' => $item->id,
                'cart_id' => $item->cart_id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'subtotal' => $item->subtotal,
                'product' => new ProductResource($item->product),
            ];
        });

        $subtotal = $cart->items->sum(function ($item) {
            return $item->subtotal;
        });

        return response()->json([
            'subtotal' => $subtotal,
            'cart' => $cart,
            'items' => $items,
            'total' => $cart->total,
            'items_count' => $cart->items_count
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->quantity < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'الكمية المطلوبة غير متوفرة'
            ], 400);
        }

        $cart = $this->getOrCreateCart();
        $cartItem = $cart->items()->where('product_id', $request->product_id)->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $request->quantity;
            if ($product->quantity < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'الكمية المطلوبة غير متوفرة'
                ], 400);
            }
            $cartItem->update(['quantity' => $newQuantity]);
            $product->decrement('quantity', $request->quantity);
        } else {
            $cart->items()->create([
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'price' => $product->discount_price ?? $product->price
            ]);
            $product->decrement('quantity', $request->quantity);
        }

        $cart->load('items.product');
        $items = $cart->items->map(function ($item) {
            return [
                'id' => $item->id,
                'cart_id' => $item->cart_id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'subtotal' => $item->subtotal,
                'product' => new ProductResource($item->product),
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة المنتج إلى السلة بنجاح',
            'cart' => $cart,
            'items' => $items,
            'items_count' => $cart->items_count
        ]);
    }

    public function update(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = CartItem::with('product')->findOrFail($itemId);
        $cart = $this->getOrCreateCart();

        if ($cartItem->cart_id !== $cart->id) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح'
            ], 403);
        }

        $product = $cartItem->product;
        $oldQuantity = $cartItem->quantity;
        $newQuantity = $request->quantity;
        $quantityDiff = $newQuantity - $oldQuantity;

        if ($quantityDiff > 0 && $product->quantity < $quantityDiff) {
            return response()->json([
                'success' => false,
                'message' => 'الكمية المطلوبة غير متوفرة'
            ], 400);
        }

        $cartItem->update([
            'quantity' => $newQuantity,
            'price' => $product->discount_price ?? $product->price
        ]);

        if ($quantityDiff > 0) {
            $product->decrement('quantity', $quantityDiff);
        } elseif ($quantityDiff < 0) {
            $product->increment('quantity', abs($quantityDiff));
        }

        $items = $cart->items->map(function ($item) {
            return [
                'id' => $item->id,
                'cart_id' => $item->cart_id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'subtotal' => $item->subtotal,
                'product' => new ProductResource($item->product),
            ];
        });

        $subtotal = $cart->items->sum(function ($item) {
            return $item->subtotal;
        });

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الكمية بنجاح',
            'cart' => $cart,
            'items' => $items,
            'total' => $cart->total,
            'subtotal' => $subtotal,
            'items_count' => $cart->items_count
        ]);
    }

    public function remove($itemId)
    {
        $cartItem = CartItem::findOrFail($itemId);
        $cart = $this->getOrCreateCart();

        if ($cartItem->cart_id !== $cart->id) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح'
            ], 403);
        }

        $product = Product::find($cartItem->product_id);
        if ($product) {
            $product->increment('quantity', $cartItem->quantity);
        }

        $cartItem->delete();

        $cart->load('items.product');
        $items = $cart->items->map(function ($item) {
            return [
                'id' => $item->id,
                'cart_id' => $item->cart_id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'subtotal' => $item->subtotal,
                'product' => new ProductResource($item->product),
            ];
        });

        $subtotal = $cart->items->sum(function ($item) {
            return $item->subtotal;
        });

        return response()->json([
            'success' => true,
            'message' => 'تم حذف المنتج من السلة بنجاح',
            'cart' => $cart,
            'items' => $items,
            'total' => $cart->total,
            'subtotal' => $subtotal,
            'items_count' => $cart->items_count
        ]);
    }

    public function clear()
    {
        $cart = $this->getOrCreateCart();
        foreach ($cart->items as $item) {
            $product = Product::find($item->product_id);
            if ($product) {
                $product->increment('quantity', $item->quantity);
            }
        }
        $cart->items()->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم تفريغ السلة بنجاح',
            'cart' => $cart,
            'items_count' => 0
        ]);
    }

    private function getOrCreateCart()
    {
        $sessionId = Session::getId();
        $userId = Auth::user()?->id;

        $cart = Cart::where(function($query) use ($userId, $sessionId) {
            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('session_id', $sessionId);
            }
        })->first();

        if (!$cart) {
            $cart = Cart::create([
                'user_id' => $userId,
                'session_id' => $sessionId
            ]);
        }

        return $cart;
    }
}
