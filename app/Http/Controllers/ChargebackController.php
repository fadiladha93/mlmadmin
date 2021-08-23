<?php

namespace App\Http\Controllers;

use App\Models\ChargebackMerchant;
use App\Models\Merchant;
use App\Order;
use App\Models\Chargeback;
use App\Models\ChargebackImport;
use App\User;
use App\Helper;
use App\PaymentMethodType;

class ChargebackController extends Controller
{
    private const
        ORDER_NOT_FOUND = 0,
        ORDER_CHARGED_BACK = 1,
        ORDER_REFUNDED = 2,
        ORDER_CAN_BE_CHARGED_BACK = 3,
        INVALID_MERCHANT = 4;

    private function parseCsv($filename, $merchant)
    {
        $csv = array_map('str_getcsv', file($filename));

        $headerLine = 0;//$merchant->header_line;
        $dataLine = $merchant->data_line;

        $header = $csv[$headerLine];

        for ($i = 0; $i < $dataLine; $i++) {
            array_shift($csv);
        }

        array_walk($csv, function (&$a) use ($csv, $header) {
            $a = array_combine($header, $a);
        });

        return array($header, $csv);
    }

    private function getMissingHeadersOrEmpty($requiredHeaders, $headerLine)
    {
        $missingHeaders = [];

        foreach ($requiredHeaders as $requiredHeader) {
            if (!in_array($requiredHeader, $headerLine)) {
                $missingHeaders[] = $requiredHeader;
            }
        }

        return $missingHeaders;
    }

    public function verifyEntries($csv)
    {
        $validMerchants = [
            'T1',
            'Payarc',
            'Metropolitan',
            'NMI',
            'iPaytotal'
        ];

        foreach ($csv as &$entry) {
            $transactionId = $entry['Trans_ID'];
            $merchant = $entry['merchant'];

            if (!in_array($merchant, $validMerchants)) {
                $entry['result'] = static::INVALID_MERCHANT;
            }

            $order = Order::query()->where('trasnactionid', 'ilike', "%$transactionId%")
                ->where('trasnactionid', 'NOT LIKE', '%#chargeback')
                ->first();

            if (!$order) {
                $entry['result'] = static::ORDER_NOT_FOUND;
            } else if (in_array($order->statuscode, [Order::ORDER_STATUS_CHARGED_BACK, Order::ORDER_STATUS_REFUNDED_AND_CHARGED_BACK])) {
                $entry['result'] = static::ORDER_CHARGED_BACK;
            } else if ($order->statuscode == Order::ORDER_STATUS_REFUNDED) {
                $entry['result'] = static::ORDER_REFUNDED;
            } else {
                $entry['result'] = static::ORDER_CAN_BE_CHARGED_BACK;
            }
        }

        return $csv;
    }

    public function importEntries($csv, $selectedMerchant)
    {
        set_time_limit(0);
        foreach(collect($csv)->chunk(1000) as $csv) {
            $transactions = $csv->pluck('Trans_ID');
            $orders = Order::query()->whereIn(\DB::raw("split_part(\"trasnactionid\", '#', 1)"), $transactions)
                ->where('trasnactionid', 'NOT LIKE', '%#chargeback')
                ->whereNotIn('statuscode', [Order::ORDER_STATUS_CHARGED_BACK, Order::ORDER_STATUS_REFUNDED_AND_CHARGED_BACK])
                ->with('user')
                ->get();
            foreach($orders as $order){
                $transactionId = substr($order->trasnactionid,0,10);
                $entries = $csv->where('Trans_ID', $transactionId);
                foreach($entries as $entry){
                    $this->performChargeback($order, $entry, $transactionId, $selectedMerchant);
                }
            }
        }
        /*
        foreach ($csv as &$entry) {
            $transactionId = $entry['Trans_ID'];

            $order = Order::query()->where('trasnactionid', 'ilike', "%$transactionId%")
                ->where('trasnactionid', 'NOT LIKE', '%#chargeback')
                ->first();
            if($order && !in_array($order->statuscode, [Order::ORDER_STATUS_CHARGED_BACK, Order::ORDER_STATUS_REFUNDED_AND_CHARGED_BACK])){
                $this->performChargeback($order, $entry, $transactionId, $selectedMerchant);
            
            }
        }*/
    }

    private function performChargeback(\App\Order $order, $entry, $transactionId, $selectedMerchant)
    {
        try {
            $chargebackId = $entry['ChargebackId'];
            $user = $order->user;
            $user->account_status = User::ACC_STATUS_TERMINATED;
            $user->save();

            // TODO: update to use chargeback amount

            $chargebackOrder = new Order([
                'userid' => $order->userid,
                'ordersubtotal' => (double)$order->subtotal * -1,
                'ordertotal' => (double)$order->ordertotal * -1,
                'trasnactionid' => "$chargebackId#chargeback",
                'ordercv' => $order->ordercv * -1,
                'orderqv' => 0,
                'orderqc' => $order->orderqc * -1,
                'orderac' => $order->orderac * -1,
                'shipping_address_id' => $order->shipping_address_id,
                'payment_methods_id' => $order->payment_methods_id,
                'payment_type_id' => $order->payment_type_id,
                'statuscode' => Order::ORDER_STATUS_CHARGEBACK
            ]);

            $chargebackOrder->save();
            $results[$transactionId] = static::ORDER_CHARGED_BACK;

            // TODO: update to use refund code
            // ORDER::ORDER_STATUS_CHARGED_BACK_AND_REFUNDED

            $order->statuscode = Order::ORDER_STATUS_CHARGED_BACK;
            $order->order_chargeback_ref = $chargebackOrder->id;
            $order->orderqv = 0;
            $order->save();

            $chargeback = new Chargeback([
                'order_id' => $order->id,
                'transaction_id' => $entry['Trans_ID'],
                'chargeback_id' => $entry['ChargebackId'],
                'date' => $entry['tran_date'],
                'amount' => str_replace(',','',$entry['cb_amt']),
                'currency' => str_replace(array( '(', ')' ), '', $entry['Currency']),
                'merchant' => $selectedMerchant->name
            ]);

            $chargeback->save();

            $chargebackImport = new ChargebackImport([
                'chargeback_table_id' => $chargeback->id,
                'transaction_id' => $entry['Trans_ID'],
                'chargeback_date' => $entry['tran_date'],
                'chargeback_deadline_date' => $entry['deadline_date'],
                'chargeback_amount' => $entry['cb_amt'],
                'chargeback_reason_description' => $entry['cb_reason_descr'],
                'chargeback_subject' => $entry['subject'],
                'chargeback_id' => $entry['ChargebackId'],
                'transaction_amount' => $entry['tran_amt'],
                'card_bin' => $entry['card_bin'],
                'card_last_four' => $entry['card_bin'],
                'card_brand' => $entry['card_brand'],
                'card_holder' => mb_convert_encoding($entry['card_holder'], 'UTF-8', 'UTF-8')
            ]);

            $chargebackImport->save();

            if (in_array(strtolower(env('APP_ENV')), ['prod', 'production'])) {
                Helper::deActivateIdecideUser($user->id);
                Helper::deActivateSaveOnUser($user->id, $user->current_product_id, $user->distid, 'Chargeback');
            }

            return true;
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'errors' => [
                    'Import CSV Failed'
                ]
            ]);
        }
    }

    public function merchants()
    {
        $merchants = Merchant::query()->get()->all();

        return $merchants;
}

    public function dataCsv(){
        return 'orization,Instant Represented Chargeback (9825),10602825,NMI,4/25/20,6/14/20,50,"Thursday June 18, 2020",799.95,799.95,MASTERCARD,557384,9825,BERHE GHEBREZGIABIHER WELDEMARIAM,5456,115,54';
    }

    public function importForm()
    {
        $merchants = ChargebackMerchant::query()->get()->all();
        
        return view('admin.merchants.chargeback-import', [
            'merchants' => $merchants
        ]);
    }

    public function import()
    {   
        $merchants = ChargebackMerchant::query()->get()->all();

        if (!request()->hasFile('importFile') || !request()->chargeback_merchant_id) {
            return response()->json('All fields are required', 400);
        }

        $selectedMerchant = Merchant::find(request()->chargeback_merchant_id);

        $importFile = request()->file('importFile');

        if (!$importFile->isReadable()) {
            return response()->json('Internal error 1 - file is not readable. Contact support.', 400);
        }

        // $chargebackMerchantId = filter_var(request()->post('chargeback_merchant_id'), FILTER_VALIDATE_INT);

        $merchant = ChargebackMerchant::query()->first();

        $activeFields = array_filter($merchant->getAttributes(), function($val) {
            if ($val == null) {
                return false;
            }

            return true;
        });

        unset($activeFields['id']);
        unset($activeFields['name']);
        $headerLine = 1;//$merchant->header_line;//$activeFields['header_line'];
        $dataLine = $activeFields['data_line'];
        unset($activeFields['header_line']);
        unset($activeFields['data_line']);
        $requiredHeaders = array_values($activeFields);
        $numRequiredHeaders = sizeof($requiredHeaders);

        try{
            list($header, $csv) = $this->parseCsv($importFile->getPath() . '/' . $importFile->getFilename(), $merchant);
            $numHeaders = sizeof($csv[$headerLine]);

        }catch (\Exception $e) {

            return response()->json('CSV File is invalid', 400);
        }


        if ($numHeaders < $numRequiredHeaders) {
            return response()->json('- Invalid number of headers, expected a minimum of ' . $numRequiredHeaders . ' headers, but found ' . $numHeaders . ' headers');
        }

        $path = $importFile->storeAs(public_path('/csv/chargeback'), $importFile->getFilename() . '.csv');
        $missingHeaders = $this->getMissingHeadersOrEmpty($requiredHeaders, $header);

        if (empty($missingHeaders)) {
            $actualHeaders = array_keys($csv[$headerLine]);

            return view('admin.merchants.chargeback-import', [
                'merchants' => $merchants,
                'success' => false,
                'missingHeaders' => $missingHeaders,
                'actualHeaders' => $actualHeaders,
                'activeFields' => $activeFields,
                'headerLine' => $headerLine,
                'numHeaders' => $numHeaders,
                'numRequiredHeaders' => $numRequiredHeaders,
                'headers' => $header,
                'csv' => $csv
            ]);
        }

        // $results = $this->verifyEntries($csv);
        $results = $this->importEntries($csv, $selectedMerchant);

        return view('admin.merchants.chargeback-import', [
            'success' => !empty($chargedBack),
            'merchants' => $merchants,
            'headers' => $header,
            'results' => $results,
            'activeFields' => $activeFields,
            'actualHeaders' => []
        ]);
    }

    public function manage()
    {
        return view('admin.merchants.chargeback-manage');
    }
}
