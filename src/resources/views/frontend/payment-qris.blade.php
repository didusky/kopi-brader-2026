<h2>Scan QR untuk bayar</h2>

{!! QrCode::size(250)->generate('ORDER-'.$order->id) !!}

<br><br>

<a href="{{ route('success', $order->id) }}">
    <button>Saya Sudah Bayar</button>
</a>
