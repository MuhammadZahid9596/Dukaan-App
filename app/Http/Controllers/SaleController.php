<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function create()
    {
        $products = Product::where('user_id', auth()->id())
            ->where('stock_qty', '>', 0)
            ->get();

        return view('sales.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $userId = auth()->id();

        $sale = DB::transaction(function () use ($validated, $userId) {
            $totalAmount = 0;
            $saleItemsData = [];

            foreach ($validated['items'] as $item) {
                $product = Product::where('user_id', $userId)
                    ->lockForUpdate()
                    ->findOrFail($item['product_id']);

                if ($product->stock_qty < $item['quantity']) {
                    throw new \Exception("'{$product->name}' ka stock kam hai. Sirf {$product->stock_qty} bacha hai.");
                }

                $lineTotal = $product->price * $item['quantity'];
                $totalAmount += $lineTotal;

                $saleItemsData[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $item['quantity'],
                    'price_at_sale' => $product->price,
                ];

                $product->decrement('stock_qty', $item['quantity']);
            }

            $sale = Sale::create([
                'user_id' => $userId,
                'total_amount' => $totalAmount,
                'customer_name' => $validated['customer_name'] ?? null,
            ]);

            foreach ($saleItemsData as $data) {
                $sale->items()->create($data);
            }

            return $sale;
        });

        return redirect()->route('sales.receipt', $sale->id);
    }

    public function receipt(Sale $sale)
    {
        abort_if($sale->user_id !== auth()->id(), 403);

        $sale->load('items');
        return view('sales.receipt', compact('sale'));
    }

    public function index(Request $request)
    {
        $query = Sale::where('user_id', auth()->id())->with('items');

        if ($request->filled('search')) {
            $query->where('customer_name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $sales = $query->latest()->paginate(20)->withQueryString();

        return view('sales.index', compact('sales'));
    }
}