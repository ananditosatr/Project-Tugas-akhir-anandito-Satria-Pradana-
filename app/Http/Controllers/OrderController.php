<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:100',
            'no_whatsapp'   => 'required|string|max:20',
            'notes'         => 'nullable|string',
            'payment_method' => 'required|in:cash,qris',
            'proof_image'   => 'nullable|string',
            'items'         => 'required|array|min:1',
            'items.*.menu_id'  => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $totalAmount = 0;
            $menuItems = [];
            foreach ($validated['items'] as $item) {
                $menu = Menu::findOrFail($item['menu_id']);
                $totalAmount += $menu->price * $item['quantity'];
                $menuItems[] = ['menu' => $menu, 'quantity' => $item['quantity']];
            }

            // Determine order status based on payment method
            $orderStatus = $validated['payment_method'] === 'cash' ? 'processing' : 'pending_verification';
            
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'customer_name' => $validated['customer_name'],
                'no_whatsapp' => $validated['no_whatsapp'],
                'total_amount' => $totalAmount,
                'status' => $orderStatus,
                'payment_method' => $validated['payment_method'],
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($menuItems as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'menu_id'    => $item['menu']->id,
                    'quantity'   => $item['quantity'],
                    'unit_price' => $item['menu']->price,
                    'subtotal'   => $item['menu']->price * $item['quantity'],
                ]);
            }

            // Create payment record
            $paymentData = [
                'order_id' => $order->id,
                'payment_method' => $validated['payment_method'],
                'status' => $validated['payment_method'] === 'cash' ? 'completed' : 'uploaded',
            ];

            if ($validated['payment_method'] === 'qris' && $validated['proof_image']) {
                $paymentData['proof_image_base64'] = $validated['proof_image'];
                $paymentData['uploaded_at'] = now();
            }

            Payment::create($paymentData);

            DB::commit();

            return response()->json([
                'success' => true,
                'order' => $order->load('items.menu'),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order creation failed', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pesanan. Silakan coba lagi.',
            ], 500);
        }
    }

    public function show($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->with(['items.menu', 'payment'])
            ->firstOrFail();

        return response()->json($order);
    }

}
