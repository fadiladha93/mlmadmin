<?php

namespace App\Http\Controllers;

use App\Order;
use App\OrderItem;
use App\Core\Refunder;
use App\helpers\ApiHelper;
use Illuminate\Http\Request;
use App\helpers\HttpStatuses;
use Illuminate\Support\Facades\Log;

class RefundController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth.admin');
//        $this->middleware(function ($request, $next) {
//            if (!(\App\User::admin_super_admin() || \App\User::admin_cs_exec())) {
//                if ($request->ajax() || $request->wantsJson()) {
//                    return response('Unauthorized.', 401);
//                } else {
//                    return redirect('/');
//                }
//            }
//            return $next($request);
//        });
    }

    public function refundOrder()
    {

        /** @var Order $order */
        if (!$order = Order::getActiveOrder(request()->order_id)) {
            return response()->json([
                'error' => 1,
                'msg' => '#' . request()->order_id . ' Order already refunded.'
            ]);
        }

        try {
            $refundOrder = (new Refunder())
                ->refundOrder($order)
                ->createRefundOrder((int)request()->refund_qv)
                ->finish();

            if (filter_var(request()->terminate_user, FILTER_VALIDATE_BOOLEAN)) {
                $refundOrder->terminateUser();
            }

            if (filter_var(request()->suspend_user, FILTER_VALIDATE_BOOLEAN)) {
                $refundOrder->suspendUser();
            }

            # Check if exist world series and have an order there.
            if ($this->isActiveWorldSeries()) {
                $response = ApiHelper::request('POST', '/join/errors-world-series', ['order_id' => $order->id]);
            }

            return response()->json([
                'error' => $refundOrder->getStatus(),
                'msg' => $refundOrder->getMessage()
            ], HttpStatuses::SUCCESS_200);
        } catch (\Exception $ex) {
            return response()->json([
                'error' => 1,
                'msg' => $ex->getMessage()
            ]);
        }
    }

    public function refundBatchForm(){
        return view('admin.orders.batchRefund');
    }

    public function refundBatch(Request $request, Order $order, OrderItem $orderItem){
        
        $file = fopen($request->media_file->getRealPath(), 'r');
        
        $order_refund_status_code = $order::ORDER_STATUS_REFUND;
        $order_refunded_status_code = $order::ORDER_STATUS_REFUNDED;
        
        $processed = 0;
        $skipped = 0;
        $failed = 0;
        $successfully = 0;
        $today = '2020-08-31'; //date("Y-m-d");
        $time = ' 22:11:05'; //date("H:i:s");

        while (($line = fgetcsv($file)) !== FALSE) {
            //transaction_id == $line[0]
            if(!is_numeric($line[0])) // ignore head lines 
                continue;

            $processed++;

            $order_refund = $order->where('trasnactionid', 'like', $line[0].'#%')
                                ->orWhere('trasnactionid', $line[0]) // exact value
                                ->first();
                                
            if($order_refund){
                if(!is_null($order_refund->order_refund_ref)){
                    $skipped++;
                    continue;        
                } //duplicate

                $orderItems = $order_refund->orderItems()->get()->toArray();
                
                $data = $order_refund->toArray();
                $data['orderbv'] = $data['orderbv'] * (-1); 
                $data['orderqv'] = $data['orderqv'] * (-1);
                $data['ordercv'] = $data['ordercv'] * (-1);
                $data['orderqc'] = $data['orderqc'] * (-1);
                $data['orderac'] = $data['orderac'] * (-1);
                $data['ordertax'] = $data['ordertax'] * (-1);
                $data['statuscode'] = $order_refund_status_code;
                $data['ordersubtotal'] = abs($line[1]) * (-1);
                $data['ordertotal'] = abs($line[1]) * (-1);
                $data['order_refund_ref'] = $data['id'];
                $data['created_date'] = $today;
                $data['created_time'] = $time;
                $data['created_dt'] = $today . ' ' . $time;
                unset($data['id'], $data['trasnactionid']);
                
                $new_order = $order->create($data);
                $order_refund->update([
                    'statuscode' => $order_refunded_status_code,
                    'order_refund_ref' => $new_order->id
                ]);

                for($i=0; $i < count($orderItems);$i++){
                    $orderItems[$i]['itemprice'] = $orderItems[$i]['itemprice'] * (-1);
                    $orderItems[$i]['bv'] = $orderItems[$i]['bv'] * (-1);
                    $orderItems[$i]['qv'] = $orderItems[$i]['qv'] * (-1);
                    $orderItems[$i]['cv'] = $orderItems[$i]['cv'] * (-1);
                    $orderItems[$i]['qc'] = $orderItems[$i]['qc'] * (-1);
                    $orderItems[$i]['ac'] = $orderItems[$i]['ac'] * (-1);
                    $orderItems[$i]['orderid'] = $new_order->id;
                    $orderItems[$i]['created_date'] = $today;
                    $orderItems[$i]['created_time'] = $time;
                    $orderItems[$i]['created_dt'] = $today . ' ' . $time;
                    $orderItems[$i]['is_refunded'] = 1;
                    unset($orderItems[$i]['id']);
                }
                
                $orderItem->insert($orderItems);

                $successfully++;
            }else{
                Log::info("Transaction id not found", $line);
                $failed++;
            }
            
        }
        fclose($file);
        
        return redirect('/batch-order-refund')->with([
            'successfully' => $successfully,
            'failed' => $failed,
            'skipped' => $skipped,
            'processed' => $processed
        ]);
    }

    public function refundOrderItem()
    {
        /** @var OrderItem $orderItem */
        $orderItem = \App\OrderItem::where('id', request()->order_item_id)->first();
        if (!$orderItem) {
            return response()->json([
                'error' => 1,
                'msg' => 'Could not find order item with id #' . request()->order_item_id
            ]);
        }

        if ($orderItem->order->isPurchasedByVoucher()) {
            return response()->json([
                'error' => 1,
                'msg' => 'Sorry currently unable to do partial refunds for purchases made with vouchers'
            ]);
        }

        // check order exists
        if ($orderItem->is_refunded) {
            return response()->json([
                'error' => 1,
                'msg' => '#' . request()->order_item_id . ' Order item already refunded.'
            ]);
        }

        try {
            $refundOrder = (new Refunder())
                ->refundOrderItem($orderItem)
                ->createRefundOrder((int)request()->refund_qv)
                ->finish();

            if (filter_var(request()->terminate_user, FILTER_VALIDATE_BOOLEAN)) {
                $refundOrder->terminateUser();
            }

            if (filter_var(request()->suspend_user, FILTER_VALIDATE_BOOLEAN)) {
                $refundOrder->suspendUser();
            }

            return response()->json([
                'error' => $refundOrder->getStatus(),
                'msg' => $refundOrder->getMessage()
            ], HttpStatuses::SUCCESS_200);
        } catch (\Exception $ex) {
            return response()->json([
                'error' => 1,
                'msg' => $ex->getMessage()
            ]);
        }
    }


    public function isActiveWorldSeries()
    {
        $month = \Carbon\Carbon::now()->endOfMonth()->month;
        
        return in_array($month, [6, 7, 8]) ? true : false;
    }


}
