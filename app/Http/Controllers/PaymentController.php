<?php

namespace App\Http\Controllers;

use App\Payment;
use Illuminate\Http\Request;
use Paystack;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
      $request->validate([
        'amount'                => 'required|numeric',
        'email'                 => 'email',
      ]);

      // \Request::instance()->query->set('trxref', $request->trxref);
      $amount           = $request->amount;
      $type             = $request->type;
      $email            = $request->email;
      $user             = $request->user('api');

      if (true) {
        $data             = ["amount" => $amount, "email" => $user ? $user->email : $email];
        $response         = Paystack::getAuthorizationResponse($data);

        return Payment::create([
          'amount'                => $amount,
          'access_code'           => $response['data']['access_code'],
          'reference'             => $response['data']['reference'],
        ]);
      }
    }

    public function verify(Request $request)
    {
      $statusText = 'abandoned'; //for dev purpose

      $paymentDetails   = Paystack::getPaymentData()['data'];

      $payment          = Payment::where('reference', $paymentDetails['reference'])->first();
      if ($payment && $payment->status == 'pending') {
        $user           = $payment->user;
        if ($paymentDetails['status'] != $statusText) {
          $payment->update([
            'status' => $paymentDetails['status'],
          ]);
        }

        if ($paymentDetails['status'] == $statusText) {
          $payment->updatePay($paymentDetails);
        }

        return ['status' => true, 'payment' => $payment];
      }
      return ['status' => false, 'payment' => $payment];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
