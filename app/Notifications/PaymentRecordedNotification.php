<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentRecordedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Payment $payment)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Payment Recorded - CCS Payment System')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A payment has been successfully recorded for your account.')
            ->line('**Payment Details:**')
            ->line('Amount: ₱' . number_format($this->payment->amount, 2))
            ->line('Payment Date: ' . $this->payment->payment_date->format('F d, Y'))
            ->line('Payment Method: ' . ucfirst($this->payment->payment_method))
            ->line('Status: ' . ucfirst($this->payment->status));
        
        if ($this->payment->reference_number) {
            $mail->line('Reference Number: ' . $this->payment->reference_number);
        }
        
        return $mail
            ->action('View Payment Details', url('/student/dashboard'))
            ->line('Thank you for your payment!')
            ->line('If you have any questions, please contact your treasurer or the admin.')
            ->salutation('Best regards, CCS Payment Monitoring System');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'payment_id' => $this->payment->id,
            'amount' => $this->payment->amount,
            'payment_date' => $this->payment->payment_date->format('Y-m-d'),
            'payment_method' => $this->payment->payment_method,
            'status' => $this->payment->status,
            'message' => 'Payment of ₱' . number_format($this->payment->amount, 2) . ' recorded successfully',
        ];
    }
}
