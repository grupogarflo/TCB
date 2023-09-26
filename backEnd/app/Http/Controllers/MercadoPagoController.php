<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PaymentClient;



class MercadoPagoController extends Controller
{
    //


    public function getPreferenceId(Request $request){
        // dd(env("MERCADO_PAGO_ACCESS_TOKEN"));

        //dump($request);


        \MercadoPago\SDK::setAccessToken(env("MERCADO_PAGO_ACCESS_TOKEN"));

        $preference = new \MercadoPago\Preference();

        // Crear un elemento en la preferencia
        $item = new \MercadoPago\Item();
        $item->title = 'Tour: '.$request->tourData['name'];
        $item->description = 'Tour: '.$request->tourData['name'];
        $item->quantity = 1;
        $item->unit_price = $request->total;
        $item->currency_id = 'MXN';
        $preference->external_reference = $request->clientId;

        // el $preference->purpose = 'wallet_purchase'; solo permite pagos registrados
        // para permitir pagos de guests, puede omitir esta propiedad
        // $preference->purpose = 'wallet_purchase';

        $preference->back_urls = array(
            "success" => "http://localhost:3000/mercado-pago-success",
            "failure" => "http://localhost:3000/mercado-pago-failure",
            "pending" => "http://localhost:3000/mercado-pago-pending"
        );
        $preference->auto_return = "approved";
        $preference->items = array($item);
        $preference->save();

        // dump($preference);

        $client = PaymentClient ::find($request->clientId);





        return  response()->json(['token_id'=>$preference->id, 'client'=>$client]);
    }



    public function process_payment(Request $request){
        // dd($request);
        \MercadoPago\SDK::setAccessToken(env("MERCADO_PAGO_ACCESS_TOKEN"));


        if($request->method ==='credit_card'){
            $payment = new \MercadoPago\Payment();
            $payment->transaction_amount = $request->formData['transaction_amount'];
            $payment->token = $request->formData['token'];
            $payment->installments = $request->formData['installments'];
            $payment->payment_method_id = $request->formData['payment_method_id'];
            $payment->issuer_id = $request->formData['issuer_id'];
            $payer = new \MercadoPago\Payer();
            $payer->email = $request->formData['payer']['email'];
            /*$payer->identification = array(
                "type" => $request->formData['payer']['identification']['type'],
                "number" => $request->formData['payer']['identification']['number']
            );*/
            $payment->payer = $payer;
            $payment->save();


            return response()->json([
                'status' => $payment->status,
                'status_detail' => $payment->status_detail,
                'id' => $payment->id,
                'details'=>$payment->transaction_details
            ]);
        }
        if($request->method ==='debit_card'){
            $payment = new \MercadoPago\Payment();
            $payment->transaction_amount = $request->formData['transaction_amount'];
            $payment->token = $request->formData['token'];
            $payment->installments = $request->formData['installments'];
            $payment->payment_method_id = $request->formData['payment_method_id'];
            $payment->issuer_id = $request->formData['issuer_id'];
            $payer = new \MercadoPago\Payer();
            $payer->email = $request->formData['payer']['email'];
            $payer->identification = array(
                "type" => $request->formData['payer']['identification']['type'],
                "number" => $request->formData['payer']['identification']['number']
            );
            $payment->payer = $payer;
            $payment->save();


            return response()->json([
                'status' => $payment->status,
                'status_detail' => $payment->status_detail,
                'id' => $payment->id,
                'details'=>$payment->transaction_details
            ]);
        }

        if($request->method ==='ticket'){

            $payment = new \MercadoPago\Payment();
            $payment->transaction_amount =  $request->formData['transaction_amount'];
            $payment->description = 'Tour: '.$request->tour['name'];
            $payment->payment_method_id = $request->formData['payment_method_id'];
            $payment->payer = array(
                "first_name" => $request->formData['payer']['first_name'],
                "last_name" => $request->formData['payer']['last_name'],
                "email" => $request->formData['payer']['email'],
              );
           $payment->metadata = array(
                "payment_point" => $request->formData['metadata']['payment_point'],
              );

            $payment->save();

            return response()->json([
                'status' => $payment->status,
                'status_detail' => $payment->status_detail,
                'id' => $payment->id,
                'details'=>$payment->transaction_details
            ]);
        }
    }
}
