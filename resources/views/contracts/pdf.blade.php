<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contract Agreement #{{ str_pad($contract->id, 4, '0', STR_PAD_LEFT) }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #1f2937;
            padding: 40px;
            background: #ffffff;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 30px;
            margin-bottom: 40px;
        }
        .header h1 {
            font-size: 32px;
            color: #2563eb;
            margin-bottom: 10px;
        }
        .header p {
            color: #6b7280;
            font-size: 16px;
        }
        .contract-id {
            font-size: 20px;
            color: #2563eb;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-pending { background: #dbeafe; color: #1e40af; }
        .status-signed { background: #d1fae5; color: #065f46; }
        .status-active { background: #d1fae5; color: #065f46; }
        .status-completed { background: #e9d5ff; color: #6b21a8; }
        .section {
            margin-bottom: 30px;
        }
        .section-title {
            font-size: 20px;
            font-weight: bold;
            color: #2563eb;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 8px;
            margin-bottom: 20px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .info-item {
            padding: 15px;
            background: #f9fafb;
            border-left: 4px solid #2563eb;
        }
        .info-label {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 5px;
        }
        .info-value {
            font-size: 16px;
            color: #1f2937;
            font-weight: 600;
        }
        .amount-box {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }
        .amount-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        .amount-row:last-child {
            border-bottom: none;
            font-size: 24px;
            font-weight: bold;
            margin-top: 10px;
            padding-top: 15px;
            border-top: 2px solid white;
        }
        .terms-list {
            list-style: none;
            padding: 0;
        }
        .terms-list li {
            padding: 12px 15px;
            background: #f9fafb;
            margin-bottom: 10px;
            border-left: 4px solid #2563eb;
        }
        .signature-section {
            margin-top: 50px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
        }
        .signature-box {
            text-align: center;
            padding: 20px;
        }
        .signature-line {
            border-top: 2px solid #1f2937;
            margin-top: 60px;
            padding-top: 10px;
        }
        .footer {
            margin-top: 50px;
            padding-top: 30px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 24px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .print-button:hover {
            background: #1d4ed8;
        }
        @media print {
            .print-button { display: none; }
            body { padding: 20px; }
        }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()">🖨️ Print / Save PDF</button>

    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>📄 Contract Agreement</h1>
            <p class="contract-id">Contract #{{ str_pad($contract->id, 4, '0', STR_PAD_LEFT) }}</p>
            <span class="status-badge status-{{ $contract->status }}">{{ $contract->status }}</span>
            <p style="margin-top: 15px;">Issued on {{ \Carbon\Carbon::parse($contract->created_at)->format('F d, Y') }}</p>
        </div>

        <!-- Parties Information -->
        <div class="section">
            <h2 class="section-title">Parties to the Agreement</h2>
            <div class="info-grid">
                <div class="info-item">
                    <p class="info-label">Buyer</p>
                    <p class="info-value">{{ $contract->buyer->name }}</p>
                    <p style="color: #6b7280; font-size: 14px;">{{ $contract->buyer->email }}</p>
                </div>
                <div class="info-item">
                    <p class="info-label">Farmer</p>
                    <p class="info-value">{{ $contract->farmer->name }}</p>
                    <p style="color: #6b7280; font-size: 14px;">{{ $contract->farmer->email }}</p>
                </div>
            </div>
        </div>

        <!-- Contract Details -->
        <div class="section">
            <h2 class="section-title">Contract Details</h2>
            <div class="info-grid">
                <div class="info-item">
                    <p class="info-label">Crop Name</p>
                    <p class="info-value">{{ $contract->crop_name }}</p>
                </div>
                <div class="info-item">
                    <p class="info-label">Quantity Required</p>
                    <p class="info-value">{{ number_format($contract->agreed_quantity, 2) }} kg</p>
                </div>
                <div class="info-item">
                    <p class="info-label">Price per Unit</p>
                    <p class="info-value">₹{{ number_format($contract->price_per_unit, 2) }} / kg</p>
                </div>
                <div class="info-item">
                    <p class="info-label">Delivery Date</p>
                    <p class="info-value">{{ \Carbon\Carbon::parse($contract->delivery_date)->format('F d, Y') }}</p>
                </div>
                <div class="info-item">
                    <p class="info-label">Delivery Location</p>
                    <p class="info-value">{{ $contract->delivery_location }}</p>
                </div>
                <div class="info-item">
                    <p class="info-label">Contract Period</p>
                    <p class="info-value">
                        {{ \Carbon\Carbon::parse($contract->contract_start_date)->format('M d, Y') }} - 
                        {{ \Carbon\Carbon::parse($contract->contract_end_date)->format('M d, Y') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Payment Information -->
        <div class="section">
            <h2 class="section-title">Payment Information</h2>
            <div class="amount-box">
                <div class="amount-row">
                    <span>Quantity</span>
                    <span>{{ number_format($contract->agreed_quantity, 2) }} kg</span>
                </div>
                <div class="amount-row">
                    <span>Price per Unit</span>
                    <span>₹{{ number_format($contract->price_per_unit, 2) }}</span>
                </div>
                <div class="amount-row">
                    <span>Advance Payment ({{ $contract->advance_percentage }}%)</span>
                    <span>₹{{ number_format($contract->advance_amount, 2) }}</span>
                </div>
                <div class="amount-row">
                    <span>Final Payment</span>
                    <span>₹{{ number_format($contract->final_payment, 2) }}</span>
                </div>
                <div class="amount-row">
                    <span>TOTAL CONTRACT VALUE</span>
                    <span>₹{{ number_format($contract->total_amount, 2) }}</span>
                </div>
            </div>
            <div class="info-item">
                <p class="info-label">Payment Terms</p>
                <p class="info-value">{{ $contract->payment_terms }}</p>
            </div>
        </div>

        <!-- Additional Terms -->
        @if($contract->additional_terms)
        <div class="section">
            <h2 class="section-title">Additional Terms & Conditions</h2>
            <ul class="terms-list">
                @foreach(explode("\n", $contract->additional_terms) as $term)
                    @if(trim($term))
                        <li>{{ trim($term) }}</li>
                    @endif
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Signatures -->
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-line">
                    <p><strong>{{ $contract->buyer->name }}</strong></p>
                    <p style="color: #6b7280;">Buyer</p>
                    <p style="color: #6b7280; font-size: 14px;">Date: {{ \Carbon\Carbon::parse($contract->created_at)->format('F d, Y') }}</p>
                </div>
            </div>
            <div class="signature-box">
                <div class="signature-line">
                    <p><strong>{{ $contract->farmer->name }}</strong></p>
                    <p style="color: #6b7280;">Farmer</p>
                    <p style="color: #6b7280; font-size: 14px;">Date: _______________</p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>AgriConnect - Assured Contract Farming System</strong></p>
            <p style="margin-top: 10px;">This is a legally binding agreement between the parties mentioned above.</p>
            <p style="margin-top: 5px;">Generated on {{ now()->format('F d, Y \\a\\t g:i A') }}</p>
        </div>
    </div>
</body>
</html>
