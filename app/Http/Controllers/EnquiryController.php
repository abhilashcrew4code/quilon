<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnquiryController extends Controller
{
    //List
    public function index(Request $request)
    {
        $entry_count = $request->entry_count ?? 10;

        $query = Enquiry::where('status',  1)->newQuery();

        if ($request->search != '') {
            $query->where(function ($q1) use ($request) {
                $q1->where('customer_name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('quantity', 'LIKE', '%' . $request->search . '%');
            });
        }


        if ($request->order_status != '') {
            $query->where('order_status', $request->order_status);
        }

        $enquiry = $query->orderBy('id', 'DESC')->paginate($entry_count);

        if ($request->ajax()) {
            return view('enquiry.listPagin', compact('enquiry'));
        }

        return view('enquiry.list', compact('enquiry'));
    }

    // Create
    public function create()
    {
        $products = Product::where('status', 1)->get();
        return view('enquiry.create', compact('products'));
    }

    public function show() {}

    //Store
    public function store(Request $request)
    {
        $rules = [
            'customer_name' => 'required|max:255',
            'date'          => 'required|date',
            'quantity'  => 'required',
        ];

        $messages = [
            'customer_name.required' => 'Customer name is required.',
            'date.required'      => 'Date is required.',
            'date.required'      => 'Quantity is required.',
        ];

        $this->validate($request, $rules, $messages);

        $enquiry = Enquiry::create([
            'customer_name' => $request->customer_name,
            'quantity'      => $request->quantity,
            'product_id'    => $request->product_id,
            'date'          => $request->date ?? now(),
            'remarks'       => $request->remarks,
            'status'        => 1,
            'order_status'  => $request->order_status,
            'user_id'       => Auth::id(),
        ]);


        return response()->json([
            'status'  => 'success',
            'message' => 'Enquiry Created Successfully',
        ]);
    }

    // Edit
    public function edit($id)
    {
        $enquiry = Enquiry::findOrFail($id);
        $products = Product::where('status', 1)->get();
        return view('enquiry.create', compact('enquiry', 'products'));
    }

    // Update
    public function update(Request $request, $id)
    {
        $rules = [
            'customer_name' => 'required|max:255',
            'date'          => 'required|date',
            'quantity'  => 'required',
        ];

        $messages = [
            'customer_name.required' => 'Customer name is required.',
            'date.required'      => 'Date is required.',
            'date.required'      => 'Quantity is required.',
        ];

        $this->validate($request, $rules, $messages);

        $update_stat = Enquiry::where('id', $id)->update([
            'customer_name' => $request->customer_name,
            'quantity'      => $request->quantity,
            'product_id'    => $request->product_id,
            'date'          => $request->date ?? now(),
            'remarks'       => $request->remarks,
            'order_status'  => $request->order_status,
            'user_id'       => Auth::id(),
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Order Updated Successfully',
        ]);
    }

    //Delete
    public function deleteData(string $id)
    {
        $enquiry = Enquiry::find($id);
        if (!$enquiry)
            return response()->json(['status' => 'error',  'message' => 'No Enquiry Found.']);
        $status = ($enquiry->status == 1) ? 2 : 1;

        $enquiry->status  = $status;
        $enquiry->save();

        if ($status == 1) {
            return response()->json(['status' => 'success',  'message' => 'Enquiry Unblocked Successfully.']);
        } else {
            return response()->json(['status' => 'success',  'message' => 'Enquiry Deleted Successfully.']);
        }
    }
}
