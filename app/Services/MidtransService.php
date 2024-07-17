<?php
namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function createTransaction($orderId, $grossAmount, $customerDetails)
    {
        $transactionDetails = [
            'order_id' => $orderId,
            'gross_amount' => $grossAmount,
        ];

        $transaction = [
            'transaction_details' => $transactionDetails,
            'customer_details' => $customerDetails,
        ];

        try {
            $snapTransaction = Snap::createTransaction($transaction);

            return [
                'payment_url' => $snapTransaction->redirect_url,
                'snap_token' => $snapTransaction->token // Mengembalikan Snap Token
            ];
        } catch (\Exception $e) {
            \Log::error('Error creating Midtrans transaction: '.$e->getMessage());
            return null;
        }
    }
}
