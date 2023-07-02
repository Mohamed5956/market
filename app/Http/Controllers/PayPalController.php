<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Omnipay\Omnipay;

class PayPalController extends Controller
{
    private \Omnipay\Common\GatewayInterface $gateway;
    public function __construct()
    {
        $this->gateway = Omnipay::create('PayPal_Rest');
        $this->gateway->setClientId(env('PAYPAL_SANDBOX_CLIENT_ID'));
        $this->gateway->setSecret(env('PAYPAL_SANDBOX_CLIENT_SECRET'));
        $this->gateway->setTestMode(true);
    }

    public function payment(Request $request){
        try{
//            dd($request->input('amount'));
//            .'?order='.$order
//            dd($request->all());
            $userId=Auth::id();
//            dd($user);
            $order = $request->all();
            $response = $this->gateway->purchase([
                'amount' => $request->total_price,
                'order' => $order,
                'currency' => 'USD',
                'returnUrl' => url('/api/payment/success').'?auth_user_id='.$userId.'&total_price='.$request->total_price.'&order='.http_build_query($order),
                'cancelUrl' => url('/api/cancel'),
            ])->send();
            if($response->isRedirect()){
//                $response->redirect();
                $redirectUrl = $response->getRedirectUrl();
                return response()->json(['redirect_url' => $redirectUrl]);
            }
            else{
                return $response->getMessage();
            }
        }catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
    public function cancel(){
        return redirect('http://localhost:8000/error');
    }
    public function success(Request $request)
    {
        $user_id = $request->auth_user_id;
        $user = User::where('id', $user_id)->first();
        $tracking_no = 'Order' . time();

        if ($request->input('paymentId') && $request->input('PayerID')) {
            $transaction = $this->gateway->completePurchase([
                'payerId' => $request->input('PayerID'),
                'transactionReference' => $request->input('paymentId')
            ]);

            $response = $transaction->send();
            if ($response->isSuccessful()) {
                $arr = $response->getData();
                $orderData = [];
                parse_str($request->query('order'), $orderData);

                $order = Order::create([
                    'firstName' => $user->name,
                    'lastName' => $user->lastName,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'address' => $user->address,
                    'total_price' => $request->total_price,
                    'user_id' => $user->id,
                    'tracking_no' => $tracking_no
                ]);

                $orderItems = $orderData['order_items'];
                foreach ($orderItems as $item) {
                    $order->orderItems()->create([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                    ]);

                    $product = Product::findOrFail($item['product_id']);
                    $product->quantity -= $item['quantity'];
                    $product->update();
                }

                $order->save();
                return redirect('http://localhost:8080/success');
            } else {
                return $response->getMessage();
            }
        } else {
            return redirect('http://localhost:8000/error');
        }
    }

}
