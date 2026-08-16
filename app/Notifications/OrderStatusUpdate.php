<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class OrderStatusUpdate extends Notification
{
    use Queueable;

    protected $order;
    protected $oldStatus;

    public function __construct(Order $order, $oldStatus)
    {
        $this->order = $order;
        $this->oldStatus = $oldStatus;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Order Status Update - ' . $this->order->order_id)
            ->greeting('Hello ' . $this->order->customer_name . '!')
            ->line('Your order status has been updated.')
            ->line('Old Status: ' . ucfirst($this->oldStatus))
            ->line('New Status: ' . ucfirst($this->order->order_status))
            ->action('View Order', url('/orders/' . $this->order->order_id))
            ->line('Thank you for shopping with us!');
    }
}
