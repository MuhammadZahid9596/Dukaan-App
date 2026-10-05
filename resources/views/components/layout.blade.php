<!DOCTYPE html>
<html lang="ur">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#2563eb">
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/icons/icon-192.png">
    <title>Dukaan App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">
    <nav class="bg-white shadow p-4 flex justify-between items-center">
        <img src="{{ asset('logo') }}" alt="Dukaan App" class="w-500 h-7">
        <div class="space-x-4">
            <a href="{{ route('products.index') }}" class="text-blue-600 font-medium">Items</a>
            <a href="{{ route('sales.create') }}" class="text-green-600 font-bold">🛒 Nayi Sale</a>
            <a href="{{ route('sales.index') }}" class="text-gray-700 font-medium">📋 History</a>

            @auth
                <span class="text-sm text-gray-500">{{ auth()->user()->shop_name }}</span>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-red-600 font-medium">Logout</button>
                </form>
            @endauth
        </div>
    </nav>

    @if(session('show_subscription_status') && auth()->user())
        <div x-data="{ open: true }" x-show="open"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg p-6 max-w-sm w-full">
                @php $user = auth()->user(); @endphp

                @if($user->onTrial())
                    <div class="text-4xl mb-3 text-center">🎁</div>
                    <h3 class="font-bold text-lg text-center mb-2">Free Trial Chal Raha Hai</h3>
                    <p class="text-center text-gray-600 mb-4">
                        Apke trial me <strong>{{ $user->trialDaysLeft() }} din</strong> baqi hain.
                    </p>
                @elseif($user->hasActiveSubscription())
                    <div class="text-4xl mb-3 text-center">✅</div>
                    <h3 class="font-bold text-lg text-center mb-2">Subscription Active Hai</h3>
                    <p class="text-center text-gray-600 mb-4">
                        Shukriya! Apka account active hai.
                    </p>
                @endif

                <button @click="open = false"
                    class="bg-blue-600 text-white w-full py-2 rounded font-medium">
                    Theek Hai
                </button>
            </div>
        </div>
    @endif
    <main class="p-4 max-w-4xl mx-auto">
        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{ $slot }}
    </main>
</body>
</html>
<!-- <script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js')
                .then(() => console.log('Service Worker registered'))
                .catch((err) => console.log('Service Worker failed:', err));
        });
    }
</script> -->