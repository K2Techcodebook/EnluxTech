<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Transaction extends Model
{
  protected $fillable = ['user_id', 'response_description', 'product_name','transactionId','requestId','type','amout','phone','quantity','transaction_date'];

  public static function addNew($res, $user = null)
  {
    return self::create([
      'user_id'              => $user ? $user->id : null,
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
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
