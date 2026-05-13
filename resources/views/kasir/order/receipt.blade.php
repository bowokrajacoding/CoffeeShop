<x-kasir-layout>
    <x-slot name="header">
        Struk Pembayaran
    </x-slot>

    <div class="max-w-md mx-auto">
        <div class="bg-white p-8 rounded-xl shadow-lg border border-gray-100 receipt-container" id="printableReceipt">
            <!-- Header -->
            <div class="text-center border-b-2 border-dashed border-gray-200 pb-6 mb-6">
                <div class="flex justify-center mb-4">
                    <span class="text-2xl font-black text-coffee-brown uppercase tracking-tighter">Kedai Kopi</span>
                </div>
                <h2 class="text-2xl font-bold text-coffee-dark uppercase tracking-wider">Kedai Kopi Premium</h2>
                <p class="text-xs text-gray-500 mt-1">Jl. Wangi Kopi No. 123, Bandung</p>
                <p class="text-xs text-gray-500">Telp: 0812-3456-7890</p>
            </div>

            <!-- Transaction Info -->
            <div class="flex justify-between text-xs text-gray-600 mb-6">
                <div>
                    <p><span class="font-bold">ID:</span> {{ $transaction->receipt->receipt_number }}</p>
                    <p><span class="font-bold">Kasir:</span> {{ $transaction->kasir->name }}</p>
                </div>
                <div class="text-right">
                    <p>{{ $transaction->created_at->format('d/m/Y') }}</p>
                    <p>{{ $transaction->created_at->format('H:i') }}</p>
                </div>
            </div>

            <!-- Items -->
            <div class="space-y-4 mb-8">
                @foreach($transaction->details as $detail)
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <p class="font-bold text-sm text-coffee-dark">{{ $detail->menu->name }}</p>
                        @if($detail->custom_notes)
                            <p class="text-[10px] text-coffee-brown italic">Note: {{ $detail->custom_notes }}</p>
                        @endif
                        <p class="text-xs text-gray-500">{{ $detail->qty }} x {{ number_format($detail->unit_price, 0, ',', '.') }}</p>
                    </div>
                    <p class="font-bold text-sm text-coffee-dark">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</p>
                </div>
                @endforeach
            </div>

            <!-- Summary -->
            <div class="border-t border-dashed border-gray-200 pt-4 space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Subtotal</span>
                    <span class="font-bold">Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
                </div>
                @if($transaction->discount > 0)
                <div class="flex justify-between text-sm text-coffee-brown">
                    <span>Diskon ({{ $transaction->promo->code ?? '-' }})</span>
                    <span class="font-bold">- Rp {{ number_format($transaction->discount, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="flex justify-between text-lg font-black text-coffee-dark pt-2">
                    <span>TOTAL</span>
                    <span>Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Payment -->
            <div class="mt-6 pt-4 border-t border-gray-100 space-y-1 text-xs text-gray-600">
                <div class="flex justify-between">
                    <span>Metode Pembayaran</span>
                    <span class="uppercase font-bold">{{ $transaction->payment_method }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Bayar</span>
                    <span>Rp {{ number_format($transaction->amount_paid, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm font-bold text-coffee-dark pt-1">
                    <span>Kembali</span>
                    <span>Rp {{ number_format($transaction->change, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-10">
                <p class="text-sm font-bold text-coffee-brown">Terima kasih, selamat menikmati!</p>
                <p class="text-[10px] text-gray-400 mt-2">www.kedaikopi.com</p>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-4 mt-8 no-print">
            <button onclick="window.print()" class="flex-1 bg-coffee-brown text-white py-3 rounded-xl font-bold hover:bg-coffee-dark transition-all flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2-2v4a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak Struk
            </button>
            <a href="{{ route('kasir.order.index') }}" class="flex-1 bg-gray-200 text-gray-700 py-3 rounded-xl font-bold text-center hover:bg-gray-300 transition-all">
                Order Lagi
            </a>
        </div>
    </div>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #printableReceipt, #printableReceipt * {
                visibility: visible;
            }
            #printableReceipt {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                box-shadow: none;
                border: none;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</x-kasir-layout>
