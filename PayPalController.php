<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use Illuminate\Support\Facades\Redirect;

class PayPalController extends Controller
{
    private $apiContext;

    public function __construct()
    {
        $paypalConfig = config('services.paypal');

        $this->apiContext = new ApiContext(
            new OAuthTokenCredential(
                $paypalConfig['client_id'],
                $paypalConfig['secret']
            )
        );

        $this->apiContext->setConfig($paypalConfig['settings']);
    }

    public function index()
    {
        return view('paypal.index');
    }

    public function payWithPayPal(Request $request)
    {
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $amount = new Amount();
        $amount->setTotal($request->amount);
        $amount->setCurrency('PHP');

        $transaction = new Transaction();
        $transaction->setAmount($amount);
        $transaction->setDescription('Payment description');

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(route('paypal.success'))
            ->setCancelUrl(route('paypal.cancel'));

        $payment = new Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setTransactions([$transaction])
            ->setRedirectUrls($redirectUrls);

        try {
            $payment->create($this->apiContext);

            return Redirect::away($payment->getApprovalLink());
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            //            return redirect()->route('paypal.cancel');
            return $ex->getMessage();
        }
    }

    public function success(Request $request)
    {
        if (empty($request->PayerID) || empty($request->token)) {
            return redirect()->route('paypal.cancel');
        }

        $paymentId = $request->paymentId;
        $payment = Payment::get($paymentId, $this->apiContext);

        $execution = new PaymentExecution();
        $execution->setPayerId($request->PayerID);

        try {
            $result = $payment->execute($execution, $this->apiContext);

            if ($result->getState() == 'approved') {
                return 'Payment success';
            }
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return redirect()->route('paypal.cancel');
        }

        return redirect()->route('paypal.cancel');
    }

    public function cancel()
    {
        return 'Payment canceled';
    }
}