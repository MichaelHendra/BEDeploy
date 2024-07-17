<?php

namespace App\Http\Controllers;

use App\Models\Subs;
use App\Models\Transaksi;
use App\Models\User;
use App\Services\MidtransService;
use Midtrans\Notification;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    public function subscribe($id, Request $request)  
    {
        $user = User::find($id);
        $subPlan = Subs::find($request->sub_id);

        if (!$subPlan) {
            return response()->json(['error' => 'Jenis Langganan Anda Tidak Ada'], 404);
        }

        $orderId = uniqid();
        $grossAmount = $subPlan->harga;
        $customerDetails = [
            'first_name' => $user->name,
            'last_name' => '',
            'email' => $user->email,
            'phone' => $user->telp,
        ];

        $transaction = $this->midtransService->createTransaction($orderId, $grossAmount, $customerDetails);

        if ($transaction) {
            $user->order_id = $orderId;
            $user->save();

            Transaksi::create([
                'order_id' => $orderId,
                'user_id' => $id,
                'plan_id' => $request->sub_id,
                'status' => '' // Assuming initial status is empty
            ]);
        }

        return response()->json([
            'payment_url' => $transaction['payment_url'],
            'snap_token' => $transaction['snap_token']
        ]);
    }

    public function handleNotification(Request $request)  
    {
        \Log::info('Midtrans Notification received.', ['data' => $request->all()]);
    
        try {
            $notification = new Notification();
            $transactionStatus = $notification->transaction_status;
            $orderId = $notification->order_id;
    
            \Log::info('Transaction Status and Order ID extracted.', ['transactionStatus' => $transactionStatus, 'orderId' => $orderId]);
    
            // Find the user by order_id
            $user = User::where('order_id', $orderId)->first();
            $transaksi = Transaksi::where('order_id', $orderId)->first();
    
            if (!$user) {
                \Log::error('User not found for given order_id.', ['orderId' => $orderId]);
                return response()->json(['error' => 'User not found'], 404);
            }
            if (!$transaksi) {
                \Log::error('Transaksi not found for given order_id.', ['orderId' => $orderId]);
                return response()->json(['error' => 'Transaksi not found'], 404);
            }
    
            \Log::info('Found user and transaksi.', ['user' => $user, 'transaksi' => $transaksi]);
    
            if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                $planId = $transaksi->plan_id;
    
                if (!$planId) {
                    \Log::error('Plan ID not found in transaksi.', ['transaksi' => $transaksi]);
                    return response()->json(['error' => 'Plan ID not found'], 400);
                }
    
                $subPlan = Subs::find($planId);
                if (!$subPlan) {
                    \Log::error('Subscription plan not found.', ['planId' => $planId]);
                    return response()->json(['error' => 'Subscription plan not found'], 404);
                }
    
                $user->plan_id = $planId;
                $user->date_sub = now();
                $user->valid_date = now()->addDays($subPlan->sub_day);
                $user->save();
    
                \Log::info('User subscription updated.', ['user' => $user]);
    
                $transaksi->status = $transactionStatus;
                $transaksi->save();
    
                \Log::info('Transaksi status updated to Success.', ['transaksi' => $transaksi]);
    
                return response()->json(['message' => 'Subscription updated successfully']);
            } elseif ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
                \Log::warning('Payment denied or expired.', ['transactionStatus' => $transactionStatus]);
                $transaksi->status = $transactionStatus;
                $transaksi->save();
                \Log::info('Transaksi status updated to ' . $transactionStatus, ['transaksi' => $transaksi]);
                return response()->json(['message' => 'Payment denied or expired'], 400);
            }
    
            \Log::info('Notification handled with transaction status.', ['transactionStatus' => $transactionStatus]);
            return response()->json(['message' => 'Notification handled'], 200);
        } catch (\Exception $e) {
            \Log::error('Error handling Midtrans notification.', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Error handling notification'], 500);
        }
    }    
}
