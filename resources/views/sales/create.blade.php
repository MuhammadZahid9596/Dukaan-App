<x-layout>
    <div x-data="billing()" class="grid md:grid-cols-3 gap-4">

        <!-- Product Search + List -->
        <div class="bg-white p-4 rounded shadow md:col-span-2">
            <input type="text" x-model="search" placeholder="Item search karo..."
                class="w-full border rounded p-3 mb-3 text-lg" autofocus>

            <div class="space-y-2 max-h-[28rem] overflow-y-auto">
                <template x-for="product in filtered" :key="product.id">
                    <div @click="addToCart(product)"
                        class="flex justify-between items-center border rounded p-3 cursor-pointer hover:bg-blue-50">
                        <div>
                            <p class="font-medium" x-text="product.name"></p>
                            <p class="text-sm text-gray-500">
                                Rs. <span x-text="product.price"></span> |
                                Stock: <span x-text="product.stock_qty"></span>
                            </p>
                        </div>
                        <span class="text-blue-600 text-2xl font-bold">+</span>
                    </div>
                </template>

                <p x-show="filtered.length === 0" class="text-gray-400 text-center py-4">
                    Koi item nahi mila
                </p>
            </div>
        </div>

        <!-- Cart -->
        <div class="bg-white p-4 rounded shadow md:col-span-1 flex flex-col">
            <h2 class="font-bold mb-3 text-lg">🛒 Cart</h2>

            <div class="flex-1 space-y-2 overflow-y-auto max-h-80">
                <template x-for="(item, index) in cart" :key="item.product_id">
                    <div class="border-b pb-2">
                        <div class="flex justify-between items-center">
                            <p class="font-medium text-sm" x-text="item.name"></p>
                            <button @click="removeFromCart(index)" class="text-red-500 text-sm">✕</button>
                        </div>
                        <div class="flex justify-between items-center mt-1">
                            <div class="flex items-center gap-2">
                                <button @click="decreaseQty(index)"
                                    class="bg-gray-200 w-7 h-7 rounded font-bold">-</button>
                                <span x-text="item.quantity" class="w-6 text-center"></span>
                                <button @click="increaseQty(index)"
                                    class="bg-gray-200 w-7 h-7 rounded font-bold">+</button>
                            </div>
                            <p class="font-medium" x-text="'Rs. ' + (item.price * item.quantity)"></p>
                        </div>
                    </div>
                </template>

                <p x-show="cart.length === 0" class="text-gray-400 text-center py-6">
                    Cart khaali hai
                </p>
            </div>

            <div class="border-t pt-3 mt-3">
                <input type="text" x-model="customerName" placeholder="Customer ka naam (optional)"
                class="w-full border rounded p-2 mb-3 text-sm">

                <div class="flex justify-between font-bold text-xl mb-3">
                    <span>Total</span>
                    <span x-text="'Rs. ' + total"></span>
                </div>

                <p x-show="error" x-text="error" class="text-red-600 text-sm mb-2"></p>

                <button @click="completeSale()" :disabled="cart.length === 0 || loading"
                    class="bg-green-600 text-white w-full py-3 rounded font-bold text-lg disabled:bg-gray-300">
                    <span x-show="!loading">Sale Complete Karo</span>
                    <span x-show="loading">Processing...</span>
                </button>
            </div>
        </div>
    </div>

    <script>
        function billing() {
            return {
                search: '',
                products: {{ Js::from($products) }},
                cart: [],
                customerName: '',
                error: '',
                loading: false,

                get filtered() {
                    if (!this.search) return this.products;
                    return this.products.filter(p =>
                        p.name.toLowerCase().includes(this.search.toLowerCase())
                    );
                },

                get total() {
                    return this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                },

                addToCart(product) {
                    const existing = this.cart.find(item => item.product_id === product.id);
                    if (existing) {
                        if (existing.quantity < product.stock_qty) {
                            existing.quantity++;
                        }
                    } else {
                        this.cart.push({
                            product_id: product.id,
                            name: product.name,
                            price: parseFloat(product.price),
                            quantity: 1,
                            max_stock: product.stock_qty
                        });
                    }
                    this.search = '';
                },

                increaseQty(index) {
                    const item = this.cart[index];
                    if (item.quantity < item.max_stock) {
                        item.quantity++;
                    }
                },

                decreaseQty(index) {
                    if (this.cart[index].quantity > 1) {
                        this.cart[index].quantity--;
                    }
                },

                removeFromCart(index) {
                    this.cart.splice(index, 1);
                },

                async completeSale() {
                    this.loading = true;
                    this.error = '';

                    try {
                        const response = await fetch('{{ route('sales.store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                customer_name: this.customerName,
                                items: this.cart.map(item => ({
                                    product_id: item.product_id,
                                    quantity: item.quantity
                                }))
                            })
                        });

                        if (response.redirected) {
                            window.location.href = response.url;
                        } else {
                            const data = await response.json();
                            this.error = data.message || 'Kuch ghalat ho gaya';
                        }
                    } catch (e) {
                        this.error = 'Network error, dobara try karo';
                    } finally {
                        this.loading = false;
                    }
                }
            }
        }
    </script>
</x-layout>