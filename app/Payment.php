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

  public function updatePay($paymentDetails)
  {
    return $this->update([
      'status'              => 'success',// dev purpose $paymentDetails['status'],
      'message'             => $paymentDetails['message'],
      'reference'           => $paymentDetails['reference'],
      'authorization_code'  => $paymentDetails['authorization'] ? $paymentDetails['authorization']['authorization_code'] : null,
      'currency_code'       => $paymentDetails['currency'],
      'paid_at'             => now(),//$paymentDetails['data']['paidAt'],
    ]);
  }
}
