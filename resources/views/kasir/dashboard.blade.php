<x-kasir-layout>
    <x-slot name="header">
        Dashboard Kasir
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-coffee-brown">
            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Transaksi Hari Ini</p>
            <h3 class="text-3xl font-black text-coffee-dark mt-1">{{ $totalTransactions }}</h3>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-coffee-brown">
            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Pendapatan</p>
            <h3 class="text-3xl font-black text-coffee-dark mt-1">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h3>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Shortcuts -->
        <div class="space-y-4">
            <a href="{{ route('kasir.order.index') }}" class="flex items-center justify-between gap-4 bg-coffee-brown text-white p-6 rounded-2xl hover:bg-coffee-dark transition-all shadow-lg group">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-white/10 rounded-xl group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    </div>
                    <span class="text-xl font-black uppercase tracking-tight">Pesanan Baru</span>
                </div>
                <svg class="w-6 h-6 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
            <a href="{{ route('kasir.history') }}" class="flex items-center justify-between gap-4 bg-white text-coffee-brown border-2 border-coffee-brown p-6 rounded-2xl hover:bg-coffee-cream transition-all shadow-md group">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-coffee-brown/5 rounded-xl group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <span class="text-xl font-black uppercase tracking-tight">Riwayat Semua</span>
                </div>
                <svg class="w-6 h-6 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <!-- Recent Transactions -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-coffee-brown/10 overflow-hidden">
            <div class="bg-coffee-cream/50 px-6 py-4 border-b border-coffee-brown/10 flex justify-between items-center">
                <h3 class="font-black text-coffee-dark uppercase tracking-widest text-sm">Transaksi Terakhir Anda</h3>
                <a href="{{ route('kasir.history') }}" class="text-[10px] font-bold text-coffee-brown hover:underline uppercase">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] font-black text-coffee-brown/50 uppercase tracking-widest border-b border-gray-50">
                            <th class="px-6 py-3">ID</th>
                            <th class="px-6 py-3">Waktu</th>
                            <th class="px-6 py-3">Total</th>
                            <th class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($recentTransactions as $trx)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-bold text-coffee-dark">#{{ $trx->id }}</td>
                            <td class="px-6 py-4 text-xs text-gray-500">{{ $trx->created_at->format('H:i') }} <span class="opacity-50">({{ $trx->created_at->diffForHumans() }})</span></td>
                            <td class="px-6 py-4 font-black text-coffee-brown">Rp {{ number_format($trx->grand_total, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('kasir.receipt', $trx) }}" class="p-2 text-coffee-brown hover:bg-coffee-cream rounded-lg inline-block transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-400 italic text-sm">Belum ada transaksi hari ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-kasir-layout>
