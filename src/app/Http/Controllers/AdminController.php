<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;

class AdminController extends Controller
{
    public function orders()
    {
        return response()->json(
            Order::with(['table', 'orderItems.product'])
                ->orderBy('created_at', 'desc')
                ->get()
        );
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate(['status' => 'required|in:pending,process,done,cancel']);
        $order->update(['status' => $request->status]);
        return response()->json(['success' => true]);
    }

    public function products()
    {
        return response()->json(Product::orderBy('category')->get());
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name'        => 'required|string',
            'category'    => 'required|in:coffee,noncoffee,food,snack',
            'price'       => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);
        $product = Product::create([
            'name'        => $request->name,
            'category'    => $request->category,
            'price'       => $request->price,
            'description' => $request->description,
            'is_ready'    => true,
        ]);
        return response()->json($product);
    }

    public function updateProduct(Request $request, Product $product)
    {
        $product->update($request->only(['name', 'category', 'price', 'description', 'is_ready']));
        return response()->json($product);
    }

    public function deleteProduct(Product $product)
    {
        $product->delete();
        return response()->json(['success' => true]);
    }
}