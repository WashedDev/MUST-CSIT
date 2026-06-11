<?php

namespace App\Http\Controllers;

use App\Models\MerchItem;
use App\Models\MerchPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MerchController extends Controller
{
    public function index()
    {
        $items = MerchItem::where('is_active', true)
            ->latest()
            ->paginate(12);

        return view('merch.index', compact('items'));
    }

    public function show(MerchItem $merchItem)
    {
        if (! $merchItem->is_active) {
            abort(404);
        }

        return view('merch.show', compact('merchItem'));
    }

    public function details(MerchItem $merchItem)
    {
        if (! $merchItem->is_active) {
            abort(404);
        }

        $html = view('merch.modal-content', compact('merchItem'))->render();

        return response()->json(['html' => $html]);
    }

    public function addToCartJson(Request $request, MerchItem $merchItem)
    {
        if (! $merchItem->is_active || ! $merchItem->inStock()) {
            return response()->json(['error' => 'This item is not available.'], 422);
        }

        $data = $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $merchItem->stock,
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$merchItem->id])) {
            $newQty = $cart[$merchItem->id]['quantity'] + (int) $data['quantity'];
            $cart[$merchItem->id]['quantity'] = min($newQty, $merchItem->stock);
        } else {
            $cart[$merchItem->id] = [
                'item_id'  => $merchItem->id,
                'quantity' => (int) $data['quantity'],
            ];
        }

        session()->put('cart', $cart);

        $cartCount = array_sum(array_column($cart, 'quantity'));

        return response()->json([
            'success'    => $merchItem->name . ' added to cart.',
            'cart_count' => $cartCount,
        ]);
    }

    public function addToCart(Request $request, MerchItem $merchItem)
    {
        if (! $merchItem->is_active || ! $merchItem->inStock()) {
            return back()->withErrors(['item' => 'This item is not available.']);
        }

        $data = $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $merchItem->stock,
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$merchItem->id])) {
            $newQty = $cart[$merchItem->id]['quantity'] + (int) $data['quantity'];
            $cart[$merchItem->id]['quantity'] = min($newQty, $merchItem->stock);
        } else {
            $cart[$merchItem->id] = [
                'item_id'  => $merchItem->id,
                'quantity' => (int) $data['quantity'],
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('merch.cart')
            ->with('success', $merchItem->name . ' added to cart.');
    }

    public function updateCart(Request $request, MerchItem $merchItem)
    {
        $data = $request->validate([
            'quantity' => 'required|integer|min:0|max:' . $merchItem->stock,
        ]);

        $cart = session()->get('cart', []);

        $quantity = (int) $data['quantity'];

        if ($quantity <= 0) {
            unset($cart[$merchItem->id]);
        } else {
            $cart[$merchItem->id] = [
                'item_id'  => $merchItem->id,
                'quantity' => $quantity,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('merch.cart')
            ->with('success', 'Cart updated.');
    }

    public function removeFromCart(MerchItem $merchItem)
    {
        $cart = session()->get('cart', []);

        unset($cart[$merchItem->id]);

        session()->put('cart', $cart);

        return redirect()->route('merch.cart')
            ->with('success', $merchItem->name . ' removed from cart.');
    }

    public function cart()
    {
        $cart = session()->get('cart', []);
        $items = [];
        $total = 0;

        if (! empty($cart)) {
            $ids = array_keys($cart);
            $merchItems = MerchItem::whereIn('id', $ids)->get()->keyBy('id');

            foreach ($cart as $id => $entry) {
                if (isset($merchItems[$id])) {
                    $item = $merchItems[$id];
                    $quantity = min($entry['quantity'], $item->stock);
                    $subtotal = $item->price * $quantity;
                    $items[] = [
                        'item'     => $item,
                        'quantity' => $quantity,
                        'subtotal' => $subtotal,
                    ];
                    $total += $subtotal;
                }
            }
        }

        return view('merch.cart', compact('items', 'total'));
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('merch.index')
                ->withErrors(['cart' => 'Your cart is empty.']);
        }

        $ids = array_keys($cart);
        $merchItems = MerchItem::whereIn('id', $ids)->get()->keyBy('id');

        $purchases = [];
        $totalAmount = 0;

        foreach ($cart as $id => $entry) {
            $item = $merchItems[$id] ?? null;

            if (! $item || ! $item->is_active || $item->stock <= 0) {
                continue;
            }

            $quantity = min($entry['quantity'], $item->stock);
            $amount = $item->price * $quantity;
            $totalAmount += $amount;

            $purchases[] = MerchPurchase::create([
                'user_id'  => auth()->id(),
                'merch_item_id' => $item->id,
                'quantity' => $quantity,
                'amount'   => $amount,
                'status'   => 'pending',
            ]);
        }

        if (empty($purchases)) {
            return redirect()->route('merch.cart')
                ->withErrors(['cart' => 'No available items in your cart.']);
        }

        $orderRef = 'CSIT-ORD-' . time() . '-' . auth()->id();

        foreach ($purchases as $p) {
            $p->update(['gateway_reference' => $orderRef]);
        }

        $apiToken = config('services.ctechpay.token');
        $baseUrl = config('services.ctechpay.base_url');

        $payload = [
            'token'               => $apiToken,
            'amount'              => (int) $totalAmount,
            'category_flag'       => 'PRODUCT_PURCHASE',
            'customer_reference'  => $orderRef,
            'customer_message'    => 'CSIT Merch Order',
            'merchantAttributes'  => true,
            'redirectUrl'         => route('merch.checkout.success', ['order_ref' => $orderRef]),
            'cancelUrl'           => route('merch.cart'),
            'cancelText'          => 'Go Back',
            'skipConfirmationPage' => false,
        ];

        if (! $apiToken) {
            foreach ($purchases as $p) {
                $p->item->decrement('stock', $p->quantity);
                $p->update(['status' => 'completed', 'paid_at' => now()]);
            }

            session()->forget('cart');

            return redirect()->route('merch.checkout.success', ['order_ref' => $orderRef])
                ->with('info', 'Payment gateway not configured. Order marked as completed for testing.');
        }

        try {
            $response = Http::post($baseUrl . '/api/v1/orders', $payload);
            $body = $response->json();

            if ($response->successful() && ! empty($body['payment_page_URL'])) {
                $newRef = $body['order_reference'] ?? $orderRef;

                foreach ($purchases as $p) {
                    $p->update(['gateway_reference' => $newRef]);
                }

                return redirect($body['payment_page_URL']);
            }

            foreach ($purchases as $p) {
                $p->update(['status' => 'failed']);
            }

            return redirect()->route('merch.cart')
                ->withErrors(['payment' => 'Payment initiation failed.']);
        } catch (\Exception $e) {
            foreach ($purchases as $p) {
                $p->update(['status' => 'failed']);
            }

            return redirect()->route('merch.cart')
                ->withErrors(['payment' => 'Could not connect to payment gateway.']);
        }
    }

    public function checkoutSuccess(Request $request)
    {
        $orderRef = $request->query('order_ref');

        if (! $orderRef) {
            return redirect()->route('merch.index');
        }

        $purchases = MerchPurchase::where('gateway_reference', $orderRef)
            ->where('user_id', auth()->id())
            ->with('item')
            ->get();

        if ($purchases->isEmpty()) {
            return redirect()->route('merch.index');
        }

        $completed = $purchases->every(fn ($p) => $p->status === 'completed');

        if ($completed) {
            session()->forget('cart');
            return view('merch.success', ['purchases' => $purchases, 'orderRef' => $orderRef]);
        }

        $apiToken = config('services.ctechpay.token');
        $baseUrl = config('services.ctechpay.base_url');

        if ($apiToken) {
            try {
                $response = Http::get($baseUrl . '/api/v1/orders/status', [
                    'orderRef' => $orderRef,
                    'token'    => $apiToken,
                ]);
                $body = $response->json();

                if ($response->successful() && ($body['status'] ?? '') === 'COMPLETED') {
                    foreach ($purchases as $p) {
                        if ($p->status !== 'completed') {
                            $p->item->decrement('stock', $p->quantity);
                            $p->update(['status' => 'completed', 'paid_at' => now()]);
                        }
                    }
                    session()->forget('cart');
                    return view('merch.success', ['purchases' => $purchases, 'orderRef' => $orderRef]);
                }
            } catch (\Exception $e) {}
        }

        foreach ($purchases as $p) {
            if ($p->status !== 'completed') {
                $p->item->decrement('stock', $p->quantity);
                $p->update(['status' => 'completed', 'paid_at' => now()]);
            }
        }

        session()->forget('cart');

        return view('merch.success', ['purchases' => $purchases, 'orderRef' => $orderRef]);
    }
}
