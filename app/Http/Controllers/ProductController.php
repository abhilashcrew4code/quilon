<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    //List
    public function index(Request $request)
    {
        $entry_count = $request->entry_count ?? 10;

        if (!Auth::user()->hasRole('super-admin')) {
            $query = Product::where('status', '<>', 3)->where('user_id', Auth::id())->newQuery();
        } else {
            $query = Product::where('status', '<>', 3)->newQuery();
        }

        if ($request->search != '') {
            $query->where(function ($q1) use ($request) {
                $q1->where('name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('code', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('mrp', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('price', 'LIKE', '%' . $request->search . '%');
            });
        }

        $products = $query->orderBy('id', 'DESC')->paginate($entry_count);

        if ($request->ajax()) {
            return view('products.listPagin', compact('products'));
        }

        return view('products.list', compact('products'));
    }

    // Create
    public function create()
    {
        return view('products.create');
    }

    // Store
    public function store(Request $request)
    {
        $rules = [
            'name'        => 'required|max:255',
            'code'        => 'required|unique:products,code',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable',
            'mrp'         => 'required|numeric',
            'price'       => 'required|numeric',
            'stock'       => 'required|integer|min:0',
        ];

        $customMessages = [
            'name.required'  => 'Product name is required.',
            'code.required'  => 'Product code is required.',
            'mrp.required'   => 'MRP is required.',
            'price.required' => 'Price is required.',
            'stock.required' => 'Stock is required.',
        ];

        $this->validate($request, $rules, $customMessages);

        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/products'), $imageName);
        }

        Product::create([
            'name'        => $request->name,
            'code'        => $request->code,
            'image'       => $imageName,
            'description' => $request->description,
            'mrp'         => $request->mrp,
            'price'       => $request->price,
            'stock'       => $request->stock,
            'status'      => 1,
            'user_id'     => Auth::id(),
        ]);

        return response()->json(['status' => 'success', 'message' => 'New Product Created Successfully']);
    }

    // Edit
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('products.create', compact('product'));
    }

    // Update
    public function update(Request $request, $id)
    {
        $rules = [
            'name'        => 'required|max:255',
            'code'        => 'required|unique:products,code,' . $id,
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable',
            'mrp'         => 'required|numeric',
            'price'       => 'required|numeric',
            'stock'       => 'required|integer|min:0',
        ];

        $customMessages = [
            'name.required'  => 'Product name is required.',
            'code.required'  => 'Product code is required.',
            'mrp.required'   => 'MRP is required.',
            'price.required' => 'Price is required.',
            'stock.required' => 'Stock is required.',
        ];

        $this->validate($request, $rules, $customMessages);

        $product = Product::findOrFail($id);

        $imageName = $product->image;
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/products'), $imageName);
        }

        $product->update([
            'name'        => $request->name,
            'code'        => $request->code,
            'image'       => $imageName,
            'description' => $request->description,
            'mrp'         => $request->mrp,
            'price'       => $request->price,
            'stock'       => $request->stock,
            'user_id'     => Auth::id(),
        ]);

        return response()->json(['status' => 'success', 'message' => 'Product Updated Successfully']);
    }
}
