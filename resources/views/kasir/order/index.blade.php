<x-kasir-layout>
    <x-slot name="header">
        Pesanan Baru
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8" x-data="posSystem()">
        <!-- Menu Section -->
        <div class="lg:col-span-2">
            <!-- Filter & Search -->
            <div class="flex flex-col md:flex-row gap-4 mb-6">
                <div class="flex-1">
                    <input type="text" x-model="search" @input="filterMenus()" placeholder="Cari menu..." class="w-full rounded-lg border-coffee-brown focus:ring-coffee-gold focus:border-coffee-gold">
                </div>
                <div class="flex gap-2 overflow-x-auto pb-2">
                    <button @click="setCategory(null)" :class="activeCategory === null ? 'bg-coffee-brown text-white' : 'bg-white text-coffee-brown'" class="px-4 py-2 rounded-lg border border-coffee-brown whitespace-nowrap">Semua</button>
                    @foreach($categories as $cat)
                        <button @click="setCategory({{ $cat->id }})" :class="activeCategory === {{ $cat->id }} ? 'bg-coffee-brown text-white' : 'bg-white text-coffee-brown'" class="px-4 py-2 rounded-lg border border-coffee-brown whitespace-nowrap">{{ $cat->name }}</button>
                    @endforeach
                </div>
            </div>

            <!-- Menu Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <template x-for="menu in filteredMenus" :key="menu.id">
                    <div class="bg-white rounded-xl shadow-sm border border-coffee-light overflow-hidden hover:border-coffee-gold cursor-pointer transition-all" @click="addToCart(menu)">
                        <img :src="menu.image" class="w-full h-32 object-cover" alt="">
                        <div class="p-3">
                            <h4 class="font-bold text-coffee-dark truncate" x-text="menu.name"></h4>
                            <p class="text-coffee-brown font-semibold" x-text="formatCurrency(menu.price)"></p>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Cart Section -->
        <div class="bg-white rounded-xl shadow-lg border border-coffee-brown flex flex-col h-[calc(100vh-200px)]">
            <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-coffee-cream rounded-t-xl">
                <h3 class="font-bold text-coffee-dark">Keranjang</h3>
                <button @click="cart = []" class="text-xs text-red-600 font-bold underline">Kosongkan</button>
            </div>

            <div class="flex-1 overflow-y-auto p-4 space-y-4">
                <template x-for="(item, index) in cart" :key="index">
                    <div class="flex gap-3 items-start border-b border-gray-50 pb-3">
                        <div class="flex-1">
                            <p class="font-bold text-sm text-coffee-dark" x-text="item.name"></p>
                            <p class="text-xs text-gray-500" x-text="formatCurrency(item.price)"></p>
                            <input type="text" x-model="item.notes" placeholder="Catatan..." class="mt-1 text-[10px] w-full border-none p-0 focus:ring-0 text-coffee-brown italic">
                        </div>
                        <div class="flex items-center gap-2">
                            <button @click="updateQty(index, -1)" class="w-6 h-6 flex items-center justify-center bg-coffee-light rounded-full text-coffee-dark">-</button>
                            <span class="text-sm font-bold w-4 text-center" x-text="item.qty"></span>
                            <button @click="updateQty(index, 1)" class="w-6 h-6 flex items-center justify-center bg-coffee-brown text-white rounded-full">+</button>
                        </div>
                    </div>
                </template>
                <div x-show="cart.length === 0" class="text-center py-10 text-gray-400 italic">
                    Belum ada menu dipilih
                </div>
            </div>

            <div class="p-4 bg-coffee-cream border-t border-coffee-light rounded-b-xl space-y-2">
                <div class="flex justify-between text-sm">
                    <span>Subtotal</span>
                    <span x-text="formatCurrency(subtotal)"></span>
                </div>
                <div class="flex justify-between text-sm text-coffee-brown">
                    <span>Diskon</span>
                    <span x-text="'- ' + formatCurrency(discount)"></span>
                </div>
                <div class="flex justify-between text-lg font-bold text-coffee-dark border-t border-coffee-brown pt-2 mt-2">
                    <span>Total</span>
                    <span x-text="formatCurrency(total)"></span>
                </div>
                <button @click="showPaymentModal = true" :disabled="cart.length === 0" class="w-full bg-coffee-dark text-white py-3 rounded-lg mt-4 font-bold disabled:opacity-50">BAYAR SEKARANG</button>
            </div>
        </div>

        <!-- Payment Modal -->
        <div x-show="showPaymentModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50" x-cloak>
            <div class="bg-white rounded-2xl w-full max-w-md overflow-hidden shadow-2xl">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-coffee-dark text-white">
                    <h3 class="font-bold text-xl">Pembayaran</h3>
                    <button @click="showPaymentModal = false">&times;</button>
                </div>
                <div class="p-6 space-y-6">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">Pilih Metode Pembayaran</p>
                        <div class="grid grid-cols-3 gap-3">
                            <button @click="paymentMethod = 'cash'" :class="paymentMethod === 'cash' ? 'border-coffee-brown bg-coffee-cream' : 'border-gray-200'" class="border-2 p-3 rounded-xl flex flex-col items-center gap-2">
                                <svg class="w-6 h-6 text-coffee-brown" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                <span class="text-xs font-bold">Tunai</span>
                            </button>
                            <button @click="paymentMethod = 'qris'" :class="paymentMethod === 'qris' ? 'border-coffee-brown bg-coffee-cream' : 'border-gray-200'" class="border-2 p-3 rounded-xl flex flex-col items-center gap-2">
                                <svg class="w-6 h-6 text-coffee-brown" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                <span class="text-xs font-bold">QRIS</span>
                            </button>
                            <button @click="paymentMethod = 'debit'" :class="paymentMethod === 'debit' ? 'border-coffee-brown bg-coffee-cream' : 'border-gray-200'" class="border-2 p-3 rounded-xl flex flex-col items-center gap-2">
                                <svg class="w-6 h-6 text-coffee-brown" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                <span class="text-xs font-bold">Kartu</span>
                            </button>
                        </div>
                    </div>

                    <div x-show="paymentMethod === 'cash'">
                        <p class="text-sm text-gray-500 mb-2">Uang Diterima</p>
                        <div class="relative">
                            <span class="absolute left-3 top-3 font-bold text-coffee-brown">Rp</span>
                            <input type="number" x-model="amountPaid" class="w-full pl-10 pr-4 py-3 rounded-xl border-coffee-brown text-2xl font-bold focus:ring-coffee-gold">
                        </div>
                        <div class="mt-4 flex justify-between items-center p-4 bg-gray-50 rounded-xl">
                            <span class="text-sm text-gray-500">Kembalian</span>
                            <span class="text-xl font-bold text-green-600" x-text="formatCurrency(change)"></span>
                        </div>
                    </div>

                    <div x-show="paymentMethod !== 'cash'" class="p-4 bg-coffee-cream rounded-xl text-center">
                        <p class="text-sm text-coffee-brown font-medium">Silakan lakukan pembayaran melalui terminal QRIS/EDC.</p>
                        <p class="text-2xl font-bold text-coffee-dark mt-2" x-text="formatCurrency(total)"></p>
                    </div>

                    <button @click="processPayment()" :disabled="processing || (paymentMethod === 'cash' && amountPaid < total)" class="w-full bg-coffee-brown text-white py-4 rounded-xl font-bold text-lg shadow-lg disabled:opacity-50">
                        <span x-show="!processing">KONFIRMASI PEMBAYARAN</span>
                        <span x-show="processing">MEMPROSES...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function posSystem() {
            return {
                allMenus: {!! json_encode($menus) !!},
                filteredMenus: {!! json_encode($menus) !!},
                categories: {!! json_encode($categories) !!},
                search: '',
                activeCategory: null,
                cart: [],
                showPaymentModal: false,
                paymentMethod: 'cash',
                amountPaid: 0,
                processing: false,
                promo_id: null,
                discount: 0,

                get subtotal() {
                    return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
                },

                get total() {
                    return Math.max(0, this.subtotal - this.discount);
                },

                get change() {
                    return Math.max(0, this.amountPaid - this.total);
                },

                filterMenus() {
                    this.filteredMenus = this.allMenus.filter(m => {
                        const matchSearch = m.name.toLowerCase().includes(this.search.toLowerCase());
                        const matchCategory = this.activeCategory ? m.category_id === this.activeCategory : true;
                        return matchSearch && matchCategory;
                    });
                },

                setCategory(id) {
                    this.activeCategory = id;
                    this.filterMenus();
                },

                addToCart(menu) {
                    const index = this.cart.findIndex(item => item.id === menu.id);
                    if (index > -1) {
                        this.cart[index].qty++;
                    } else {
                        this.cart.push({
                            id: menu.id,
                            name: menu.name,
                            price: menu.price,
                            qty: 1,
                            notes: ''
                        });
                    }
                    this.amountPaid = this.total; // Auto-fill amount paid for convenience
                },

                updateQty(index, delta) {
                    this.cart[index].qty += delta;
                    if (this.cart[index].qty < 1) {
                        this.cart.splice(index, 1);
                    }
                    this.amountPaid = this.total;
                },

                formatCurrency(value) {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value);
                },

                async processPayment() {
                    this.processing = true;
                    try {
                        const response = await fetch("{{ route('kasir.order.store') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({
                                items: this.cart,
                                payment_method: this.paymentMethod,
                                amount_paid: this.paymentMethod === 'cash' ? this.amountPaid : this.total,
                                promo_id: this.promo_id
                            })
                        });

                        const result = await response.json();
                        if (result.success) {
                            window.location.href = `/kasir/order/${result.transaction_id}/receipt`;
                        } else {
                            alert(result.message);
                        }
                    } catch (error) {
                        alert("Terjadi kesalahan saat memproses pembayaran.");
                    } finally {
                        this.processing = false;
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-kasir-layout>
