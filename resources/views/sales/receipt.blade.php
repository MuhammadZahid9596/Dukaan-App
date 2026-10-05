<!DOCTYPE html>
<html lang="ur">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#2563eb">
    <link rel="manifest" href="/manifest.json">
    <title>Receipt #{{ $sale->id }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            #receipt { box-shadow: none !important; }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen py-6">

    <!-- Action Buttons (print me nahi dikhenge) -->
    <div class="no-print max-w-sm mx-auto mb-4 flex gap-2 px-4">
        <button onclick="window.print()"
            class="bg-blue-600 text-white flex-1 py-3 rounded font-bold">
            🖨️ Print
        </button>
        <button onclick="saveAsImage()"
            class="bg-green-600 text-white flex-1 py-3 rounded font-bold">
            💾 Save
        </button>
    </div>

    <div class="no-print max-w-sm mx-auto mb-4 flex gap-2 px-4">
        <a href="{{ route('sales.create') }}"
            class="bg-gray-700 text-white flex-1 py-3 rounded font-bold text-center">
            ← Wapas (Nayi Sale)
        </a>
        <a href="{{ route('products.index') }}"
            class="bg-gray-400 text-white flex-1 py-3 rounded font-bold text-center">
            Items
        </a>
    </div>

    <!-- Receipt -->
    <div id="receipt" class="bg-white max-w-sm mx-auto p-5 shadow-lg font-mono text-sm">
        <div class="text-center border-b border-dashed border-gray-400 pb-3 mb-3">
            <h1 class="text-lg font-bold">🏪 Dukaan Store</h1>
            <p class="text-xs text-gray-500">Receipt #{{ str_pad($sale->id, 5, '0', STR_PAD_LEFT) }}</p>
            <p class="text-xs text-gray-500">{{ $sale->created_at->format('d-M-Y h:i A') }}</p>
        </div>

        <div class="space-y-2 border-b border-dashed border-gray-400 pb-3 mb-3">
            @foreach($sale->items as $item)
                <div>
                    <p class="font-medium">{{ $item->product_name }}</p>
                    <div class="flex justify-between text-xs text-gray-600">
                        <span>{{ $item->quantity }} x Rs. {{ number_format($item->price_at_sale, 2) }}</span>
                        <span class="font-medium text-black">
                            Rs. {{ number_format($item->quantity * $item->price_at_sale, 2) }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="flex justify-between text-base font-bold mb-3">
            <span>Total</span>
            <span>Rs. {{ number_format($sale->total_amount, 2) }}</span>
        </div>

        <div class="text-center text-xs text-gray-500 border-t border-dashed border-gray-400 pt-3">
            <p class="text-xs text-gray-500">{{ $sale->created_at->format('d-M-Y h:i A') }}</p>
            @if($sale->customer_name)
                <p class="text-sm font-medium mt-1">Customer: {{ $sale->customer_name }}</p>
            @endif

            <p>Shukriya! Phir tashreef layen 🙏</p>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        function saveAsImage() {
            const receipt = document.getElementById('receipt');

            html2canvas(receipt, { scale: 2, backgroundColor: '#ffffff' }).then(canvas => {
                canvas.toBlob(async (blob) => {
                    const fileName = 'receipt-{{ $sale->id }}.png';
                    const file = new File([blob], fileName, { type: 'image/png' });

                    // Agar mobile pe Web Share API support ho, to share dialog khulega
                    if (navigator.canShare && navigator.canShare({ files: [file] })) {
                        try {
                            await navigator.share({
                                files: [file],
                                title: 'Receipt'
                            });
                            return;
                        } catch (e) {
                            // user ne cancel kiya, ya error aayi - fallback download
                        }
                    }

                    // Fallback: seedha download
                    const link = document.createElement('a');
                    link.download = fileName;
                    link.href = URL.createObjectURL(blob);
                    link.click();
                });
            });
        }
    </script>
</body>
</html>