<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\MerchItem;
use App\Models\MerchPurchase;
use App\Services\OneKhusaService;
use Illuminate\Http\Request;

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

        $cartItem = CartItem::firstOrNew([
            'user_id'      => auth()->id(),
            'merch_item_id' => $merchItem->id,
        ]);

        $newQty = $cartItem->exists ? $cartItem->quantity + (int) $data['quantity'] : (int) $data['quantity'];
        $cartItem->quantity = min($newQty, $merchItem->stock);
        $cartItem->save();

        $cartCount = auth()->user()->cartCount();

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

        $cartItem = CartItem::firstOrNew([
            'user_id'      => auth()->id(),
            'merch_item_id' => $merchItem->id,
        ]);

        $newQty = $cartItem->exists ? $cartItem->quantity + (int) $data['quantity'] : (int) $data['quantity'];
        $cartItem->quantity = min($newQty, $merchItem->stock);
        $cartItem->save();

        return redirect()->route('merch.cart')
            ->with('success', $merchItem->name . ' added to cart.');
    }

    public function updateCart(Request $request, MerchItem $merchItem)
    {
        $data = $request->validate([
            'quantity' => 'required|integer|min:0|max:' . $merchItem->stock,
        ]);

        $quantity = (int) $data['quantity'];

        if ($quantity <= 0) {
            CartItem::where('user_id', auth()->id())
                ->where('merch_item_id', $merchItem->id)
                ->delete();
        } else {
            CartItem::updateOrCreate(
                ['user_id' => auth()->id(), 'merch_item_id' => $merchItem->id],
                ['quantity' => $quantity]
            );
        }

        return redirect()->route('merch.cart')
            ->with('success', 'Cart updated.');
    }

    public function removeFromCart(MerchItem $merchItem)
    {
        CartItem::where('user_id', auth()->id())
            ->where('merch_item_id', $merchItem->id)
            ->delete();

        return redirect()->route('merch.cart')
            ->with('success', $merchItem->name . ' removed from cart.');
    }

    public function cart()
    {
        $cartItems = CartItem::where('user_id', auth()->id())
            ->with('merchItem')
            ->get();

        $items = [];
        $total = 0;

        foreach ($cartItems as $ci) {
            $item = $ci->merchItem;
            if (! $item || ! $item->is_active) continue;

            $quantity = min($ci->quantity, $item->stock);
            $subtotal = $item->price * $quantity;
            $items[] = [
                'item'     => $item,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
            ];
            $total += $subtotal;
        }

        return view('merch.cart', compact('items', 'total'));
    }

    public function checkout()
    {
        $cartItems = CartItem::where('user_id', auth()->id())
            ->with('merchItem')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('merch.index')
                ->withErrors(['cart' => 'Your cart is empty.']);
        }

        $purchases = [];
        $totalAmount = 0;

        foreach ($cartItems as $ci) {
            $item = $ci->merchItem;

            if (! $item || ! $item->is_active || $item->stock <= 0) {
                continue;
            }

            $quantity = min($ci->quantity, $item->stock);
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

        $oneKhusa = app(OneKhusaService::class);

        if (! $oneKhusa->isConfigured()) {
            foreach ($purchases as $p) {
                $p->item->decrement('stock', $p->quantity);
                $p->update(['status' => 'completed', 'paid_at' => now()]);
            }

            CartItem::where('user_id', auth()->id())->delete();

            return redirect()->route('merch.checkout.success', ['order_ref' => $orderRef])
                ->with('info', 'Payment gateway not configured. Order marked as completed for testing.');
        }

        $result = $oneKhusa->initiateRequestToPay(
            referenceNumber: $orderRef,
            description: 'CSIT Merch Order',
            amount: (float) $totalAmount,
            capturedBy: auth()->user()->email,
        );

        if ($result && ! empty($result['timedAccountNumber'])) {
            $newRef = $result['referenceNumber'] ?? $orderRef;

            foreach ($purchases as $p) {
                $p->update(['gateway_reference' => $newRef]);
            }

            session()->put('merch_tan_' . $newRef, [
                'number' => $result['timedAccountNumber'],
                'expiry' => $result['expiryDate'],
                'total'  => $totalAmount,
            ]);

            return redirect()->route('merch.payment.tan', ['order_ref' => $newRef]);
        }

        foreach ($purchases as $p) {
            $p->update(['status' => 'failed']);
        }

        return redirect()->route('merch.cart')
            ->withErrors(['payment' => 'Payment initiation failed.']);
    }

    public function showTan(Request $request)
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
            CartItem::where('user_id', auth()->id())->delete();
            return view('merch.success', ['purchases' => $purchases, 'orderRef' => $orderRef]);
        }

        $tanData = session('merch_tan_' . $orderRef);

        if (! $tanData) {
            return redirect()->route('merch.cart')
                ->withErrors(['payment' => 'Payment session expired. Please try again.']);
        }

        $totalAmount = $tanData['total'] ?? $purchases->sum('amount');

        return view('merch.payment-tan', [
            'purchases'   => $purchases,
            'orderRef'    => $orderRef,
            'tanNumber'   => $tanData['number'],
            'tanExpiry'   => $tanData['expiry'],
            'totalAmount' => $totalAmount,
        ]);
    }

    public function checkTanStatus(Request $request)
    {
        $orderRef = $request->input('order_ref');

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
            CartItem::where('user_id', auth()->id())->delete();
            return view('merch.success', ['purchases' => $purchases, 'orderRef' => $orderRef]);
        }

        $oneKhusa = app(OneKhusaService::class);

        if ($oneKhusa->isConfigured()) {
            $transaction = $oneKhusa->getTransaction($orderRef);

            if ($transaction && ($transaction['transaction']['transactionStatusCode'] ?? '') === 'S') {
                foreach ($purchases as $p) {
                    if ($p->status !== 'completed') {
                        $p->item->decrement('stock', $p->quantity);
                        $p->update(['status' => 'completed', 'paid_at' => now()]);
                    }
                }

                CartItem::where('user_id', auth()->id())->delete();
                session()->forget('merch_tan_' . $orderRef);

                return view('merch.success', ['purchases' => $purchases, 'orderRef' => $orderRef]);
            }
        }

        return back()->withErrors(['status' => 'Payment not yet received. Please try again after sending the funds.']);
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
            CartItem::where('user_id', auth()->id())->delete();
            return view('merch.success', ['purchases' => $purchases, 'orderRef' => $orderRef]);
        }

        $oneKhusa = app(OneKhusaService::class);

        if ($oneKhusa->isConfigured()) {
            $transaction = $oneKhusa->getTransaction($orderRef);

            if ($transaction && ($transaction['transaction']['transactionStatusCode'] ?? '') === 'S') {
                foreach ($purchases as $p) {
                    if ($p->status !== 'completed') {
                        $p->item->decrement('stock', $p->quantity);
                        $p->update(['status' => 'completed', 'paid_at' => now()]);
                    }
                }
                CartItem::where('user_id', auth()->id())->delete();
                return view('merch.success', ['purchases' => $purchases, 'orderRef' => $orderRef]);
            }
        }

        foreach ($purchases as $p) {
            if ($p->status !== 'completed') {
                $p->item->decrement('stock', $p->quantity);
                $p->update(['status' => 'completed', 'paid_at' => now()]);
            }
        }

        CartItem::where('user_id', auth()->id())->delete();

        return view('merch.success', ['purchases' => $purchases, 'orderRef' => $orderRef]);
    }
}
