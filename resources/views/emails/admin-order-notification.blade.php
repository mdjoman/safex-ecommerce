<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Order Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f8fafc;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }
        .header h1 {
            color: #0637A1;
            font-size: 28px;
            margin: 0;
        }
        .header .badge {
            display: inline-block;
            background: #ef4444;
            color: white;
            padding: 2px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .order-details {
            background: #f9fafb;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .order-details .row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .order-details .row:last-child {
            border-bottom: none;
        }
        .order-details .label {
            color: #6b7280;
        }
        .order-details .value {
            font-weight: 600;
            color: #1f2937;
        }
        .btn {
            display: inline-block;
            padding: 10px 24px;
            background: #0637A1;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
        }
        .btn:hover {
            background: #03246E;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 2px solid #f0f0f0;
            margin-top: 20px;
            color: #6b7280;
            font-size: 14px;
        }
        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }
            .order-details .row {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>🛒 New Order Received</h1>
            <p style="color: #6b7280;">A new order has been placed on your store</p>
            <span class="badge">New Order</span>
        </div>

        <!-- Order Details -->
        <div class="order-details">
            <h3>Order Information</h3>
            <div class="row">
                <span class="label">Order Number</span>
                <span class="value">{{ $order->order_id }}</span>
            </div>
            <div class="row">
                <span class="label">Order Date</span>
                <span class="value">{{ $order->created_at->format('d M Y, h:i A') }}</span>
            </div>
            <div class="row">
                <span class="label">Customer</span>
                <span class="value">{{ $order->customer_name }}</span>
            </div>
            <div class="row">
                <span class="label">Email</span>
                <span class="value">{{ $order->customer_email }}</span>
            </div>
            <div class="row">
                <span class="label">Phone</span>
                <span class="value">{{ $order->customer_phone }}</span>
            </div>
            <div class="row">
                <span class="label">Payment Method</span>
                <span class="value">{{ ucfirst($order->payment_method) }}</span>
            </div>
            <div class="row">
                <span class="label">Total Amount</span>
                <span class="value" style="color: #0637A1; font-size: 18px;">{{ setting('currency', 'BDT') }} {{ number_format($order->total, 2) }}</span>
            </div>
        </div>

        <!-- Items Summary -->
        <h3>Order Items</h3>
        <table style="width: 100%; border-collapse: collapse; margin: 10px 0;">
            @foreach($order->items as $item)
            <tr style="border-bottom: 1px solid #f3f4f6;">
                <td style="padding: 8px 0;">{{ $item->product->name ?? 'Product' }}</td>
                <td style="padding: 8px 0; text-align: center;">× {{ $item->quantity }}</td>
                <td style="padding: 8px 0; text-align: right;">{{ setting('currency', 'BDT') }} {{ number_format($item->price * $item->quantity, 2) }}</td>
            </tr>
            @endforeach
            <tr style="font-weight: 700; font-size: 16px; color: #0637A1; border-top: 2px solid #e5e7eb;">
                <td colspan="2" style="padding: 10px 0;">Total</td>
                <td style="padding: 10px 0; text-align: right;">{{ setting('currency', 'BDT') }} {{ number_format($order->total, 2) }}</td>
            </tr>
        </table>

        <!-- Shipping Address -->
        <div style="margin: 20px 0;">
            <h3>Shipping Address</h3>
            <p style="color: #4b5563; margin: 0;">
                {{ $order->customer_name }}<br>
                {{ $order->shipping_address }}<br>
                Phone: {{ $order->customer_phone }}
            </p>
        </div>

        <!-- Action Buttons -->
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn">View Order Details</a>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>This is an automated notification from {{ setting('site_name', 'SafeX Engineering') }}.</p>
        </div>
    </div>
</body>
</html>
