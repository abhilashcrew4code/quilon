<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    //List
    public function index(Request $request)
    {
        $entry_count = $request->entry_count ?? 10;

        $query = Order::where('status', '<>', 3)->newQuery();

        if ($request->search != '') {
            $query->where(function ($q1) use ($request) {
                $q1->where('customer_name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('total_amount', 'LIKE', '%' . $request->search . '%');
                // ->orWhere('mrp', 'LIKE', '%' . $request->search . '%')
                // ->orWhere('price', 'LIKE', '%' . $request->search . '%');
            });
        }


        if ($request->payment_status != '') {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->delivery_status != '') {
            $query->where('delivery_status', $request->delivery_status);
        }


        $orders = $query->orderBy('id', 'DESC')->paginate($entry_count);

        if ($request->ajax()) {
            return view('orders.listPagin', compact('orders'));
        }

        return view('orders.list', compact('orders'));
    }

    // Create
    public function create()
    {
        $products = Product::where('status', 1)->get();
        return view('orders.create', compact('products'));
    }

    //Store
    public function store(Request $request)
    {
        // ✅ Validation
        $rules = [
            'customer_name' => 'required|max:255',
            'date'          => 'nullable|date',
            'total_amount'  => 'required|numeric|min:0',
            'remarks'       => 'nullable|string',
            'payment_status' => 'required|in:0,1,2', // 0 = unpaid, 1 = paid, 2 = partial
            'products'      => 'required|array|min:1',
            // 'products.*.id' => 'required|exists:products,id',
            // 'products.*.qty' => 'required|integer|min:1',
            // 'products.*.price' => 'required|numeric|min:0',
        ];

        $messages = [
            'customer_name.required' => 'Customer name is required.',
            'products.required'      => 'At least one product is required.',
        ];

        $this->validate($request, $rules, $messages);

        $order = Order::create([
            'customer_name' => $request->customer_name,
            'total_amount'  => $request->total_amount,
            'date'          => $request->date ?? now(),
            'remarks'       => $request->remarks,
            'status'        => 1,
            'payment_status' => $request->payment_status,
            'delivery_status' => $request->delivery_status,
            'user_id'       => Auth::id(),
        ]);

        foreach ($request->products as $product) {
            $subtotal = $product['quantity'] * $product['price'];

            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $product['product_id'],
                'quantity'   => $product['quantity'],
                'price'      => $product['price'],
                'subtotal'   => $subtotal,
            ]);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Order Created Successfully',
            'order_id' => $order->id
        ]);
    }

    // Edit
    public function edit($id)
    {
        $order = Order::findOrFail($id);
        $products = Product::where('status', 1)->get();
        return view('orders.create', compact('order', 'products'));
    }

    // Update
    public function update(Request $request, $id)
    {
        $rules = [
            'customer_name'  => 'required|max:255',
            'date'           => 'nullable|date',
            'total_amount'   => 'required|numeric|min:0',
            'remarks'        => 'nullable|string',
            'payment_status' => 'required|in:0,1,2',
            'delivery_status' => 'required|in:0,1,2,3',
            'products'       => 'required|array|min:1',
        ];

        $messages = [
            'customer_name.required' => 'Customer name is required.',
            'products.required'      => 'At least one product is required.',
        ];

        $this->validate($request, $rules, $messages);

        $order = Order::findOrFail($id);

        $order->update([
            'customer_name'  => $request->customer_name,
            'total_amount'   => $request->total_amount,
            'date'           => $request->date ?? now(),
            'remarks'        => $request->remarks,
            'payment_status' => $request->payment_status,
            'delivery_status' => $request->delivery_status,
            'user_id'        => Auth::id(),
        ]);

        $order->items()->delete();

        foreach ($request->products as $product) {
            $subtotal = $product['quantity'] * $product['price'];

            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $product['product_id'],
                'quantity'   => $product['quantity'],
                'price'      => $product['price'],
                'subtotal'   => $subtotal,
            ]);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Order Updated Successfully',
            'order_id' => $order->id
        ]);
    }

    public function print($id)
    {
        $order = Order::with('items.product')->findOrFail($id);

        $company = [
            'name' => 'Quilon Pickles',
            'email' => 'quilonpickles@gmail.com',
            'whatsapp' => '+91 81191155404',
            'instagram' => 'quilon_pickles',
            'address' => 'Neendakara Kollam, Kerala - 691582',
            'fssai_no' => '21325141001010',
        ];

        $pdf = Pdf::loadView('orders.bill', compact('order', 'company'))
            ->setPaper('A4');

        return $pdf->download('QP-' . str_pad($order->id, 4, '0', STR_PAD_LEFT) . '.pdf');
    }
}
