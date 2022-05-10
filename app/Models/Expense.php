<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable=[
        'date','acc_cat_code','name','total_price'
    ];
}
