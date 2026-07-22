<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
    <style>
        body { font-family: "Palatino Linotype", "Book Antiqua", Palatino, serif; margin: 0; }
        .container { padding: 20px 40px; }
        .inv-title { padding: 10px; border: 1px solid silver; text-align: center; margin-bottom: 20px; }
        .inv-header table { width: 100%; border-collapse: collapse; border: 1px solid silver; }
        .inv-header table th, .inv-header table td { text-align: right; padding: 8px; border: 1px solid silver; }
        .inv-body table { width: 100%; border: 1px solid silver; border-collapse: collapse; }
        .inv-body table th, .inv-body table td { padding: 10px; border: 1px solid silver; }
        .inv-footer table { width: 30%; float: right; border: 1px solid silver; border-collapse: collapse; }
        .inv-footer table th, .inv-footer table td { padding: 8px; text-align: right; border: 1px solid silver; }
    </style>
</head>
<body>
<div class="container">
    <div class="inv-title">
        <h1 class="no-margin" style="color:black;">Invoice # {{ $orderId }}</h1>
    </div>
    <div class="inv-header">
        <table>
            <tr><th style="text-align:left;color:black;">Date</th><td style="color:black;">{{ $order->create_date }}</td></tr>
            <tr><th style="text-align:left;color:black;">Transaction #</th><td style="color:black;">{{ $transactionId }}</td></tr>
            <tr><th style="text-align:left;color:black;">Customer Name</th><td style="color:black;">{{ $customer->firstname }} {{ $customer->lastname }}</td></tr>
            <tr><th style="text-align:left;color:black;">Address</th><td style="color:black;">{{ $customer->address }}</td></tr>
        </table>
    </div>
    <div class="inv-body">
        <table>
            <thead>
                <th style="color:black;">Sr #</th>
                <th style="color:black;">Item</th>
                <th style="color:black;">Type</th>
                <th style="color:black;">Quantity</th>
                <th style="color:black;">Price</th>
                <th style="color:black;">Sub total</th>
            </thead>
            <tbody>
                @foreach ($orderDetails as $index => $detail)
                    <tr>
                        <td style="color:black;">{{ $index + 1 }}</td>
                        <td style="color:black;">{{ $detail->item }}</td>
                        <td style="color:black;">{{ $detail->type }}</td>
                        <td style="color:black;">{{ $detail->qty }}</td>
                        <td style="color:black;">${{ number_format($detail->price, 2) }}</td>
                        <td style="color:black;">${{ number_format($detail->sub_total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="inv-footer">
        <table>
            <tr><th style="color:black;">Sub total</th><td style="color:black;">${{ number_format($subTotal, 2) }}</td></tr>
            <tr><th style="color:black;">Discount</th><td style="color:black;">${{ number_format($discount, 2) }}</td></tr>
            <tr><th style="color:black;">Grand total</th><td style="color:black;">${{ number_format($amount, 2) }}</td></tr>
        </table>
    </div>
</div>
</body>
</html>