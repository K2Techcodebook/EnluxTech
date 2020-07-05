<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction;

class Payment extends Model
{
  protected $fillable = ['access_code', 'reference', 'amount', 'status', 'message', 'authorization_code', 'currency_code', 'paid_at'];

  public function transaction(){
    return $this->hasOne(Transaction::class);
  }
}
