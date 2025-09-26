<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Invest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvestController extends Controller
{
    //List
    public function index(Request $request)
    {
        $entry_count = $request->entry_count ?? 10;

        $query = Invest::where('status',  1)->newQuery();

        if ($request->search != '') {
            $query->where(function ($q1) use ($request) {
                $q1->where('name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('amount', 'LIKE', '%' . $request->search . '%');
            });
        }


        $invest = $query->orderBy('id', 'DESC')->paginate($entry_count);

        if ($request->ajax()) {
            return view('invest.listPagin', compact('invest'));
        }

        return view('invest.list', compact('invest'));
    }

    // Create
    public function create()
    {
        return view('invest.create');
    }

    public function show() {}

    //Store
    public function store(Request $request)
    {
        $rules = [
            'name'      => 'required|max:255',
            'amount'    => 'required',
            'date'      => 'required|date',

        ];

        $messages = [
            'name.required' => 'Customer name is required.',
            'amount.required'      => 'Date is required.',
            'date.required'      => 'Date is required.',
        ];

        $this->validate($request, $rules, $messages);

        $invest = Invest::create([
            'name' => $request->name,
            'amount'      => $request->amount,
            'date'          => $request->date ?? now(),
            'remarks'       => $request->remarks,
            'status'        => 1,
            'user_id'       => Auth::id(),
        ]);


        return response()->json([
            'status'  => 'success',
            'message' => 'Invest Created Successfully',
        ]);
    }

    // Edit
    public function edit($id)
    {
        $invest = Invest::findOrFail($id);
        return view('invest.create', compact('invest'));
    }

    // Update
    public function update(Request $request, $id)
    {
        $rules = [
            'name'      => 'required|max:255',
            'amount'    => 'required',
            'date'      => 'required|date',

        ];

        $messages = [
            'name.required' => 'Customer name is required.',
            'amount.required'      => 'Date is required.',
            'date.required'      => 'Date is required.',
        ];

        $this->validate($request, $rules, $messages);

        $update_stat = Invest::where('id', $id)->update([
            'name' => $request->name,
            'amount'      => $request->amount,
            'date'          => $request->date ?? now(),
            'remarks'       => $request->remarks,
            'user_id'       => Auth::id(),
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Invest Updated Successfully',
        ]);
    }

    //Delete
    public function deleteData(string $id)
    {
        $invest = Invest::find($id);
        if (!$invest)
            return response()->json(['status' => 'error',  'message' => 'No Invest Found.']);
        $status = ($invest->status == 1) ? 2 : 1;

        $invest->status  = $status;
        $invest->save();

        if ($status == 1) {
            return response()->json(['status' => 'success',  'message' => 'Invest Unblocked Successfully.']);
        } else {
            return response()->json(['status' => 'success',  'message' => 'Invest Deleted Successfully.']);
        }
    }
}
