<x-layout>
    <div class="bg-white rounded shadow">
        <div class="p-4 border-b">
            <h2 class="font-bold text-lg mb-3">📋 Sales History</h2>

            <form method="GET" action="{{ route('sales.index') }}" class="grid md:grid-cols-4 gap-2">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Customer naam search karo..."
                    class="border rounded p-2 text-sm md:col-span-2">

                <input type="date" name="from_date" value="{{ request('from_date') }}"
                    class="border rounded p-2 text-sm">

                <input type="date" name="to_date" value="{{ request('to_date') }}"
                    class="border rounded p-2 text-sm">

                <div class="md:col-span-4 flex gap-2">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded text-sm font-medium">
                        Search
                    </button>
                    <a href="{{ route('sales.index') }}" class="bg-gray-200 px-4 py-2 rounded text-sm font-medium">
                        Clear
                    </a>
                </div>
            </form>
        </div>

        <div class="divide-y">
            @forelse($sales as $sale)
                <a href="{{ route('sales.receipt', $sale->id) }}"
                    class="flex justify-between items-center p-4 hover:bg-gray-50">
                    <div>
                        <p class="font-medium">
                            {{ $sale->customer_name ?: 'Walk-in Customer' }}
                        </p>
                        <p class="text-sm text-gray-500">
                            {{ $sale->created_at->format('d-M-Y h:i A') }} •
                            {{ $sale->items->count() }} items
                        </p>
                    </div>
                    <p class="font-bold text-lg">Rs. {{ number_format($sale->total_amount, 2) }}</p>
                </a>
            @empty
                <p class="p-6 text-center text-gray-400">Koi sale nahi mili</p>
            @endforelse
        </div>

        <div class="p-4">
            {{ $sales->links() }}
        </div>
    </div>
</x-layout>