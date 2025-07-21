<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payment;
use Carbon\Carbon;

class CancelExpiredPayments extends Command
{
    protected $signature = 'payments:cancel-expired';
    protected $description = 'Cancel all pending payments that have expired (after 5 minutes).';

    public function handle()
    {
        $now = Carbon::now();
        $expiredPayments = Payment::where('transaction_status', 'pending')
            ->whereNotNull('payment_expired_at')
            ->where('payment_expired_at', '<', $now)
            ->get();

        foreach ($expiredPayments as $payment) {
            $payment->transaction_status = 'expired';
            $payment->save();
            // Update booking status juga jika perlu
            if ($payment->booking) {
                $payment->booking->status = 'expired';
                $payment->booking->save();
            }
        }

        $this->info('Expired payments cancelled: ' . $expiredPayments->count());
    }
}
