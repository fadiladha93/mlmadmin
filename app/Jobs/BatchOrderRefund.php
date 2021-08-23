<?php

namespace App\Jobs;

use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class BatchOrderRefund implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $file;    
    private $order_refund_status_code;
    private $order_refunded_status_code;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($file_path)
    {
        set_time_limit(0);
        
        $this->file = fopen($file_path, 'r');
        $this->order_refund_status_code = Order::ORDER_STATUS_REFUND;
        $this->order_refunded_status_code = Order::ORDER_STATUS_REFUNDED;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Order $order)
    {
        $file = $this->file;
        while (($line = fgetcsv($file)) !== FALSE) {
            //transaction_id == $line[0]
            if(!is_numeric($line[0]))
                continue;

            $order_refund = $order->where('trasnactionid', 'like', $line[0].'#%')->first();
            if($order_refund){
                $new_order = $order->create([
                    'userid' => $order_refund->userid,
                    'statuscode' => $this->order_refund_status_code,
                    'ordersubtotal' => floatval($line[1]),
                    'ordertotal' => floatval($line[1]),
                    'order_refund_ref' => $order_refund->id 
                ]);
                $order_refund->update([
                    'statuscode' => $this->order_refunded_status_code,
                    'order_refund_ref' => $new_order->id
                ]);
                print("New order ".$new_order->id." to ".$order_refund->id);
            }
            
        }
        
        fclose($file);
    }
}
