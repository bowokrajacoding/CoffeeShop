<x-kasir-layout>
    <x-slot name="header">
        Riwayat Transaksi
    </x-slot>

    <div class="bg-white rounded-xl shadow-sm border border-coffee-brown overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-coffee-cream border-b border-coffee-brown">
                <tr class="text-coffee-dark text-xs font-bold uppercase tracking-wider">
                    <th class="px-6 py-4">ID Transaksi</th>
                    <th class="px-6 py-4">Waktu</th>
                    <th class="px-6 py-4">Total</th>
                    <th class="px-6 py-4">Metode</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @foreach($transactions as $trx)
                <tr class="hover:bg-gray-50 transition-all">
                    <td class="px-6 py-4 font-bold text-coffee-brown">#{{ $trx->id }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-6 py-4 font-black text-coffee-dark">Rp {{ number_format($trx->grand_total, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 uppercase text-xs font-medium">{{ $trx->payment_method }}</td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('kasir.receipt', $trx) }}" class="text-coffee-brown font-bold hover:underline">Lihat Struk</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $transactions->links() }}
    </div>
</x-kasir-layout>
