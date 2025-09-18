<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bill #QP-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid black; padding: 8px; }
        .header { text-align: center; margin-bottom: 20px; }
        .footer { margin-top: 30px; text-align: center; font-size: 12px; }
    </style>
</head>
<body>


    <div class="header" style="text-align: center; margin-bottom: 20px;">
        <img src="{{ public_path('assets/img/quilon-logo.png') }}" alt="Quilon Pickles" style="max-height: 80px; margin-bottom: 10px;">

        <h2>{{ $company['name'] }}</h2>
        <p>
            {{ $company['address'] }} <br>
            Email: {{ $company['email'] }} | WhatsApp: <a href=" https://wa.me/+918891155404" target="_BLANK">{{ $company['whatsapp'] }}</a>
             | Instagram: <a href="https://www.instagram.com/quilon_pickles/" target="_BLANK">{{ $company['instagram'] }}</a> <br>
            <strong>FSSAI No:</strong> {{ $company['fssai_no'] }}
        </p>
    </div>


    <h3>Bill No: QP-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</h3>
    <p><strong>Customer Name:</strong> {{ $order->customer_name }}</p>
    <p><strong>Date:</strong> {{ $order->date }}</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Product</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $key => $item)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $item->product->name ?? 'N/A' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price, 2) }}</td>
                    <td>{{ number_format($item->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3 style="text-align:right; margin-top:20px;">Total: Rs {{ number_format($order->total_amount, 2) }}</h3>

    <div class="footer">
        <p>Thanks for your purchase! </p>
    </div>

</body>
</html>