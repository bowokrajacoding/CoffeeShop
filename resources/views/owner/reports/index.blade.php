<x-owner-layout>
    <x-slot name="header">
        Laporan Penjualan
    </x-slot>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-coffee-light mb-8">
        <form action="{{ route('owner.reports.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="rounded-lg border-gray-300 focus:ring-coffee-brown">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="rounded-lg border-gray-300 focus:ring-coffee-brown">
            </div>
            <button type="submit" class="bg-coffee-brown text-white px-6 py-2 rounded-lg font-bold hover:bg-coffee-dark transition-all">Filter</button>
            <div class="flex gap-2 ms-auto">
                <a href="{{ route('owner.reports.pdf', request()->all()) }}" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-bold flex items-center gap-2 hover:bg-red-700 transition-all">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L14 2.586A2 2 0 0012.586 2H9z"/><path d="M3 8a2 2 0 012-2v10h8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/></svg>
                    PDF
                </a>
                <a href="{{ route('owner.reports.excel', request()->all()) }}" class="bg-emerald-600 text-white px-5 py-2.5 rounded-xl text-sm font-black flex items-center gap-2 hover:bg-emerald-700 transition-all shadow-lg border-2 border-emerald-500/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    EXCEL
                </a>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-coffee-cream p-6 rounded-xl border border-coffee-light">
            <p class="text-sm text-coffee-brown font-bold uppercase tracking-wider">Total Pendapatan</p>
            <h3 class="text-3xl font-black text-coffee-dark mt-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
        </div>
        <div class="bg-coffee-cream p-6 rounded-xl border border-coffee-light">
            <p class="text-sm text-coffee-brown font-bold uppercase tracking-wider">Total Pesanan</p>
            <h3 class="text-3xl font-black text-coffee-dark mt-1">{{ $totalOrders }}</h3>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-coffee-light overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr class="text-xs font-bold text-gray-500 uppercase tracking-wider">
                    <th class="px-6 py-4">ID Transaksi</th>
                    <th class="px-6 py-4">Tanggal</th>
                    <th class="px-6 py-4">Kasir</th>
                    <th class="px-6 py-4">Metode</th>
                    <th class="px-6 py-4 text-right">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm">
                @foreach($transactions as $trx)
                <tr class="hover:bg-gray-50 transition-all">
                    <td class="px-6 py-4 font-bold text-coffee-brown">#{{ $trx->id }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-6 py-4">{{ $trx->kasir->name }}</td>
                    <td class="px-6 py-4 uppercase font-medium text-xs">{{ $trx->payment_method }}</td>
                    <td class="px-6 py-4 text-right font-black text-coffee-dark">Rp {{ number_format($trx->grand_total, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-owner-layout>
