<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice #ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 40px; }
        .header { text-align: center; border-bottom: 3px solid #2563eb; padding-bottom: 20px; margin-bottom: 30px; }
        .header h1 { color: #2563eb; margin: 0; }
        .info { margin: 20px 0; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .info-item { padding: 10px; background: #f5f5f5; }
        .label { font-size: 12px; color: #666; text-transform: uppercase; }
        .value { font-size: 16px; font-weight: bold; }
        .amount-box { background: #2563eb; color: white; padding: 20px; margin: 20px 0; }
        .amount-row { display: flex; justify-content: space-between; padding: 8px 0; }
        .total { font-size: 24px; font-weight: bold; border-top: 2px solid white; margin-top: 10px; padding-top: 15px; }
        .footer { margin-top: 40px; text-align: center; color: #666; font-size: 14px; border-top: 2px solid #e5e7eb; padding-top: 20px; }
        @media print { body { padding: 20px; } }
    </style>
</head>
<body>
    <button onclick="window.print()" style="position:fixed;top:20px;right:20px;padding:12px 24px;background:#2563eb;color:white;border:none;border-radius:8px;cursor:pointer;">🖨️ Print</button>
    
    <div class="header">
        <h1>INVOICE</h1>
        <p style="font-size: 20px; color: #2563eb; font-weight: bold;">#ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</p>
        <p>Date: {{ \Carbon\Carbon::parse($order->created_at)->format('F d, Y') }}</p>
    </div>

    <div class="info">
        <h3 style="color: #2563eb;">Bill To:</h3>
        <p><strong>{{ $order->buyer->name ?? 'N/A' }}</strong></p>
        <p>{{ $order->buyer->email ?? 'N/A' }}</p>
    </div>

    <div class="info">
        <h3 style="color: #2563eb;">From:</h3>
        <p><strong>{{ $order->farmer->name ?? 'N/A' }}</strong></p>
        <p>{{ $order->farmer->email ?? 'N/A' }}</p>
    </div>

    <div class="info">
        <h3 style="color: #2563eb;">Order Details</h3>
        <div class="info-grid">
            <div class="info-item"><p class="label">Crop</p><p class="value">{{ $order->crop_name ?? 'N/A' }}</p></div>
            <div class="info-item"><p class="label">Quantity</p><p class="value">{{ number_format($order->agreed_quantity ?? 0, 2) }} kg</p></div>
            <div class="info-item"><p class="label">Price per Unit</p><p class="value">₹{{ number_format($order->price_per_unit ?? 0, 2) }}</p></div>
            <div class="info-item"><p class="label">Delivery Date</p><p class="value">{{ $order->delivery_date ? \Carbon\Carbon::parse($order->delivery_date)->format('F d, Y') : 'N/A' }}</p></div>
        </div>
    </div>

    <div class="amount-box">
        <div class="amount-row"><span>Subtotal:</span><span>₹{{ number_format($order->total_amount ?? 0, 2) }}</span></div>
        <div class="amount-row"><span>Total Paid:</span><span>₹{{ number_format($order->payments->where('status', 'completed')->sum('amount'), 2) }}</span></div>
        <div class="amount-row"><span>Balance Due:</span><span>₹{{ number_format(($order->total_amount ?? 0) - $order->payments->where('status', 'completed')->sum('amount'), 2) }}</span></div>
        <div class="amount-row total"><span>TOTAL AMOUNT:</span><span>₹{{ number_format($order->total_amount ?? 0, 2) }}</span></div>
    </div>

    <div class="info">
        <h3 style="color: #2563eb;">Payment History</h3>
        @foreach($order->payments as $payment)
            <div style="padding: 10px; background: #f5f5f5; margin: 10px 0;">
                <div style="display: flex; justify-content: space-between;">
                    <div>
                        <strong>{{ ucfirst($payment->payment_type) }} Payment</strong>
                        <p style="margin: 5px 0; font-size: 12px; color: #666;">{{ $payment->transaction_id }} | {{ $payment->payment_method }}</p>
                    </div>
                    <div style="text-align: right;">
                        <strong>₹{{ number_format($payment->amount, 2) }}</strong>
                        <p style="margin: 5px 0; font-size: 12px; color: green;">{{ $payment->status }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="footer">
        <p><strong>AgriConnect - Assured Contract Farming System</strong></p>
        <p>Generated on {{ now()->format('F d, Y \a\t g:i A') }}</p>
    </div>
</body>
</html>
