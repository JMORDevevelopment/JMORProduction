<?php

namespace App\Services;

<<<<<<< HEAD
use App\Models\Setting;
=======
use Illuminate\Support\Facades\DB;
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)
use Illuminate\Support\Facades\Mail;

class InvoiceService
{
    public function sendOrderInvoice(
        int $orderId,
        string $transactionId,
        object $order,
        object $customer,
        iterable $orderDetails,
        float $subTotal,
        float $discount,
        float $amount
    ): void {
        $data = [
            'orderId' => $orderId,
            'transactionId' => $transactionId,
            'order' => $order,
            'customer' => $customer,
            'orderDetails' => $orderDetails,
            'subTotal' => $subTotal,
            'discount' => $discount,
            'amount' => $amount,
        ];

        $subject = 'Order Invoice';

        Mail::send('mails.order-invoice', $data, function ($mail) use ($customer, $subject) {
            $mail->to($customer->email)->subject($subject)->from('Info@jmor.com');
        });

<<<<<<< HEAD
        $adminEmail = Setting::get('email');
=======
        $adminEmail = DB::table('settings')->where('option', 'email')->value('value');
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)

        if ($adminEmail) {
            Mail::send('mails.order-invoice', $data, function ($mail) use ($adminEmail, $subject) {
                $mail->to($adminEmail)->subject($subject)->from('noreply@jmor.com');
            });
        }
    }
}
