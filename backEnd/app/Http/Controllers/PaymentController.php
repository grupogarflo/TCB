<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PaymentCreateRequest;
use App\PaymentClient;
use App\PaymentTour;
use App\Payment;
use Response;
use Storage;
use App\Mail\ConfirmationSend;
use App\Mail\preBookEmail;
use App\Mail\emailResponsePay;
use App\Mail\BankTransferSend;
use App\Mail\MailSend;
use App\Mail\MailRefund;
use App\Mail\emailContacto;
use App\Mail\EventUrlBook;

use Illuminate\Support\Facades\Mail;

use function GuzzleHttp\json_decode;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    // Declarar la variable global
    protected $urlApiPlatta;

    public function __construct()
    {
        // Inicializar la variable en el constructor
        // Desarrollo: https://qa.thetouragency.com/api/
        // Produccion: https://tta-api.thetouragency.com/tta/

        $this->urlApiPlatta = 'https://tta-api.thetouragency.com/tta/'; // Puedes asignar el valor que desees
    }

    //para crear el registro del pago
    function addPayment(PaymentCreateRequest $request)
    {
        //ingreso el registro del cliente
        $hotel
            = ($request->hotel == "") ? 'n/a' : $request->hotel;
        $cliente = new PaymentClient();
        $cliente->name = $request->name;
        $cliente->email = $request->email;
        $cliente->phone = $request->phone;
        $cliente->code_book = $request->code_book;
        $cliente->site_book = $request->site_book;
        $cliente->language = $request->language;
        $cliente->status = $request->status;
        $cliente->hotel = $hotel;
        $cliente->country = $request->country['name'] ?? null;
        $cliente->state = $request->state['name'] ?? null;
        $cliente->city = $request->city ?? null;


        $cliente->save();

        //ultimo id insertado
        $clienteInsert = $cliente->id;

        //ingreso el registro de los tours
        $date = date_create($request->toursInfo["date"]);

        $discount = 0;

        //$totals = ($request->language==='ing') ? $request->toursInfo["total_usd"] : $request->toursInfo["total_mxn"];
        if (!empty($request->toursInfo["promocode"])) {
            $discount = ($request->language === 'ing') ? $request->toursInfo["promocode"]['discount'] : $request->toursInfo["promocode"]['discount_mxn'];
        }

        $tour = new PaymentTour();
        $tour->tours_id = $request->toursInfo["id"];
        $tour->date = date_format($date, "Y/m/d H:i:s");
        $tour->adult = $request->toursInfo["adultos"];
        $tour->child = $request->toursInfo["ninos"];
        $tour->promocode = (empty($request->toursInfo["promocode"])) ? 'n/a' : $request->toursInfo["promocode"]['promocode'];
        $tour->discount = $discount;
        $tour->payment_clients_id = $clienteInsert;
        $tour->save();

        /*
        foreach (json_decode($request->toursInfo)
            as  $value) {
            $date = date_create($value->date);
            //date_format($date, "Y/m/d H:i:s");

            $tour = new PaymentTour();
            $tour->tours_id = $value->tours_id;
            $tour->date = date_format($date, "Y/m/d H:i:s");
            $tour->adult = $value->adult;
            $tour->child = $value->child;
            $tour->promocode = $value->promocode;
            $tour->discount = $value->discount;
            $tour->payment_clients_id = $clienteInsert;
            $tour->save();
        }
        */

        //ingreso el registro del pago
        $pago = new Payment();
        $pago->payment_clients_id = $clienteInsert;
        $pago->total = $request->amount;
        $pago->currency = $request->currency;
        $pago->authorization = $request->status;
        $pago->status = $request->status;
        $pago->merch = $request->merch;
        $pago->save();

        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => [
                'clientId' => $clienteInsert
            ]
        ], 200);
    }

    //se manda a realizar el proceso de compra
    function paymentProcess(Request $request)
    {
        //$response = Http::post('http://loreavila.com/paymentApi/api/payment', [
        $response = Http::post('https://www.rivieratt.com/paymentApi/api/payment', [
            "card_no"        => str_replace(' ', '', $request->card_no),
            "expiry_mounth" => $request->expiry_mounth,
            "expiry_year" => $request->expiry_year,
            "cvc" => $request->cvc,
            "name" => $request->name,
            "email" => $request->email,
            "uniqueId" => $request->uniqueId,
            "currency" => $request->currency,
            "descriptionItem" => $request->descriptionItem,
            // "amount" => $request->amount,
            "amount" => number_format($request->amount, 2, '.', ''),
            "paymentMethodType" => $request->paymentMethodType
        ]);

        //dump($request);
        //dd($response);
        // send email for payment information
        $this->emailResponsePay($response, $request->name, $request->email, $request->uniqueId, $request->descriptionItem, number_format($request->amount, 2, '.', ''), $request->currency);
        return $response;
    }

    //para actualizar el registro del pago
    function updatePayment(PaymentCreateRequest $request)
    {

        //actualiza  el registro del cliente
        PaymentClient::where('id', $request->clientId)
            ->update([
                "status" => $request->status,


            ]);


        Payment::where('payment_clients_id', $request->clientId)
            ->update([
                "status" => $request->status,
                "authorization" => $request->authorization,
                "merch" => $request->merch,
            ]);

        //se manda el email de confirmacion
        if ($request->status === "complet" || $request->status === "approved" || $request->status === "Check in payment") {
            return $this->sendEmail($request->clientId, $request->idioma);
        }
        /*
        return response()->json([
            'code' => 200,
            'message' => 'success'
        ], 200);*/
    }

    //recupera los datos de la reserva ya hecha
    function getDataPayment(Request $request)
    {
        /*
        $res = PaymentClient::select(
            "payment_clients.*",
            "payments.*"
        )
            ->join('payments', 'payments.payment_clients_id', '=', 'payment_clients.id')
            ->where('payment_clients.status', 'complet')
            ->where('payments.status', 'complet')
            ->where('payment_clients.id', $request->id)
            ->get();

        if (count($res) > 0) {

            $tours = PaymentTour::select(
                "*"
            )
                ->where('payment_clients_id', $res[0]->id)
                ->get();

            $arr = [];
            if (count($tours) > 0) {

                foreach ($tours as $value) {
                    $arr[] = [
                        "id" => $value->id,
                        "tours_id" => $value->,
                        "date" => $value->date,
                        "adult" => $value->adult,
                        "child" => $value->child,
                        "promocode" => $value->promocode,
                        "discount" => $value->discount,
                    ];
                }
            }
            */

        $res = PaymentClient::select(
            "payment_clients.*",
            "payments.*",
            "payment_tours.*",
            "tour_contents.name as nameTour",
            "tour_contents.duration",
            "tour_contents.img"
        )
            ->join('payments', 'payments.payment_clients_id', '=', 'payment_clients.id')
            ->join('payment_tours', 'payment_tours.payment_clients_id', '=', 'payment_clients.id')
            ->join('tour_contents', 'payment_tours.tours_id', '=', 'tour_contents.tour_id')
            ->where('payment_clients.id', $request->id)
            ->where('tour_contents.language_id', $request->idioma)

            ->get();

        if (count($res) > 0) {
            return response()->json([
                'code' => 200,
                'message' => '',
                'data' => $res
            ], 200);
        } else {
            return response()->json([
                'code' => 400,
                'message' => 'No data found whith code book => ' . $request->codeBook,
                'data' => ''
            ], 400);
        }
    }

    //envia el correo de confirmacion
    function sendEmail($id, $idioma)
    {

        DB::enableQueryLog();
        $res = PaymentClient::select(
            "payment_clients.*",
            "payments.*",
            "payment_tours.*",
            "tour_contents.name as nameTour",
            "tour_contents.duration"
        )
            ->join('payments', 'payments.payment_clients_id', '=', 'payment_clients.id')
            ->join('payment_tours', 'payment_tours.payment_clients_id', '=', 'payment_clients.id')
            ->join('tour_contents', 'payment_tours.tours_id', '=', 'tour_contents.tour_id')
            ->where('payment_clients.id', $id)
            ->where('tour_contents.language_id', $idioma)
            ->get();
        \Log::info('query ', [DB::getQueryLog()]);

        $details = [

            'client' => $res[0]->name,
            'email' => $res[0]->email,
            'phone' => $res[0]->phone,
            'codeBook' => $res[0]->code_book,
            'siteBook' => $res[0]->site_book,
            'language' => $res[0]->language,
            'status' => $res[0]->status,
            'hotel' => $res[0]->hotel,
            // 'total' => $res[0]->total,
            'total' => number_format($res[0]->total, 2, '.', ''),
            'currency' => $res[0]->currency,
            'authorization' => $res[0]->authorization,
            'nameTour' => $res[0]->nameTour,
            'duration' => $res[0]->duration,
            'date' => $res[0]->date,
            'adults' => $res[0]->adult,
            'child' => $res[0]->child,
            'promocode' => $res[0]->promocode,
            'discount' => $res[0]->discount,
            'country' => $res[0]->country,
            'state' => $res[0]->state,
            'city' => $res[0]->city
        ];

        Mail::to($res[0]->email)->send(new ConfirmationSend($details));
        Mail::to("aperez@grupogarflo.com")->send(new ConfirmationSend($details));
        //Mail::to("lavila@grupogarflo.com")->send(new ConfirmationSend($details));
        Mail::to("websales@cancunbay.com")->send(new ConfirmationSend($details));
        Mail::to("resellers@contactcentermexico.com")->send(new ConfirmationSend($details));
        Mail::to("dcastaneda@grupogarflo.com")->send(new ConfirmationSend($details));
    }


    function reSendEmail(Request $request)
    {
        return $this->sendEmail($request->id, $request->idioma);
    }

    function preBookEmail(Request $request)
    {

        $totalPromocode = null;

        $totals = ($request->language === 'ing') ? $request->toursInfo["total_usd"] : $request->toursInfo["total_mxn"];
        if (!empty($request->toursInfo["promocode"])) {
            $totalPromocode = ($request->language === 'ing') ? $request->toursInfo["promocode"]['data_usd'] : $request->toursInfo["promocode"]['data_mxn'];
        }

        $details = [

            'client' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'language' => $request->language,
            // 'total' => $request->toursInfo["total"],
            'total' => number_format($totals, 2, '.', ''),
            'currency' => $request->currency,
            'nameTour' => $request->toursInfo["name"],
            'date' => $request->toursInfo["date"],
            'adults' => $request->toursInfo["adultos"],
            'child' => $request->toursInfo["ninos"],
            'promocode' => $request->toursInfo["promocode"]['promocode'] ?? '',
            'total_promocode' => $totalPromocode,
            'hotel' => $request->hotel,
            'country' => $request->country['name'] ?? '',
            'state' => $request->state['name'] ?? '',
            'city' => $request->city ?? '',
            'eventUrlBook' => $request->eventUrlBook ?? ''
        ];

        /*Mail::to(env('MAIL_FROM_ADDRESS'))->send(new preBookEmail($details));

        */
        //Mail::to("lavila@grupogarflo.com")->send(new preBookEmail($details));
        Mail::to("websales@cancunbay.com")->send(new preBookEmail($details));
        Mail::to('aperez@grupogarflo.com')->send(new preBookEmail($details));
        Mail::to('resellers@contactcentermexico.com')->send(new preBookEmail($details));
        Mail::to('dcastaneda@grupogarflo.com')->send(new preBookEmail($details));
    }


    function emailContacto(Request $request)
    {
        $details = [

            'client' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'language' => $request->language,
            'message' => $request->message,


        ];
        Mail::to("websales@cancunbay.com")->send(new emailContacto($details));
        //Mail::to("lavila@grupogarflo.com")->send(new emailContacto($details));
        Mail::to("aperez@grupogarflo.com")->send(new emailContacto($details));
        Mail::to("resellers@contactcentermexico.com")->send(new emailContacto($details));

        // check for failures
        if (count(Mail::failures()) > 0) {

            echo "There was one or more failures. They were: <br />";

            foreach (Mail::failures() as $email_address) {
                echo " - $email_address <br />";
            }
        } else {
            return response()->json([
                'code' => 200,
                'data' => "ok"
            ], 200);
        }
    }


    function emailResponsePay($info, $name, $email, $uniqueId, $descriptionItem, $amount, $currency)
    {
        $code = "";
        $param = "";
        $message = "";

        \Log::info('data ', [$info, $name, $email, $uniqueId, $descriptionItem, $amount, $currency]);

        if (isset($info["code"])) {
            $code = $info["code"];
        }
        if (isset($info["param"])) {
            $param = $info["param"];
        }
        if (isset($info["message"])) {
            $message = $info["message"];
        }
        //dd($info['status']);
        $details = [
            'status' => $info["status"],
            'type' => $info["type"],
            'code' => $code,
            'param' => $param,
            'message' => $message,
            'language' => 'ing',
            'name' => $name,
            'email' => $email,
            'uniqueId' => $uniqueId,
            'descriptionItem' => $descriptionItem,
            'amount' => $amount . ' ' . $currency,
        ];

        Mail::to("aperez@grupogarflo.com")->send(new emailResponsePay($details));
        // Mail::to("lavila@grupogarflo.com")->send(new emailResponsePay($details));
        Mail::to("websales@cancunbay.com")->send(new emailResponsePay($details));
        Mail::to("resellers@contactcentermexico.com")->send(new emailResponsePay($details));
    }

    function test(Request $request)
    {
        $res = PaymentClient::select(
            "payment_clients.*",
            "payments.*",
            "payment_tours.*",
            "tour_contents.name as nameTour",
            "tour_contents.duration"
        )
            ->join('payments', 'payments.payment_clients_id', '=', 'payment_clients.id')
            ->join('payment_tours', 'payment_tours.payment_clients_id', '=', 'payment_clients.id')
            ->join('tour_contents', 'payment_tours.tours_id', '=', 'tour_contents.tour_id')
            ->where('payment_clients.id', $request->id)
            ->where('tour_contents.language_id', $request->idioma)
            ->get();


        $details = [

            'client' => $res[0]->name,
            'email' => $res[0]->email,
            'phone' => $res[0]->phone,
            'codeBook' => $res[0]->code_book,
            'siteBook' => $res[0]->site_book,
            'language' => $res[0]->language,
            'status' => $res[0]->status,
            'hotel' => $res[0]->hotel,
            'total' => $res[0]->total,
            'currency' => $res[0]->currency,
            'authorization' => $res[0]->authorization,
            'nameTour' => $res[0]->nameTour,
            'duration' => $res[0]->duration,
            'date' => $res[0]->date,
            'adults' => $res[0]->adult,
            'child' => $res[0]->child,
            'promocode' => $res[0]->promocode,
            'discount' => $res[0]->discount,
        ];

        Mail::to($res[0]->email)->send(new ConfirmationSend($details));
        Mail::to("aperez@grupogarflo.com")->send(new ConfirmationSend($details));
        Mail::to("resellers@contactcentermexico.com")->send(new ConfirmationSend($details));
        //Mail::to("lavila@grupogarflo.com")->send(new BankTransferSend($details));
        //Mail::to("websales@cancuntours.com")->send(new BankTransferSend($details));
    }


    function eventUrlBook(Request $request)
    {

        $data = [

            "productId" => $request->toursInfo["tour_id"],
            // "productId" => 1,
            "travelDate" => $request->toursInfo["date"],
            "clientName" => $request->name,
            "clientLastname" => "",
            "clientPhone" => $request->phone,
            "clientEmail" => $request->email,
            "comments" => '',
            "hotelName" => $request->hotel,
            'adults' => $request->toursInfo["adultos"],
            'childs' => $request->toursInfo["ninos"],
            // 'infants' => 0,
            'currency' => $request->currency,
            "plattaCode" => $request->eventUrlBook,
            // 'aplic' => 'Cancunbay.com',
        ];

        $postFields = http_build_query($data);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->urlApiPlatta . 'tour/octo/bookingsExternalId',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $postFields,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic ' . base64_encode('cancunbay_api:11OJztUK')
            ),
        ));

        // echo "<pre>";
        // echo "URL: $urlApiPlatta\n";
        // echo "Método: $method\n";
        // echo "Datos Enviados: " . print_r($fields, true) . "\n";
        // echo "PLata id: " . print_r($idPlatta) . "\n";
        // echo "Adults: " . print_r($adults) . "\n";
        // echo "children: " . print_r($children) . "\n";
        //  echo "Datos Enviados: " . print_r($fields, true) . "\n";
        // echo "Datos Enviados: " . print_r($postFields, true) . "\n";
        // echo "</pre>";

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            echo json_encode(array('code' => 400, 'message' => 'Error en la petición: ' . curl_error($curl)));
            http_response_code(400);
            curl_close($curl);
            return;
        }

        curl_close($curl);

        $data = json_decode($response, true);

        // echo "<pre>";
        // print_r($data);
        // print_r($response);
        // echo "</pre>";

        if (json_last_error() === JSON_ERROR_NONE) {
            if (isset($data["booking"]["code"])) {

                $details = [
                    'client' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'logId' => $data["booking"]["code"],
                    'plattaCode' => $request->eventUrlBook,
                    'language' => 'ing',
                    'nameTour' => $request->toursInfo["name"],
                    'date' => $request->toursInfo["date"],
                    'adults' => $request->toursInfo["adultos"],
                    'child' => $request->toursInfo["ninos"],
                    'promocode' => $request->toursInfo["promocode"]['promocode'] ?? '',
                ];

                Mail::to("aperez@grupogarflo.com")->send(new EventUrlBook($details));
                Mail::to("mcanela@grupogarflo.com")->send(new EventUrlBook($details));
                Mail::to("lavila@grupogarflo.com")->send(new EventUrlBook($details));
                Mail::to("abarajas@cancunbay.com")->send(new EventUrlBook($details));


                return array('code' => 200, 'message' => '', 'logId' => $data["booking"]["code"]);
            } else {
                return array('code' => 400, 'message' => $data['errorMessage'], 'logId' => '');
            }
        } else {
            return array('status' => 400, 'message' => 'Respuesta no es un JSON válido: ' . json_last_error_msg());
        }
    }

    //////////////////////////////////////////
    //PRCESO DE COMPRA CON TRANSFERENCIA DE BANCO

    //se manda a realizar el proceso de compra

    function paymentTransferBank(Request $request)
    {
        //$paymentRes = Http::post('http://loreavila.com/paymentApi/api/speiPayment', [
        $paymentRes = Http::post('http://127.0.0.1:8001/api/speiPayment', [
            "email" => $request->email,
            "name" => $request->name,
            "amount" => $request->amount,
            "uniqueId" => $request->uniqueId,
            "currency" => $request->currency,
            "descriptionItem" => $request->descriptionItem,
            'bank_transfer' => 'mx_bank_account'
        ]);

        //recupera la respuesta del proceso

        // valida si durante el proceso  de creacion del pago el cliente ya tenia un saldo en stripe suficiente
        // para cubrir el total de esta operacion
        if (count($paymentRes["data"]["charges"]["data"]) > 0) {

            //echo "Pasa el pago tiene fondos";
            //print_r($paymentRes->charges);
            return response()->json([
                'status' => 200,
                'id' => $paymentRes["data"]["id"],
                'data' => ''
            ], 200);
        } else {
            // si entra al else es porque el cliente no tiene saldo en stripe para cubrir el total de la operacion , y es donde se le manda
            // el mensaje de que debe hacer una transferencia con el total a cubrir y con la info para realizarlo.

            //actualizo el campo de autorizacion con el id del pago generado por stripe para tener la referencia en el resto del proceso
            Payment::where('payment_clients_id', $request->clientidd)
                ->update([

                    "authorization" => $paymentRes["data"]["id"]
                ]);
            // se envia el correo con la informacion al cliente para que pueda realizar la transferencia

            $res = PaymentClient::select(
                "payment_clients.*",
                "payments.*",
                "payment_tours.*",
                "tour_contents.name as nameTour",
                "tour_contents.duration"
            )
                ->join('payments', 'payments.payment_clients_id', '=', 'payment_clients.id')
                ->join('payment_tours', 'payment_tours.payment_clients_id', '=', 'payment_clients.id')
                ->join('tour_contents', 'payment_tours.tours_id', '=', 'tour_contents.tour_id')
                ->where('payment_clients.id', $request->clientidd)
                ->where('tour_contents.language_id', $request->language)
                ->get();

            $details = [
                'client' => $res[0]->name,
                'email' => $res[0]->email,
                'currency' => $res[0]->currency,
                'amount' => $paymentRes["data"]["next_action"]["display_bank_transfer_instructions"]["amount_remaining"] / 100,
                'type' => $paymentRes["data"]["next_action"]["display_bank_transfer_instructions"]["financial_addresses"][0]["type"],
                'bankCode' => $paymentRes["data"]["next_action"]["display_bank_transfer_instructions"]["financial_addresses"][0]["spei"]["bank_code"],
                'bankName' => $paymentRes["data"]["next_action"]["display_bank_transfer_instructions"]["financial_addresses"][0]["spei"]["bank_name"],
                'bankClabe' => $paymentRes["data"]["next_action"]["display_bank_transfer_instructions"]["financial_addresses"][0]["spei"]["clabe"],
                'bankReference' => $paymentRes["data"]["next_action"]["display_bank_transfer_instructions"]["reference"]
            ];

            Mail::to($res[0]->email)->send(new BankTransferSend($details));
            //Mail::to("aperez@grupogarflo.com")->send(new BankTransferSend($details));
            //Mail::to("lavila@grupogarflo.com")->send(new BankTransferSend($details));
            Mail::to("websales@cancunbay.com")->send(new BankTransferSend($details));

            return response()->json([
                'status' => 200,
                'id' => $paymentRes["data"]["id"],
                'data' => [
                    'amount' => $paymentRes["data"]["next_action"]["display_bank_transfer_instructions"]["amount_remaining"],
                    'currency' => $paymentRes["data"]["next_action"]["display_bank_transfer_instructions"]["currency"],
                    'type' => $paymentRes["data"]["next_action"]["display_bank_transfer_instructions"]["financial_addresses"][0]["type"],
                    'bank_code' => $paymentRes["data"]["next_action"]["display_bank_transfer_instructions"]["financial_addresses"][0]["spei"]["bank_code"],
                    'bank_name' => $paymentRes["data"]["next_action"]["display_bank_transfer_instructions"]["financial_addresses"][0]["spei"]["bank_name"],
                    'bank_clabe' => $paymentRes["data"]["next_action"]["display_bank_transfer_instructions"]["financial_addresses"][0]["spei"]["clabe"],
                    'reference' => $paymentRes["data"]["next_action"]["display_bank_transfer_instructions"]["reference"]
                ]
            ], 200);
        }
    }


    //recupera la respuesta del hook de sripe con el pago de spei o transferencias bancarias
    /*
    function responseHook(Request $request)
    {
        /*
        switch ($request->type) {

            case 'payment_intent.requires_action':
                $paymentIntent = $request->data;
                http_response_code(200);
                break;

            case 'payment_intent.partially_funded':
                echo $paymentIntent = $request->data;
                // ... handle other event types
                http_response_code(200);
                break;

            case 'payment_intent.succeeded':
                //echo $paymentIntent = $request->data;
                //http_response_code(200);
                $this->sendEmail($id, $idioma, $);
                break;

            case 'payment_intent.canceled':
                echo $paymentIntent = $request->data;
                http_response_code(200);
                break;

            case 'payment_intent.payment_failed':
                echo $paymentIntent =
                    $request->data;
                $error_message = $paymentIntent->last_payment_error ? $paymentIntent->last_payment_error->message : "";
                printf("Failed: %s, %s", $paymentIntent->id, $error_message);
                http_response_code(200);
                break;

                // default:
                //    echo  'Received unknown event type ' . $request->type;
        }

    }
     */

    // recuperar informacion de las reservas en el cms

    function getAllReservation(Request $request)
    {
        /*
        $res = PaymentClient::select(
            "payment_clients.*",
            "payments.*"
        )
            ->join('payments', 'payments.payment_clients_id', '=', 'payment_clients.id')
            ->where('payment_clients.status', 'complet')
            ->where('payments.status', 'complet')
            ->get();
        */

        $status = "";
        if ($request->typeBook === "Approved") {
            $status = "approved";
        }
        if ($request->typeBook === "Completed") {
            $status = "complet";
        }
        if ($request->typeBook === "Cancelled") {
            $status = "cancelled";
        }
        if ($request->typeBook === "Refunded") {
            $status = "refunded";
        }
        if ($request->typeBook === "Check in payment") {
            $status = "Check in payment";
        }

        // para agregar la busqueda por fechas
        if (isset($request->start_date) && $request->start_date != "" && isset($request->end_date) && $request->end_date != "") {
            $res = PaymentClient::select(
                "payment_clients.*",
                "payments.*",
                "payment_tours.*"
            )
                ->join('payments', 'payments.payment_clients_id', '=', 'payment_clients.id')
                ->join('payment_tours', 'payment_tours.payment_clients_id', '=', 'payment_clients.id')
                ->whereRaw(
                    "(payment_clients.created_at >= ? AND payment_clients.created_at <= ?)",
                    [
                        $request->start_date . " 00:00:00",
                        $request->end_date . " 23:59:59"
                    ]
                )
                // ->whereBetween('payment_clients.created_at', [$request->start_date, $request->end_date])
                ->where('payment_clients.status', $status)
                ->where('payments.status', $status)
                ->get();
        } else if (isset($request->start_date) && $request->start_date != "") {
            $res = PaymentClient::select(
                "payment_clients.*",
                "payments.*",
                "payment_tours.*"
            )
                ->join('payments', 'payments.payment_clients_id', '=', 'payment_clients.id')
                ->join('payment_tours', 'payment_tours.payment_clients_id', '=', 'payment_clients.id')
                ->where('payment_clients.created_at', '>=', $request->start_date)
                ->where('payment_clients.status', $status)
                ->where('payments.status', $status)
                ->get();
        } else {
            $res = PaymentClient::select(
                "payment_clients.*",
                "payments.*",
                "payment_tours.*"
            )
                ->join('payments', 'payments.payment_clients_id', '=', 'payment_clients.id')
                ->join('payment_tours', 'payment_tours.payment_clients_id', '=', 'payment_clients.id')
                ->where('payment_clients.status', $status)
                ->where('payments.status', $status)
                ->get();
        }
        /*
        $res = PaymentClient::select(
            "payment_clients.*",
            "payments.*",
            "payment_tours.*"
        )
            ->join('payments', 'payments.payment_clients_id', '=', 'payment_clients.id')
            ->join('payment_tours', 'payment_tours.payment_clients_id', '=', 'payment_clients.id')
            ->where('payment_clients.status', $status)
            ->where('payments.status', $status)
            ->get();
            */

        if (count($res) > 0) {
            return $res;
        } else {
            return response()->json([
                'code' => 400,
                'message' => 'No data found ',
                'data' => ''
            ], 400);
        }
    }

    function getDetailReservation(Request $request)
    {
        /*
        $tours = PaymentTour::select(
            "*"
        )
            ->where('payment_clients_id', $request->id)
            ->get();
        */

        $tours = PaymentTour::select(
            "payment_tours.*",
            "tour_contents.name"

        )
            ->join('tour_contents', 'tour_contents.tour_id', '=', 'payment_tours.tours_id')
            ->where('payment_clients_id', $request->id)
            ->where('tour_contents.language_id', 2)
            ->get();

        if (count($tours) > 0) {
            return $tours;
        } else {
            return response()->json([
                'code' => 400,
                'message' => 'No data found ',
                'data' => ''
            ], 400);
        }
    }

    function cvsReservation(Request $request)
    {
        if (isset($request->start_date) && $request->start_date != "" && isset($request->end_date) && $request->end_date != "") {
            $res = PaymentClient::select(
                "payment_clients.*",
                "payments.*"
            )
                ->join('payments', 'payments.payment_clients_id', '=', 'payment_clients.id')
                ->where('payment_clients.status', 'complet')
                //->whereBetween('payment_clients.created_at', [$request->start_date, $request->end_date])
                ->whereRaw(
                    "(payment_clients.created_at >= ? AND payment_clients.created_at <= ?)",
                    [
                        $request->start_date . " 00:00:00",
                        $request->end_date . " 23:59:59"
                    ]
                )
                ->where(
                    'payments.status',
                    'complet'
                )
                ->get();
        } else if (isset($request->start_date) && $request->start_date != "") {
            $res = PaymentClient::select(
                "payment_clients.*",
                "payments.*"
            )
                ->join('payments', 'payments.payment_clients_id', '=', 'payment_clients.id')
                ->where('payment_clients.status', 'complet')
                ->where('payment_clients.created_at', '>=', $request->start_date)
                ->where(
                    'payments.status',
                    'complet'
                )
                ->get();
        } else {
            $res = PaymentClient::select(
                "payment_clients.*",
                "payments.*"
            )
                ->join('payments', 'payments.payment_clients_id', '=', 'payment_clients.id')
                ->where('payment_clients.status', 'complet')
                ->where(
                    'payments.status',
                    'complet'
                )
                ->get();
        }


        if (count($res) > 0) {
            /*
            $datos[0] = array('Name', 'Email', 'Tel', 'Hotel', 'Site', 'Code_reservacion', 'Code_authorization', 'Total', 'Tour', 'Fecha', 'Adultos' . 'Ni���os', 'Promocode', 'Descuento');
            $aux = 1;
            for ($a = 0; $a < sizeof($res); $a++) {

                //recuperando los tours del cliente

                $tours = PaymentTour::select(
                    "*"
                )
                    ->where('payment_clients_id', $res[$a]->id)
                    ->get();
                foreach ($tours as $value) {
                    $datos[$aux] = array(
                        $res[$a]->name,
                        $res[$a]->email,
                        $res[$a]->phone,
                        $res[$a]->hotel,
                        $res[$a]->site_book,
                        $res[$a]->code_book,
                        $res[$a]->authorization,
                        $res[$a]->total,
                        $value->tours_id,
                        $value->date,
                        $value->adult,
                        $value->child,
                        $value->promocode,
                        $value->discount
                    );
                    $aux++;
                }
            }

            $fileName = 'reporte.csv';

            $headers = array(
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            );

            $delimitador = ';';
            $encapsulador = '"';
            $file_handle = fopen('reporte.csv', 'w');
            foreach ($datos as $linea) {
                fputcsv($file_handle, $linea, $delimitador, $encapsulador);
            }
            rewind($file_handle);
            fclose($file_handle);
            return Response::download($fileName, 'reporte.csv', $headers);
            */


            $fileName = 'reporte.csv';

            $headers = array(
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            );
            $columns = array('Name', 'Email', 'Tel', 'Hotel', 'Site', 'Reservation_code', 'Authorization_code', 'Creation_date', 'Sub total', 'Total', 'Currency', 'Tour', 'Adults', 'Children', 'Tour date', 'Discount', 'Promocode');
            $file = fopen($fileName, 'w');
            fputcsv($file, $columns);

            $arr = [];
            for ($a = 0; $a < sizeof($res); $a++) {

                //recuperando los tours del cliente

                $tours = PaymentTour::select(
                    "payment_tours.*",
                    "tour_contents.name as nameTour"
                )
                    ->join('tour_contents', 'payment_tours.tours_id', '=', 'tour_contents.tour_id')
                    ->where('payment_clients_id', $res[$a]->id)
                    ->get();

                //$auxArry = [];
                //$con = 0;
                //foreach ($tours as $value) {
                //    $auxArry[$con]["tour"] = $value->tour_id;
                //    $auxArry[$con]["date"] = $value->date;
                //    $auxArry[$con]["adult"] = $value->adult;
                //    $auxArry[$con]["child"] = $value->child;
                //    $auxArry[$con]["promocode"] = $value->promocode;
                //    $auxArry[$con]["discount"] = $value->discount;
                //    $con++;
                //}

                $cadena = '';

                $arr[$a]["name"] = $res[$a]->name;
                $arr[$a]["email"] = $res[$a]->email;
                $arr[$a]["tel"] = $res[$a]->phone;
                $arr[$a]["hotel"] = $res[$a]->hotel;
                $arr[$a]["site"] = $res[$a]->site_book;
                $arr[$a]["code_reservacion"] = $res[$a]->code_book;
                $arr[$a]["code_authorization"] = $res[$a]->authorization;
                $arr[$a]["fecha_creacion"] = $res[$a]->created_at;
                $arr[$a]["subTotal"] = '';
                $arr[$a]["total"] = $res[$a]->total;
                $arr[$a]["currency"] = $res[$a]->currency;

                foreach ($tours as $value) {

                    $arr[$a]["Tour"] = $value->nameTour;
                    $arr[$a]["Adults"] = $value->adult;
                    $arr[$a]["Children"] = $value->child;
                    $arr[$a]["tourDate"] = $value->date;
                    $arr[$a]["Discount"] = $value->discount;
                    $arr[$a]["Promocode"] = $value->promocode;
                }

                fputcsv($file, array($arr[$a]["name"], $arr[$a]["email"], $arr[$a]["tel"], $arr[$a]["hotel"], $arr[$a]["site"], $arr[$a]["code_reservacion"], $arr[$a]["code_authorization"], $arr[$a]["fecha_creacion"], $arr[$a]["subTotal"], $arr[$a]["total"], $arr[$a]["currency"], $arr[$a]["Tour"], $arr[$a]["Adults"], $arr[$a]["Children"], $arr[$a]["tourDate"], $arr[$a]["Discount"], $arr[$a]["Promocode"]));
            }
            rewind($file);
            fclose($file);

            return Response::download($fileName, 'reporte.csv', $headers);
        } else {
            return response()->json([
                'code' => 400,
                'message' => 'No data found ',
                'data' => ''
            ], 400);
        }
    }

    //cancelacion de reserva envio de aviso
    function cancelTour(Request $request)
    {

        //actualiza  el registro del cliente
        PaymentClient::where('id', $request->id)
            ->update([
                "status" => "cancelled",
            ]);


        Payment::where('payment_clients_id', $request->id)
            ->update([
                "status" => "cancelled"
            ]);


        $res = PaymentClient::select(
            "payment_clients.*",
            "payments.*",
            "payment_tours.*",
            "tour_contents.name as nameTour"
        )
            ->join('payments', 'payments.payment_clients_id', '=', 'payment_clients.id')
            ->join('payment_tours', 'payment_tours.payment_clients_id', '=', 'payment_clients.id')
            ->join('tour_contents', 'tour_contents.tour_id', '=', 'payment_tours.tours_id')
            ->where('payment_clients.id', $request->id)

            ->get();

        //Mail::to("aperez@grupogarflo.com")->send(new MailSend($user));

        $details = [

            'client' => $res[0]->name,
            'email' => $res[0]->email,
            'phone' => $res[0]->phone,
            'codeBook' => $res[0]->code_book,
            'siteBook' => $res[0]->site_book,
            'language' => $res[0]->language,
            'status' => $res[0]->status,
            'hotel' => $res[0]->hotel,
            'total' => $res[0]->total,
            'currency' => $res[0]->currency,
            'authorization' => $res[0]->authorization,
            'toursId' => $res[0]->nameTour,
            'date' => $res[0]->date,
            'adults' => $res[0]->adult,
            'child' => $res[0]->child,
            'promocode' => $res[0]->promocode,
            'discount' => $res[0]->discount,
            'userName' => $request->userName
        ];

        Mail::to("aperez@grupogarflo.com")->send(new MailSend($details));
        //Mail::to("lavila@grupogarflo.com")->send(new MailSend($details));
        Mail::to("websales@cancuntours.com")->send(new MailSend($details));
    }

    //edita la fecha del tour
    function editTourBook(Request $request)
    {
        PaymentTour::where('payment_clients_id', $request->id)
            ->where("tours_id", $request->idTour)
            ->update([
                "date" => $request->date
            ]);

        return response()->json([
            'code' => 200,
            'message' => 'Edit completed',
            'data' => ''
        ], 200);
    }

    //refund tour
    function refundTourBook(Request $request)
    {
        $client = new Client();

        if ($request->reason === "Duplicate") {
            $reason = "duplicate";
        }
        if ($request->reason === "Requested by customer") {
            $reason = "requested_by_customer";
        }
        if ($request->reason === "Fraudulent") {
            $reason = "fraudulent";
        }

        //$amount = 10.00;
        //$id = "pi_3K9JyfGVWmRXvHsw2Ann8OMy";

        //$res = $client->request('POST', 'http://127.0.0.1:8002/api/refund', [
        $res = $client->request('POST', 'https: //www.cancuntours.com/paymentApi/api//refund', [
            'form_params' => [
                'id' => $request->id,
                'reason' => $reason,
                'amount' =>  $request->amount
            ]
        ]);

        $response_data = json_decode($res->getBody()->getContents());
        //print_r($response_data);
        //print_r($response_data->message);

        if ($response_data->status === 200) { // 200 OK

            //actualiza  el registro del cliente
            PaymentClient::where('id', $request->idBook)
                ->update([
                    "status" => "refunded",
                ]);


            Payment::where('payment_clients_id', $request->idBook)
                ->update([
                    "status" => "refunded"
                ]);


            $res = PaymentClient::select(
                "payment_clients.*",
                "payments.*",
                "payment_tours.*",
                "tour_contents.name as nameTour"
            )
                ->join('payments', 'payments.payment_clients_id', '=', 'payment_clients.id')
                ->join('payment_tours', 'payment_tours.payment_clients_id', '=', 'payment_clients.id')
                ->join('tour_contents', 'tour_contents.tour_id', '=', 'payment_tours.tours_id')
                ->where('payment_clients.id', $request->idBook)

                ->get();




            //Mail::to("aperez@grupogarflo.com")->send(new MailSend($user));

            $details = [

                'client' => $res[0]->name,
                'email' => $res[0]->email,
                'phone' => $res[0]->phone,
                'codeBook' => $res[0]->code_book,
                'siteBook' => $res[0]->site_book,
                'language' => $res[0]->language,
                'status' => $res[0]->status,
                'hotel' => $res[0]->hotel,
                'total' => $res[0]->total,
                'currency' => $res[0]->currency,
                'refundId' => $response_data->id,
                'toursId' => $res[0]->nameTour,
                'date' => $res[0]->date,
                'adults' => $res[0]->adult,
                'child' => $res[0]->child,
                'promocode' => $res[0]->promocode,
                'discount' => $res[0]->discount,
                'userName' => $request->userName
            ];


            Mail::to("aperez@grupogarflo.com")->send(new MailRefund($details));
            //Mail::to("lavila@grupogarflo.com")->send(new MailRefund($details));
            Mail::to("websales@cancuntours.com")->send(new MailRefund($details));

            return response()->json([
                'code' => 200,
                'message' => $response_data->message,
                'data' => $response_data->id
            ], 200);
        } else {
            return response()->json([
                'code' => 400,
                'message' => $response_data->message,
                'data' => ''
            ], 400);
        }
    }
}
