<?php

namespace App\Http\Controllers;

use App\Models\Order;

class StatusController extends Controller
{
    public function page(string $table_number)
    {
        return view('frontend.status', compact('table_number'));
    }

    public function tracking(string $table_number)
    {
        return view('frontend.tracking', compact('table_number'));
    }

    public function check(string $order_id)
    {
        $order = Order::with(['table', 'orderItems.product'])->find($order_id);
        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order tidak ditemukan']);
        }
        return response()->json(['success' => true, 'order' => $order]);
    }
}
