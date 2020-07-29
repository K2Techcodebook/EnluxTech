<?php

namespace App\Http\Controllers;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Vtpass;
use App\Payment;

class DataSubscriptionController extends Controller
{
  public function purchase(Request $request)
  {
    $request->validate([
	    // "variation_code"  => "required|in:mtn-100mb-1000",
      // 'serviceID'       => 'required|in:mtn-data', //integer e.g mtn,airtel
      'amount'          => 'required|regex:/^\d+(\.\d{1,2})?$/', // integer
      'recepient'       => 'required', //integer
      'trxref'          => 'required',
      'phone'           => 'required',
    ]);

    $serviceID      = $request->serviceID;
    $variation_code = $request->variation_code;
    $amount         = $request->amount;
    $recepient      = $request->recepient;
    $request_id     = Str::random();
    $trxref         = $request->trxref;
    $phone          = $request->phone;

    $payment        = Payment::whereReference($trxref)->first();

    $this->authorize('use', [Payment::class, $payment, $amount]);

    $res = Vtpass::purchase([
      'serviceID'       => $serviceID,
      'amount'          => $amount,
      'billersCode'     => $recepient,
      'variation_code'  => $variation_code,
      'request_id'      => $request_id,
      'phone'           => $phone,
    ]);

    $user = $request->user('api');

    return Transaction::addNew($res, $payment->id, $user);
  }

  public function variations(Request $request)
  {
    $request->validate([
      'serviceID'       => 'required|in:mtn-data', //integer e.g mtn,airtel
    ]);

    return Vtpass::variations([
      'serviceID'       => $request->serviceID,
    ]);
  }

  public function get_variation_codes(Request $request)
  {
    $username = "enluxtech@gmail.com"; //email address(sandbox@vtpass.com)
    $password = "Comkid@1"; //password (sandbox)
    $host = 'https://sandbox.vtpass.com/api/service-variations?serviceID='.$request->serviceID;
    $curl       = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => $host,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_USERPWD => $username.":" .$password,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
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

    public function status(Request $request)
    {
      $username = "enluxtech@gmail.com"; //email address(sandbox@vtpass.com)
      $password = "Comkid@1"; //password (sandbox)
      $host = 'https://sandbox.vtpass.com/api/requery';
      $data = array(
        	'request_id' => $request->request_id // unique for every transaction from your platform
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


      public function verify_smile_number(Request $request)
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


        public function verify_smile_email(Request $request)
        {
          $username = "enluxtech@gmail.com"; //email address(sandbox@vtpass.com)
          $password = "Comkid@1"; //password (sandbox)
          $host = 'https://sandbox.vtpass.com/api/merchant-verify/smile/email';
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


          public function verify_smile_phone(Request $request)
          {
            $username = "enluxtech@gmail.com"; //email address(sandbox@vtpass.com)
            $password = "Comkid@1"; //password (sandbox)
            $host = 'https://sandbox.vtpass.com/api/merchant-verify/smile/phone';
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


    public function mtn_data_vtu_api(Request $request)
    {
      $username = "enluxtech@gmail.com"; //email address(sandbox@vtpass.com)
      $password = "Comkid@1"; //password (sandbox)
      $host = 'http://sandbox.vtpass.com/api/pay';
      $data = array(
        	'serviceID'=> $request->serviceID, //integer e.g mtn,airtel
          'billersCode'=> '', // e.g smartcardNumber, meterNumber,
        	'variation_code'=> '', // e.g dstv1, dstv2,prepaid,(optional for somes services)
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


      public function smile_data_vtu_api(Request $request)
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
    //     $customer =  Transaction::create(array(
    // 'response_description' =>$response['response_description'],
    //  'product_name' =>$response['content']['transactions']['product_name'],
    //  'transactionId' => $response['content']['transactions']['transactionId'],
    //  'requestId' =>$response['requestId'],
    //   'type' => $response['content']['transactions']['type'],
    //   'amout' =>$response['amount'],
    //   'quantity'  => $response['content']['transactions']['quantity'],
    //   'phone'  => $response['content']['transactions']['unique_element'],
    //   'transaction_date'   => $response['transaction_date']['date'],
    //
    //      ));
        return $response;
      }


        }

      public function glo_data_vtu_api(Request $request)
      {
        $username = "enluxtech@gmail.com"; //email address(sandbox@vtpass.com)
        $password = "Comkid@1"; //password (sandbox)
        $host = 'http://sandbox.vtpass.com/api/pay';
        $data = array(
          'serviceID'=> $request->serviceID, //integer e.g mtn,airtel
          'billersCode'=> '', // e.g smartcardNumber, meterNumber,
          'variation_code'=> '', // e.g dstv1, dstv2,prepaid,(optional for somes services)
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

        public function airtel_data_vtu_api(Request $request)
        {
          $username = "enluxtech@gmail.com"; //email address(sandbox@vtpass.com)
          $password = "Comkid@1"; //password (sandbox)
          $host = 'http://sandbox.vtpass.com/api/pay';
          $data = array(
            'serviceID'=> $request->serviceID, //integer e.g mtn,airtel
            'billersCode'=> '', // e.g smartcardNumber, meterNumber,
            'variation_code'=> '', // e.g dstv1, dstv2,prepaid,(optional for somes services)
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
        //  $response = curl_exec($curl);
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

        public function etisalat_data_vtu_api(Request $request)
        {
          $username = "enluxtech@gmail.com"; //email address(sandbox@vtpass.com)
          $password = "Comkid@1"; //password (sandbox)
          $host = 'http://sandbox.vtpass.com/api/pay';
          $data = array(
            'serviceID'=> $request->serviceID, //integer e.g mtn,airtel
            'billersCode'=> '', // e.g smartcardNumber, meterNumber,
          	'variation_code'=> '', // e.g dstv1, dstv2,prepaid,(optional for somes services)
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
        //  $response = curl_exec($curl);
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
