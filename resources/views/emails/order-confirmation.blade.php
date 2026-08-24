<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
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
        .header p {
            color: #6b7280;
            margin: 5px 0 0;
        }
        .success-icon {
            text-align: center;
            margin: 20px 0;
        }
        .success-icon svg {
            width: 60px;
            height: 60px;
            color: #16a34a;
        }
        .order-details {
            background: #f9fafb;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .order-details h3 {
            margin: 0 0 10px;
            color: #1f2937;
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
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        .items-table th {
            background: #f9fafb;
            padding: 10px;
            text-align: left;
            font-size: 12px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
        }
        .items-table td {
            padding: 10px;
            border-bottom: 1px solid #f3f4f6;
        }
        .items-table .total-row td {
            font-weight: 700;
            font-size: 16px;
            color: #0637A1;
            border-top: 2px solid #e5e7eb;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 2px solid #f0f0f0;
            margin-top: 20px;
            color: #6b7280;
            font-size: 14px;
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
            <h1>{{ setting('site_name', 'SafeX Engineering') }}</h1>
            <p>Order Confirmation</p>
        </div>

        <!-- Success Icon -->
        <div class="success-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>

        <!-- Greeting -->
        <h2 style="text-align: center; color: #1f2937;">Thank You for Your Order!</h2>
        <p style="text-align: center; color: #6b7280;">Your order has been placed successfully.</p>

        <!-- Order Details -->
        <div class="order-details">
            <h3>Order Details</h3>
            <div class="row">
                <span class="label">Order Number</span>
                <span class="value">{{ $order->order_id }}</span>
            </div>
            <div class="row">
                <span class="label">Order Date</span>
                <span class="value">{{ $order->created_at->format('d M Y, h:i A') }}</span>
            </div>
            <div class="row">
                <span class="label">Payment Method</span>
                <span class="value">{{ ucfirst($order->payment_method) }}</span>
            </div>
            <div class="row">
                <span class="label">Order Status</span>
                <span class="value" style="color: #f59e0b;">{{ ucfirst($order->order_status) }}</span>
            </div>
        </div>

        <!-- Order Items -->
        <h3 style="margin: 20px 0 10px;">Order Items</h3>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th style="text-align: center;">Qty</th>
                    <th style="text-align: right;">Price</th>
                    <th style="text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product->name ?? 'Product' }}</td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td style="text-align: right;">{{ setting('currency', 'BDT') }} {{ number_format($item->price, 2) }}</td>
                    <td style="text-align: right;">{{ setting('currency', 'BDT') }} {{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="3" style="text-align: right;">Grand Total</td>
                    <td style="text-align: right;">{{ setting('currency', 'BDT') }} {{ number_format($order->total, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Shipping Address -->
        <div style="margin: 20px 0;">
            <h3 style="margin: 0 0 10px;">Shipping Address</h3>
            <p style="margin: 0; color: #4b5563;">
                {{ $order->customer_name }}<br>
                {{ $order->shipping_address }}<br>
                Phone: {{ $order->customer_phone }}
            </p>
        </div>

        <!-- Action Buttons -->
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('order.track.form') }}" class="btn">Track Your Order</a>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Thank you for shopping with {{ setting('site_name', 'SafeX Engineering') }}!</p>
            <p style="font-size: 12px; color: #9ca3af;">
                If you have any questions, please contact our support team.
            </p>
        </div>
    </div>
</body>
</html>
