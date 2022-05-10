<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Facades\DB;
use App\Traits\ExpenseTrait;

class ExpenseExport implements FromCollection, WithHeadings, WithEvents
{
    use ExpenseTrait;

    protected $filter_by_date, $filter_by_category;
    

    public function __construct($filter_by_date, $filter_by_category)
    {
        $this->filter_by_date = $filter_by_date;
        $this->filter_by_category = $filter_by_category;
    }

    public function headings(): array
    {
        return [
            'No',
            'Accounting Category',
            'Count of Accounting Category',
            'Total Amount',
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->expenseToExport($this->filter_by_category, $this->filter_by_date);
    }

    public function registerEvents(): array {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $cellRange = 'A1:D1'; // All headers

                $expenses = $this->expenseToExport($this->filter_by_category, $this->filter_by_date);

                $total_amount = $expenses ->SUM("sum_total_price");

                $event->sheet->appendRows(array(
                    array('', '', 'Total Expense', $total_amount),
                ), $event);
                $event->sheet->getDelegate()->getStyle($cellRange);
            },
        ];
    }
}
