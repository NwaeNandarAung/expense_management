<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccountingCategory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class AccountingCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        try
        {
            return AccountingCategory::all();
        }
        catch (Exception $e)
        {
            Log::info('Something Wrong In Expense Show Function!!'.' ('. $e->getmessage().')');
        }
    }
}
