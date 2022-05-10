<?php

namespace App\Http\Controllers;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ExpenseRequest;
use App\Exports\ExpenseExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use App\Traits\ExpenseTrait;

class ExpenseController extends Controller
{
    use ExpenseTrait;

    protected $header = array(
        'Content-type' => 'application/json',
        'charset' => 'utf-8',
    );

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request  $request
     * @return object
     */
    public function index(Request $request)
    {
        try 
        {
            return $this->getAllExpenses($request->filter_by_category, $request->filter_by_date);
        }
        catch (Exception $e)
        {
            Log::info('Something Wrong In Expense Show Function!!'.' ('. $e->getmessage().')');
        }
    }

    /**
     * Get total amount of filtered expenses
     *
     * @param  \Illuminate\Http\Request  $request
     * @return int
     */
    public function totalAmount(Request $request)
    {
        try 
        {
            $expenses = $this->getAllExpenses($request->filter_by_category, $request->filter_by_date);
           
            $total_amount = $expenses->sum("total_price");
            return $total_amount;
        }
        catch (Exception $e)
        {
            Log::info('Something Wrong In Total Amount By Search Function!!'.' ('. $e->getmessage().')');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\ExpenseRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExpenseRequest $request)
    {
        try
        {
            $expense = new Expense;
            $expense->date = $request->get('date');
            $expense->acc_cat_code = $request->get('acc_cat_code');
            $expense->name = $request->get('name');
            $expense->price = $request->get('price');
            $expense->quantity = $request->get('quantity');
            $expense->total_price = $request->get('total_price');
            $expense->supplier = $request->get('supplier');
            $expense->company = $request->get('company');
            $expense->save();
            $return_message = [
                'msg' => "Created Successful.",
                "Expense" => $expense->latest()->first(),
            ];
            return response()->json($return_message, 200, $this->header);
        }
        catch (Exception $e)
        {
            return response()->json(["msg" => "Error while Creating!"], 500, $this->header);
            Log::info('Something Wrong In Expense Store Function!!'.' ('. $e->getmessage().')');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try
        {
            $expense = Expense::find($id);
            if (is_null($expense)) {
                return response()->json(["msg" => "Data not found!"], 404, $this->header);
            }
            else{
                return $expense;
            }
        }
        catch (Exception $e)
        {
            Log::info('Something Wrong In Expense Show Function!!'.' ('. $e->getmessage().')');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param App\Http\Requests\ExpenseRequest $request
     * @param int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ExpenseRequest $request, $id)
    {
        try
        {
            $expense = Expense::find($id);
            $expense->date = $request->get('date');
            $expense->acc_cat_code = $request->get('acc_cat_code');
            $expense->name = $request->get('name');
            $expense->price = $request->get('price');
            $expense->quantity = $request->get('quantity');
            $expense->total_price = $request->get('total_price');
            $expense->supplier = $request->get('supplier');
            $expense->company = $request->get('company');
            $expense->update();
            $return_message = [
                'msg' => "Updated Successful.",
                "Expense" => $expense->latest()->first(),
            ];
            return response()->json($return_message, 200, $this->header);
        }
        catch (Exception $e)
        {
            return response()->json(["msg" => "Error while Updating!"], 500, $this->header);
            Log::info('Something Wrong In Expense Update Function!!'.' ('. $e->getmessage().')');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try
        {
            $expense = Expense::destroy($id);
            return response()->json(["msg" => "Deleted Sucessful!"], 200, $this->header);
        }
        catch (Exception $e)
        {
            return response()->json(["msg" => "Error while Deleting!"], 500, $this->header);
            Log::info('Something Wrong In Expense Delete Function!!'.' ('. $e->getmessage().')');
        }
    }


    /**
     * Export Expense
     *
     * @param  \Illuminate\Http\Request  $request
     * @return object
     */
    public function exportExpense(Request $request)
    {
        try 
        {
            $filter_by_date = $request->filter_by_date;
            $filter_by_category = $request->filter_by_category;

            return Excel::download(new ExpenseExport($filter_by_date, $filter_by_category), 'expense-report.csv');
        }
        catch (Exception $e)
        {
            Log::info('Something Wrong In Exporting CSV!!'.' ('. $e->getmessage().')');
        }
    }
}
