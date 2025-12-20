<?php

namespace App\Mail;

use App\Models\Reminder;
use App\Models\InstallmentItem;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $reminder;
    public $installmentItem;
    public $booking;
    public $customer;

    public function __construct(Reminder $reminder, InstallmentItem $installmentItem, Booking $booking, User $customer)
    {
        $this->reminder = $reminder;
        $this->installmentItem = $installmentItem;
        $this->booking = $booking;
        $this->customer = $customer;
    }

    public function build()
    {
        return $this->subject('Payment Reminder - Installment #' . $this->installmentItem->installment_number)
                    ->view('emails.payment-reminder');
    }
}