<?php

namespace App\Notifications\Subscription;

use Illuminate\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notifiable;

class Expired extends Notification
{
    use Notifiable;

    public $template;
    public function __construct()
    {

    }
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }
    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toMail($notifiable): MailMessage
    {
        $message = (new MailMessage)
        ->line("Your subscription has ended")
        ->subject('Subscription Ended');

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toArray($notifiable): array
    {
        //$this->initArrayMessage();

        return [
            'template_alias' => 'subscription_expired',
            'title' => 'Your subscription has expired',
            'description' => 'Your subscription has expired. All editing feature has been disabled',
        ];
    }

    public function getTags(): array
    {
        return [];
        return [
            '{invoice_number}',
            '{invoice_total}',
            '{invoice_due_date}',
            '{invoice_status}',
            '{invoice_guest_link}',
            '{invoice_admin_link}',
            '{invoice_portal_link}',
            '{transaction_total}',
            '{transaction_paid_date}',
            '{transaction_payment_method}',
            '{customer_name}',
            '{company_name}',
            '{company_email}',
            '{company_tax_number}',
            '{company_phone}',
            '{company_address}',
        ];
    }


}
