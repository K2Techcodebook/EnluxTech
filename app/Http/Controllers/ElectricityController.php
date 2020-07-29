<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Myckhel\Vtpass\Support\Electric;
use VtPass;
use App\Models\Transaction;
use App\Payment;

class ElectricityController extends Controller
{
  public function verify(Request $request)
  {
    $request->validate([
      'serviceID' => 'required|in:eko-electric,ikeja-electric,kano-electric,portharcourt-electric,jos-electric,ibadan-electric,kaduna-electric',
      'type'      => 'required|in:postpaid,prepaid',
      'meter'     => 'required|int:5,20',
    ]);

    $serviceID      = $request->serviceID;
    $type           = $request->type;
    $meter          = $request->meter;

    return Electric::verify([
      'serviceID'   => $serviceID,
      'billersCode' => $meter,
      'type'        => $type,
    ]);
  }

  public function purchase(Request $request)
  {
    $request->validate([
      'serviceID' => 'required|in:eko-electric,ikeja-electric,kano-electric,portharcourt-electric,jos-electric,ibadan-electric,kaduna-electric',
      'type'      => 'required|in:prepaid,postpaid',
      'meter'     => 'required|int:5,20',
      'amount'    => 'required|required|regex:/^\d+(\.\d{1,2})?$/',
      'phone'     => 'required|int:11,15',
      'trxref'    => 'required',
    ]);

    $serviceID      = $request->serviceID;
    $type           = $request->type;
    $meter          = $request->meter;
    $amount         = $request->amount;
    $phone          = $request->phone;
    $trxref         = $request->trxref;

    $payment        = Payment::whereReference($trxref)->first();

    $this->authorize('use', [Payment::class, $payment, $amount]);

    $res = Electric::purchase([
      'serviceID'       => $serviceID,
      'billersCode'     => $meter,
      'variation_code'  => $type,
      'request_id'      => Str::random(),
      'amount'          => $amount,
      'phone'           => $phone,
    ]);

    $user = $request->user('api');

    if ($res['code'] == '000') {
      Transaction::addNew($res, $payment->id, $user);
       return $res;
    } else {
      return $res;
    }
  }

  public function status(Request $request)
  {
    $request->validate([
      'request_id' => 'required',
    ]);

    $request_id = $request->request_id;

    return Electric::status([
      'request_id'      => $request_id,
    ]);
  }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
