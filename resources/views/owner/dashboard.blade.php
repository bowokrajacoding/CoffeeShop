<x-owner-layout>
    <x-slot name="header">
        Dashboard Analitik
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- KPI Cards -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-coffee-light">
            <p class="text-sm font-medium text-gray-500">Pendapatan Hari Ini</p>
            <h3 class="text-2xl font-bold text-coffee-dark">Rp {{ number_format($incomeToday, 0, ',', '.') }}</h3>
            <p class="text-xs text-green-600 mt-2">↑ {{ $totalTransactions }} Transaksi</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-coffee-light">
            <p class="text-sm font-medium text-gray-500">Pendapatan Minggu Ini</p>
            <h3 class="text-2xl font-bold text-coffee-dark">Rp {{ number_format($incomeWeek, 0, ',', '.') }}</h3>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-coffee-light">
            <p class="text-sm font-medium text-gray-500">Menu Terlaris</p>
            <h3 class="text-xl font-bold text-coffee-brown">{{ $bestSeller->menu->name ?? '-' }}</h3>
            <p class="text-xs text-gray-500 mt-1">{{ $bestSeller->total_qty ?? 0 }} terjual</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-coffee-light">
            <p class="text-sm font-medium text-gray-500">Stok Rendah</p>
            <h3 class="text-2xl font-bold {{ $lowStockCount > 0 ? 'text-red-600' : 'text-coffee-dark' }}">{{ $lowStockCount }} Item</h3>
            <p class="text-xs text-gray-500 mt-2">Perlu re-stock segera</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Line Chart -->
        <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-sm border border-coffee-light">
            <h3 class="text-lg font-semibold text-coffee-dark mb-4">Tren Pendapatan (30 Hari Terakhir)</h3>
            <canvas id="incomeChart" height="100"></canvas>
        </div>
        <!-- Doughnut Chart -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-coffee-light">
            <h3 class="text-lg font-semibold text-coffee-dark mb-4">Penjualan per Kategori</h3>
            <canvas id="categoryChart"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Transactions -->
        <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-sm border border-coffee-light">
            <h3 class="text-lg font-semibold text-coffee-dark mb-4">Transaksi Terbaru</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-gray-400 text-sm border-b border-gray-100">
                            <th class="pb-3 font-medium">ID</th>
                            <th class="pb-3 font-medium">Waktu</th>
                            <th class="pb-3 font-medium">Kasir</th>
                            <th class="pb-3 font-medium">Total</th>
                            <th class="pb-3 font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach($recentTransactions as $trx)
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="py-4 font-medium text-coffee-brown">#{{ $trx->id }}</td>
                            <td class="py-4">{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                            <td class="py-4">{{ $trx->kasir->name }}</td>
                            <td class="py-4 font-bold text-coffee-dark">Rp {{ number_format($trx->grand_total, 0, ',', '.') }}</td>
                            <td class="py-4">
                                <span class="px-2 py-1 rounded-full text-xs font-medium 
                                    @if($trx->status == 'completed') bg-green-100 text-green-700 @else bg-yellow-100 text-yellow-700 @endif">
                                    {{ ucfirst($trx->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-coffee-light">
            <h3 class="text-lg font-semibold text-red-600 mb-4">Peringatan Stok</h3>
            <div class="space-y-4">
                @forelse($lowStockItems as $item)
                <div class="flex justify-between items-center p-3 bg-red-50 rounded-lg border border-red-100">
                    <div>
                        <p class="font-medium text-coffee-dark">{{ $item->name }}</p>
                        <p class="text-xs text-gray-500">Sisa: {{ $item->stock_qty }} {{ $item->unit }}</p>
                    </div>
                    <span class="text-xs font-bold text-red-600">RE-STOCK</span>
                </div>
                @empty
                <p class="text-sm text-gray-500 italic">Semua stok aman.</p>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        // Income Chart
        const incomeCtx = document.getElementById('incomeChart').getContext('2d');
        new Chart(incomeCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($incomeData->map(fn($d) => date('d M', strtotime($d->date)))) !!},
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: {!! json_encode($incomeData->pluck('total')) !!},
                    borderColor: '#6F4E37',
                    backgroundColor: 'rgba(111, 78, 55, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // Category Chart
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($categoryData->pluck('name')) !!},
                datasets: [{
                    data: {!! json_encode($categoryData->pluck('total_qty')) !!},
                    backgroundColor: ['#3B1F0C', '#6F4E37', '#D4A96A', '#F5F5DC', '#EED9C4']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    </script>
</x-owner-layout>
