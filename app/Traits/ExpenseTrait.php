<?php

namespace App\Traits;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait ExpenseTrait
{
    //get all expense list and filter function
    public function getAllExpenses($string, $array)
    {
        $expenses = DB::table('expenses')
        ->join('accounting_categories', 'expenses.acc_cat_code', '=', 'accounting_categories.code')
        ->where('accounting_categories.name', 'LIKE','%'.$string.'%')
        ->select(
            'expenses.*',
            'accounting_categories.name as accounting_category_name',
            'total_price'
        )
        ->orderBy('expenses.id','desc');
        //if date range array exists
        if ($array) {
            $date_range = $this->datePickerFunction($array);
            $expenses -> whereBetween('date', [$date_range]);
        }
        return $expenses->get();
    }

    //expense list summary and filter function
    public function expenseToExport($string, $array)
    {
        DB::statement(DB::raw("SET @row = '0'"));
        $expenses = DB::table('expenses')
        ->join('accounting_categories', 'accounting_categories.code','=','expenses.acc_cat_code')
        ->where('accounting_categories.name', 'LIKE','%'.$string.'%')
        ->select(
            DB::raw("@row:=@row+1 AS No"),
            'accounting_categories.name as accounting_categories_name',
            DB::raw(
                'COUNT(accounting_categories.code) as count_acc_cat_code'
            ),
            DB::raw(
                'SUM(expenses.total_price) as sum_total_price'
            ),
        )
        ->groupby('accounting_categories.code');

        //if date range array exists
        if ($array) {
            $date_range = $this->datePickerFunction($array);
            $expenses -> whereBetween('date', [$date_range]);
        }
        return $expenses->get();
    }

    //explode date range for filtering function
    public function datePickerFunction($array) {
        $dateRange = explode("to",$array);
        $startDate = $dateRange[0];
        if (array_key_exists(1, $dateRange)) {
            $endDate =  $dateRange[1];
        } else {
            $endDate = $startDate;
        }
        return [$startDate, $endDate];
    }
}