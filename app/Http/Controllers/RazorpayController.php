<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Session;
use Redirect;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Input;

class RazorpayController extends Controller
{
    public function pay($request)
    {
        if(Session::has('payment_type')){
            return view('frontend.payment_gateway.razorpay');
        }
    }

    public function payment(Request $request)
    {
        //Input items of form
        $input = $request->all();
        //get API Configuration
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        if (!empty($input['razorpay_payment_id'])) {
            try {
                $payment = $api->payment->fetch($input['razorpay_payment_id']);

                // Only capture if not captured yet
                if (!$payment['captured']) {
                    $amount = $payment['amount']; 
                    $response = $payment->capture(['amount' => $amount]);
                } else {
                    $response = $payment;
                }

                $payment_details = json_encode([
                    'id' => $response['id'],
                    'method' => $response['method'],
                    'amount' => $response['amount'],
                    'currency' => $response['currency'],
                    'status' => $response['status']
                ]);
                if(Session::has('payment_type')){
                    if (Session::get('payment_type') == 'package_payment') {
                        $packagePaymentController = new PackagePaymentController;
                        return $packagePaymentController->package_payment_done(Session::get('payment_data'), $payment_details);
                    } elseif (Session::get('payment_type') == 'wallet_payment') {
                        $walletController = new WalletController;
                        return $walletController->wallet_payment_done(Session::get('payment_data'), $payment_details);
                    }
                }

            } catch (\Exception $e) {
                dd('Razorpay Exception:', $e->getMessage(), $e->getCode());
            }
        } else {
            dd('No razorpay_payment_id found!');
        }
    }
}
