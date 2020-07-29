<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Transaction extends Model
{
  protected $fillable = ['payment_id', 'user_id', 'response_description', 'product_name','transactionId','requestId','type','amout','phone','quantity','transaction_date'];

  public static function addNew($res, $payment_id, $user = null)
  {
    if ($res['code'] == '000') {
      self::create([
        'user_id'              => $user ? $user->id : null,
        'payment_id'           => $payment_id,
        'response_description' => $res['response_description'],
        'product_name'         => $res['content']['transactions']['product_name'],
        'transactionId'        => $res['content']['transactions']['transactionId'],
        'requestId'            => $res['requestId'],
        'type'                 => $res['content']['transactions']['type'],
        'amout'                => $res['amount'],
        'quantity'             => $res['content']['transactions']['quantity'],
        'phone'                => $res['content']['transactions']['unique_element'],
        'transaction_date'     => $res['transaction_date']['date']
      ]);
      return $res;
    } else {
      return $res;
    }
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
