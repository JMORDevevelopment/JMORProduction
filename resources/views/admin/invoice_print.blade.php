<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice #{{ $transaction->order_id }}</title>
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">
</head>
<body style="background:#f4f4f4;">
    <div class="container" style="margin-top:20px;margin-bottom:40px;">
        <div class="clearfix" style="margin-bottom:15px;">
            <a href="{{ url()->previous() }}" class="btn btn-default btn-sm">&larr; Back</a>
            <button onclick="window.print()" class="btn btn-primary btn-sm pull-right">Print</button>
        </div>

        @include('dashboard.partials.invoice_body')
    </div>
</body>
</html>
