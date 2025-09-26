<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    //List
    public function index(Request $request)
    {
        $entry_count         = $request->entry_count ? $request->entry_count : 10;

        $query = Expense::where('status', '<>', '3')->newQuery();

        if ($request->search != '') {
            $query->where(function ($q1) use ($request) {
                $q1->where('name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('amount', 'LIKE', '%' . $request->search . '%');
            });
        }

        $expenses = $query->orderBy('id', 'DESC')->paginate($entry_count);

        if ($request->ajax()) {
            return view('expenses.listPagin', compact('expenses'));
        }
        return view('expenses.list', compact('expenses'));
    }

    //Create
    public function create()
    {
        return view('expenses.create');
    }

    //Store
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:255',
            'remarks' => 'nullable|max:255',
            'amount' => 'required|max:255',
            'date' => 'required',
        ];

        $customMessages = [
            'name.required' => 'Name is required.',
            'amount.required' => 'Amount is required',
            'date.required' => 'Date is required',
        ];

        $validatedData = $this->validate($request, $rules, $customMessages);

        $expense = Expense::create([
            'name' => $request->name,
            'remarks' => $request->remarks,
            'amount' => $request->amount,
            'date' => $request->date,
            'user_id' => Auth::id(),
            'status' => 1,
        ]);

        return response()->json(['status' => 'success',  'message' => 'New Expense Created Successfully']);
    }

    //Edit
    public function edit($id)
    {
        $expense = Expense::find($id);
        return view('expenses.create', compact('expense'));
    }

    //Update
    public function update(Request $request, $id)
    {

        $rules = [
            'name' => 'required|max:255',
            'remarks' => 'nullable|max:255',
            'amount' => 'required|max:255',
            'date' => 'required',
        ];

        $customMessages = [
            'name.required' => 'Name is required.',
            'amount.required' => 'Amount is required',
            'date.required' => 'Date is required',
        ];

        $validatedData = $this->validate($request, $rules, $customMessages);

        $update_stat = Expense::where('id', $id)->update([
            'name' => $request->name,
            'remarks' => $request->remarks,
            'amount' => $request->amount,
            'date' => $request->date,
            'user_id' => Auth::id(),
        ]);

        return response()->json(['status' => 'success',  'message' => 'Expense updated Successfully']);
    }

    //Destroy
    public function destroy($id)
    {
        $update_stat = User::where('id', $id)->update([
            'status' => 3,
            //'user_id'=>Auth::id(),
        ]);
        //user::find($id)->delete();
        return response()->json(['status' => 'success',  'message' => 'Expense deleted successfully.']);
    }
}
