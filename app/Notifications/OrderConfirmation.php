<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class OrderConfirmation extends Notification
{
    use Queueable;

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Order Confirmation - ' . $this->order->order_id)
            ->greeting('Hello ' . $this->order->customer_name . '!')
            ->line('Thank you for your order. We have received your order and will process it shortly.')
            ->line('Order ID: ' . $this->order->order_id)
            ->line('Total Amount: BDT ' . number_format($this->order->total, 2))
            ->action('View Order', url('/orders/' . $this->order->order_id))
            ->line('Thank you for shopping with us!');
    }
}
