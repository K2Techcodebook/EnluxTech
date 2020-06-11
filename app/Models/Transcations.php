<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transcations extends Model
{

  protected $fillable = ['response_description', 'product_name','transactionId','requestId','type','amout','phone','quantity','transaction_date'];


}
