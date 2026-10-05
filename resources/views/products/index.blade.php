<x-layout>
    <div class="grid md:grid-cols-3 gap-4">
        <!-- Add Product Form -->
        <div class="bg-white p-4 rounded shadow md:col-span-1">
            <h2 class="font-bold mb-3">Naya Item Add Karo</h2>
            <form method="POST" action="{{ route('products.store') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="text-sm text-gray-600">Item Ka Naam</label>
                    <input type="text" name="name" required
                        class="w-full border rounded p-2" placeholder="e.g. Chai Patti 250g">
                </div>
                <div>
                    <label class="text-sm text-gray-600">Price (Rs.)</label>
                    <input type="number" step="0.01" name="price" required
                        class="w-full border rounded p-2" placeholder="150">
                </div>
                <div>
                    <label class="text-sm text-gray-600">Stock Quantity</label>
                    <input type="number" name="stock_qty" required
                        class="w-full border rounded p-2" placeholder="20">
                </div>
                <div>
                    <label class="text-sm text-gray-600">Barcode (optional)</label>
                    <input type="text" name="barcode"
                        class="w-full border rounded p-2" placeholder="Agar hai to">
                </div>
                <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded w-full font-medium">
                    Add Karo
                </button>
            </form>
        </div>

        <!-- Products List with Live Search -->
        <div class="bg-white p-4 rounded shadow md:col-span-2"
            x-data="{
                search: '',
                products: {{ Js::from($products) }},
                editingId: null,
                get filtered() {
                    return this.products.filter(p =>
                        p.name.toLowerCase().includes(this.search.toLowerCase())
                    )
                }
            }">
            <h2 class="font-bold mb-3">Sab Items</h2>
            <input type="text" x-model="search" placeholder="Search item..."
                class="w-full border rounded p-2 mb-3">

            <div class="space-y-2 max-h-96 overflow-y-auto">
                <template x-for="product in filtered" :key="product.id">
                    <div class="border-b py-2">
                        <!-- Normal View -->
                        <div x-show="editingId !== product.id" class="flex justify-between items-center">
                            <div>
                                <p class="font-medium" x-text="product.name"></p>
                                <p class="text-sm text-gray-500">
                                    Rs. <span x-text="product.price"></span> |
                                    Stock: <span x-text="product.stock_qty"></span>
                                </p>
                            </div>
                            <div class="flex gap-2">
                                <button @click="editingId = product.id"
                                    class="text-blue-600 text-sm font-medium">Edit</button>
                                <form :action="`/products/${product.id}`" method="POST"
                                    @submit="if(!confirm('Delete karna hai?')) $event.preventDefault()">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="text-red-500 text-sm font-medium">Delete</button>
                                </form>
                            </div>
                        </div>

                        <!-- Edit Form -->
                        <form x-show="editingId === product.id" method="POST"
                            :action="`/products/${product.id}`" class="space-y-2 bg-gray-50 p-3 rounded">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="PUT">

                            <input type="text" name="name" :value="product.name"
                                class="w-full border rounded p-1.5 text-sm" placeholder="Naam">
                            <div class="flex gap-2">
                                <input type="number" step="0.01" name="price" :value="product.price"
                                    class="w-1/2 border rounded p-1.5 text-sm" placeholder="Price">
                                <input type="number" name="stock_qty" :value="product.stock_qty"
                                    class="w-1/2 border rounded p-1.5 text-sm" placeholder="Stock">
                            </div>
                            <div class="flex gap-2">
                                <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded text-sm">
                                    Save
                                </button>
                                <button type="button" @click="editingId = null"
                                    class="bg-gray-300 px-3 py-1 rounded text-sm">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </template>

                <p x-show="filtered.length === 0" class="text-gray-400 text-center py-4">
                    Koi item nahi mila
                </p>
            </div>
        </div>
    </div>
</x-layout>