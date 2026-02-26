<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Table;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;

class FrontOrderController extends Controller
{
    public function menu(string $table_number)
    {
        $table    = Table::where('table_number', $table_number)->firstOrFail();
        $products = Product::where('is_ready', true)->get();
        return view('frontend.menu', compact('table', 'products'));
    }

    public function store(Request $request, string $table_number)
    {
        $request->validate([
            'items'          => 'required|array|min:1',
            'items.*.id'     => 'required|exists:products,id',
            'items.*.qty'    => 'required|integer|min:1',
            'payment_method' => 'required|in:qris,dana,gopay,cash',
            'notes'          => 'nullable|string|max:500',
        ]);

        $table      = Table::where('table_number', $table_number)->firstOrFail();
        $total      = 0;
        $orderItems = [];

        foreach ($request->items as $item) {
            $product  = Product::findOrFail($item['id']);
            $subtotal = $product->price * $item['qty'];
            $total   += $subtotal;
            $orderItems[] = [
                'product_id' => $product->id,
                'quantity'   => $item['qty'],
                'price'      => $product->price,
                'subtotal'   => $subtotal,
            ];
        }

        $order = Order::create([
            'table_id'       => $table->id,
            'total_price'    => $total,
            'payment_method' => $request->payment_method,
            'notes'          => $request->notes,
            'status'         => 'pending',
            'payment_status' => 'unpaid',
        ]);

        foreach ($orderItems as $item) {
            $order->orderItems()->create($item);
        }

        return response()->json([
            'success'  => true,
            'order_id' => $order->id,
            'message'  => 'Pesanan berhasil masuk!',
        ]);
    }
}