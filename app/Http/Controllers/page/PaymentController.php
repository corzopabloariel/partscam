<?php

namespace App\Http\Controllers\page;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use MP;
use Cookie;
use App\Transaccion;
class PaymentController extends Controller
{
    //
    public function success()
    {
        dd(request()->external_reference);
        
    }
    public function failure()
    {
        //-> Vuelve a sumar la cantidad
        $transaccion = Transaccion::find(request()->external_reference);
        $productos = $transaccion->productos;
        foreach($productos AS $p) {
            $aux = $p->producto;
            $aux->stock->fill(["cantidad" => $aux->stock["cantidad"] + $p["cantidad"]]);
            $aux->stock->save();
        }
        $transaccion->fill(["estado" => 0]);
        $transaccion->save();
        return 'Su compra no a podido ser procesada';
    }
    public function pending()
    {
        return 'Su compra esta pendiente por ser porcesada';
    }

    public function ipn() {
    
        if ( ! isset($_GET["id"], $_GET["topic"]) || ! ctype_digit($_GET["id"])) {
            abort(404);
        }
    
        // Get the payment and the corresponding merchant_order reported by the IPN.
        if ($_GET["topic"] == 'payment') {
            $payment_info = $mp->get("/v1/payments/$payment_id/" . $_GET["id"]);
            $merchant_order_info = $mp->get("/merchant_orders/" . $payment_info["response"]["collection"]["merchant_order_id"]);
            
            // Get the merchant_order reported by the IPN.
    
            // get order and link the notification id
            $external_reference_id = $merchant_order_info["response"]["external_reference"];
            $order = Order::findOrFail($external_reference_id);
            // link notification id
            $order->mp_notification_id = $_GET["id"];
    
            if ($merchant_order_info["status"] == 200) {
                // If the payment's transaction amount is equal (or bigger) than the merchant_order's amount you can release your items
                
                $paid_amount = 0;
    
                foreach ($merchant_order_info["response"]["payments"] as $payment) {
                    $order->status = $payment['status'];
                    if ($payment['status'] == 'approved') {
                        $paid_amount += $payment['transaction_amount'];
                    }
                }
    
                if ($paid_amount >= $merchant_order_info["response"]["total_amount"]) {
                    if (count($merchant_order_info["response"]["shipments"]) > 0) { 
                        
                        // The merchant_order has shipments
                        if ($merchant_order_info["response"]["shipments"][0]["status"] == "ready_to_ship") {
                            print_r("Totally paid. Print the label and release your item.");
                        }
                    } else {
                        // The merchant_order don't has any shipments
                        print_r("Totally paid. Release your item.");
                    }
                } else {
                    print_r("Not paid yet. Do not release your item.");
                }
            }
    
            $order->save();
    
        }
    
        return response('OK', 201);
    }
}
