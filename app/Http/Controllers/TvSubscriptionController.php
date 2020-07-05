<?php

namespace App\Http\Controllers;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TvSubscriptionController extends Controller
{

  public function verify_gotv_card_number(Request $request)
  {
    $username = "enluxtech@gmail.com"; //email address(sandbox@vtpass.com)
    $password = "Comkid@1"; //password (sandbox)
    $host = 'https://sandbox.vtpass.com/api/merchant-verify';
    $data = array(
      'serviceID'=> $request->serviceID, //integer e.g gotv,dstv,eko-electric,abuja-electric
      'billersCode'=> $request->billersCode, // e.g smartcardNumber, meterNumber,
    );
    $curl       = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => $host,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_USERPWD => $username.":" .$password,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $data,
    ));
    $response = json_decode(curl_exec( $curl ),true);
  $err = curl_error($curl);

  curl_close($curl);

  if ($err) {
    return response()->json([
   'code' => '404',
   'data'  => "cURL Error #:" . $err,
], 404);
  } else {

    return $response;
  }


    }
    public function verify_startime_card_number(Request $request)
    {
      $username = "enluxtech@gmail.com"; //email address(sandbox@vtpass.com)
      $password = "Comkid@1"; //password (sandbox)
      $host = 'https://sandbox.vtpass.com/api/merchant-verify';
      $data = array(
        'serviceID'=> $request->serviceID, //integer e.g gotv,dstv,eko-electric,abuja-electric
        'billersCode'=> $request->billersCode, // e.g smartcardNumber, meterNumber,
      );
      $curl       = curl_init();
      curl_setopt_array($curl, array(
      CURLOPT_URL => $host,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_USERPWD => $username.":" .$password,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $data,
      ));
      $response = json_decode(curl_exec( $curl ),true);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      return response()->json([
     'code' => '404',
     'data'  => "cURL Error #:" . $err,
  ], 404);
    } else {

      return $response;
    }


      }


  public function verify_dstv_card_number(Request $request)
  {
    $username = "enluxtech@gmail.com"; //email address(sandbox@vtpass.com)
    $password = "Comkid@1"; //password (sandbox)
    $host = 'https://sandbox.vtpass.com/api/merchant-verify';
    $data = array(
      'serviceID'=> $request->serviceID, //integer e.g gotv,dstv,eko-electric,abuja-electric
      'billersCode'=> $request->billersCode, // e.g smartcardNumber, meterNumber,
    );
    $curl       = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => $host,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_USERPWD => $username.":" .$password,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $data,
    ));
    $response = json_decode(curl_exec( $curl ),true);
  $err = curl_error($curl);

  curl_close($curl);

  if ($err) {
    return response()->json([
   'code' => '404',
   'data'  => "cURL Error #:" . $err,
], 404);
  } else {

    return $response;
  }


    }

    public function startime_sub_vtu_api(Request $request)
    {
      $username = "enluxtech@gmail.com"; //email address(sandbox@vtpass.com)
      $password = "Comkid@1"; //password (sandbox)
      $host = 'http://sandbox.vtpass.com/api/pay';
      $data = array(
        'serviceID'=> $request->serviceID, //integer e.g mtn,airtel
        'billersCode'=> $request->billersCode, // e.g smartcardNumber, meterNumber,
        'variation_code'=> $request->variation_code, // e.g dstv1, dstv2,prepaid,(optional for somes services)
        'amount' => $request->amount, // integer
        'phone' => $request->recepient, //integer
        'variation_code'=>$request->variation_code,
        'request_id' => rand(100,9999) // unique for every transaction from your platform
      );
      $curl       = curl_init();
      curl_setopt_array($curl, array(
      CURLOPT_URL => $host,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_USERPWD => $username.":" .$password,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $data,
      ));
      $response = json_decode(curl_exec( $curl ),true);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      return response()->json([
     'code' => '404',
     'data'  => "cURL Error #:" . $err,
  ], 404);
    } else {
      $customer =  Transaction::create(array(
  'response_description' =>$response['response_description'],
   'product_name' =>$response['content']['transactions']['product_name'],
   'transactionId' => $response['content']['transactions']['transactionId'],
   'requestId' =>$response['requestId'],
    'type' => $response['content']['transactions']['type'],
    'amout' =>$response['amount'],
    'quantity'  => $response['content']['transactions']['quantity'],
    'phone'  => $response['content']['transactions']['unique_element'],
    'transaction_date'   => $response['transaction_date']['date'],

       ));
      return $response;
    }


      }


    public function dstv_sub_vtu_api(Request $request)
    {
      $username = "enluxtech@gmail.com"; //email address(sandbox@vtpass.com)
      $password = "Comkid@1"; //password (sandbox)
      $host = 'http://sandbox.vtpass.com/api/pay';
      $data = array(
        'serviceID'=> $request->serviceID, //integer e.g mtn,airtel
        'billersCode'=> $request->billersCode, // e.g smartcardNumber, meterNumber,
        'variation_code'=> $request->variation_code, // e.g dstv1, dstv2,prepaid,(optional for somes services)
        'amount' => $request->amount, // integer
        'phone' => $request->recepient, //integer
        'variation_code'=>$request->variation_code,
        'request_id' => rand(100,9999) // unique for every transaction from your platform
      );
      $curl       = curl_init();
      curl_setopt_array($curl, array(
      CURLOPT_URL => $host,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_USERPWD => $username.":" .$password,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $data,
      ));
      $response = json_decode(curl_exec( $curl ),true);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      return response()->json([
     'code' => '404',
     'data'  => "cURL Error #:" . $err,
  ], 404);
    } else {
      $customer =  Transaction::create(array(
  'response_description' =>$response['response_description'],
   'product_name' =>$response['content']['transactions']['product_name'],
   'transactionId' => $response['content']['transactions']['transactionId'],
   'requestId' =>$response['requestId'],
    'type' => $response['content']['transactions']['type'],
    'amout' =>$response['amount'],
    'quantity'  => $response['content']['transactions']['quantity'],
    'phone'  => $response['content']['transactions']['unique_element'],
    'transaction_date'   => $response['transaction_date']['date'],

       ));
      return $response;
    }


      }

      public function gotv_sub_vtu_api(Request $request)
      {
        $username = "enluxtech@gmail.com"; //email address(sandbox@vtpass.com)
        $password = "Comkid@1"; //password (sandbox)
        $host = 'http://sandbox.vtpass.com/api/pay';
        $data = array(
          'serviceID'=> $request->serviceID, //integer e.g mtn,airtel
          'billersCode'=> $request->billersCode, // e.g smartcardNumber, meterNumber,
          'variation_code'=> $request->variation_code, // e.g dstv1, dstv2,prepaid,(optional for somes services)
          'amount' => $request->amount, // integer
          'phone' => $request->recepient, //integer
          'variation_code'=>$request->variation_code,
          'request_id' => rand(100,9999) // unique for every transaction from your platform
        );
        $curl       = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $host,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_USERPWD => $username.":" .$password,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $data,
        ));
        $response = json_decode(curl_exec( $curl ),true);
      $err = curl_error($curl);

      curl_close($curl);

      if ($err) {
        return response()->json([
       'code' => '404',
       'data'  => "cURL Error #:" . $err,
    ], 404);
      } else {
        $customer =  Transaction::create(array(
    'response_description' =>$response['response_description'],
     'product_name' =>$response['content']['transactions']['product_name'],
     'transactionId' => $response['content']['transactions']['transactionId'],
     'requestId' =>$response['requestId'],
      'type' => $response['content']['transactions']['type'],
      'amout' =>$response['amount'],
      'quantity'  => $response['content']['transactions']['quantity'],
      'phone'  => $response['content']['transactions']['unique_element'],
      'transaction_date'   => $response['transaction_date']['date'],

         ));
        return $response;
      }


        }



}
