<x-owner-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-black text-coffee-dark tracking-tight uppercase">Manajemen Inventaris</h2>
                    <p class="text-coffee-brown font-medium">Pantau dan kelola stok bahan baku kopi Anda</p>
                </div>
                <div class="flex gap-2">
                    <button onclick="openModal('addSupplierModal')" class="bg-coffee-cream text-coffee-dark px-4 py-3 rounded-xl font-bold border border-coffee-brown/20 hover:bg-coffee-brown hover:text-white transition-all flex items-center gap-2">
                        <span class="text-xl font-black">+</span>
                        Supplier Baru
                    </button>
                    <button onclick="openModal('addModal')" class="bg-coffee-gold text-coffee-dark px-6 py-3 rounded-xl font-bold shadow-lg hover:bg-coffee-brown hover:text-white transition-all flex items-center gap-2">
                        <span class="text-xl font-black">+</span>
                        Tambah Bahan
                    </button>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-r-xl shadow-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-r-xl shadow-sm">
                    {{ session('error') }}
                </div>
            @endif
            @if($errors->any())
                <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-r-xl shadow-sm">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-2xl rounded-3xl border border-coffee-brown/10">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-coffee-dark text-coffee-cream">
                             <th class="px-6 py-4 font-bold uppercase text-sm">Bahan Baku</th>
                             <th class="px-6 py-4 font-bold uppercase text-sm">Tipe</th>
                             <th class="px-6 py-4 font-bold uppercase text-sm">Stok Saat Ini</th>
                            <th class="px-6 py-4 font-bold uppercase text-sm">Status</th>
                            <th class="px-6 py-4 font-bold uppercase text-sm">Supplier</th>
                            <th class="px-6 py-4 font-bold uppercase text-sm text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-coffee-brown/10">
                        @foreach($items as $item)
                            <tr class="hover:bg-coffee-cream/10 transition-colors">
                                 <td class="px-6 py-4">
                                     <span class="font-bold text-coffee-dark block">{{ $item->name }}</span>
                                     <span class="text-xs text-coffee-brown uppercase tracking-wider">{{ $item->unit }}</span>
                                 </td>
                                 <td class="px-6 py-4">
                                     <span class="text-xs font-black uppercase px-2 py-1 bg-coffee-cream text-coffee-brown rounded-md border border-coffee-brown/10">
                                         @if($item->type == 'kopi') Kopi
                                         @elseif($item->type == 'non_kopi') Non-Kopi
                                         @elseif($item->type == 'snack') Snack
                                         @elseif($item->type == 'makanan_berat') Makanan Berat
                                         @else {{ $item->type }} @endif
                                     </span>
                                 </td>
                                 <td class="px-6 py-4 font-black text-coffee-brown text-xl">
                                     {{ number_format($item->stock_qty, 0, ',', '.') }}
                                 </td>
                                <td class="px-6 py-4">
                                    @if($item->stock_qty <= 0)
                                        <span class="px-3 py-1 bg-red-500 text-white rounded-full text-[10px] font-black uppercase tracking-widest">Habis</span>
                                    @elseif($item->stock_qty <= $item->min_threshold)
                                        <span class="px-3 py-1 bg-yellow-500 text-coffee-dark rounded-full text-[10px] font-black uppercase tracking-widest">Rendah</span>
                                    @else
                                        <span class="px-3 py-1 bg-green-200 text-green-800 rounded-full text-[10px] font-black uppercase tracking-widest">Aman</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-coffee-brown font-medium italic">
                                    {{ $item->supplier->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <button onclick="openAdjustModal({{ $item->toJson() }})" class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors" title="Update Stok">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
                                        </button>
                                        <button onclick="editItem({{ $item->toJson() }})" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit Bahan">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <form action="{{ route('owner.inventory.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus bahan ini dari sistem?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div id="addModal" class="hidden fixed inset-0 bg-coffee-dark/80 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden">
            <div class="bg-coffee-gold px-8 py-6 flex justify-between items-center">
                <h3 class="text-xl font-black text-coffee-dark uppercase tracking-wider">Tambah Bahan Baku</h3>
                <button onclick="closeModal('addModal')" class="text-coffee-dark hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form action="{{ route('owner.inventory.store') }}" method="POST" class="p-8 space-y-4">
                @csrf
                 <div>
                    <label class="block text-sm font-bold text-coffee-dark mb-1">Nama Bahan</label>
                    <input type="text" name="name" required class="w-full px-4 py-3 bg-coffee-cream/20 border-coffee-brown/20 rounded-xl focus:ring-coffee-gold focus:border-coffee-gold" placeholder="Misal: Biji Kopi Arabika">
                </div>
                <div>
                    <label class="block text-sm font-bold text-coffee-dark mb-1">Tipe Bahan</label>
                    <select name="type" required class="w-full px-4 py-3 bg-coffee-cream/20 border-coffee-brown/20 rounded-xl focus:ring-coffee-gold focus:border-coffee-gold">
                        <option value="kopi">Kopi</option>
                        <option value="non_kopi">Minuman Non-Kopi</option>
                        <option value="snack">Camilan/Snack</option>
                        <option value="makanan_berat">Makanan Berat</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-coffee-dark mb-1">Satuan</label>
                        <input type="text" name="unit" required class="w-full px-4 py-3 bg-coffee-cream/20 border-coffee-brown/20 rounded-xl focus:ring-coffee-gold focus:border-coffee-gold" placeholder="gram, ml, pcs">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-coffee-dark mb-1">Stok Awal</label>
                        <input type="number" name="stock_qty" required class="w-full px-4 py-3 bg-coffee-cream/20 border-coffee-brown/20 rounded-xl focus:ring-coffee-gold focus:border-coffee-gold">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-coffee-dark mb-1">Batas Minimum (Alert)</label>
                    <input type="number" name="min_threshold" required class="w-full px-4 py-3 bg-coffee-cream/20 border-coffee-brown/20 rounded-xl focus:ring-coffee-gold focus:border-coffee-gold">
                </div>
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label class="block text-sm font-bold text-coffee-dark">Supplier</label>
                        <button type="button" onclick="openModal('addSupplierModal')" class="text-[10px] font-bold text-blue-600 hover:underline uppercase">+ Baru</button>
                    </div>
                    <select name="supplier_id" required class="w-full px-4 py-3 bg-coffee-cream/20 border-coffee-brown/20 rounded-xl focus:ring-coffee-gold focus:border-coffee-gold">
                        @foreach($suppliers as $sup)
                            <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="w-full bg-coffee-dark text-coffee-gold py-4 rounded-xl font-black uppercase tracking-widest hover:bg-coffee-brown hover:text-white transition-all shadow-xl mt-4"> Simpan Bahan </button>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="hidden fixed inset-0 bg-coffee-dark/80 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden">
            <div class="bg-coffee-gold px-8 py-6 flex justify-between items-center">
                <h3 class="text-xl font-black text-coffee-dark uppercase tracking-wider">Edit Bahan Baku</h3>
                <button onclick="closeModal('editModal')" class="text-coffee-dark hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form id="editForm" method="POST" class="p-8 space-y-4">
                @csrf @method('PUT')
                 <div>
                    <label class="block text-sm font-bold text-coffee-dark mb-1">Nama Bahan</label>
                    <input type="text" name="name" id="edit_name" required class="w-full px-4 py-3 bg-coffee-cream/20 border-coffee-brown/20 rounded-xl focus:ring-coffee-gold focus:border-coffee-gold">
                </div>
                <div>
                    <label class="block text-sm font-bold text-coffee-dark mb-1">Tipe Bahan</label>
                    <select name="type" id="edit_type" required class="w-full px-4 py-3 bg-coffee-cream/20 border-coffee-brown/20 rounded-xl focus:ring-coffee-gold focus:border-coffee-gold">
                        <option value="kopi">Kopi</option>
                        <option value="non_kopi">Minuman Non-Kopi</option>
                        <option value="snack">Camilan/Snack</option>
                        <option value="makanan_berat">Makanan Berat</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-coffee-dark mb-1">Satuan</label>
                        <input type="text" name="unit" id="edit_unit" required class="w-full px-4 py-3 bg-coffee-cream/20 border-coffee-brown/20 rounded-xl focus:ring-coffee-gold focus:border-coffee-gold">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-coffee-dark mb-1">Batas Minimum</label>
                        <input type="number" name="min_threshold" id="edit_min_threshold" required class="w-full px-4 py-3 bg-coffee-cream/20 border-coffee-brown/20 rounded-xl focus:ring-coffee-gold focus:border-coffee-gold">
                    </div>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label class="block text-sm font-bold text-coffee-dark">Supplier</label>
                        <button type="button" onclick="openModal('addSupplierModal')" class="text-[10px] font-bold text-blue-600 hover:underline uppercase">+ Baru</button>
                    </div>
                    <select name="supplier_id" id="edit_supplier_id" required class="w-full px-4 py-3 bg-coffee-cream/20 border-coffee-brown/20 rounded-xl focus:ring-coffee-gold focus:border-coffee-gold">
                        @foreach($suppliers as $sup)
                            <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                        @endforeach
                    </select>
                </div>
                <input type="hidden" name="stock_qty" id="edit_stock_qty">
                <button type="submit" class="w-full bg-coffee-dark text-coffee-gold py-4 rounded-xl font-black uppercase tracking-widest hover:bg-coffee-brown hover:text-white transition-all shadow-xl mt-4"> Simpan Perubahan </button>
            </form>
        </div>
    </div>

    <!-- Add Supplier Modal -->
    <div id="addSupplierModal" class="hidden fixed inset-0 bg-coffee-dark/90 backdrop-blur-md z-[60] flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden border-4 border-coffee-gold">
            <div class="bg-coffee-dark px-8 py-6 flex justify-between items-center">
                <h3 class="text-lg font-black text-coffee-gold uppercase tracking-wider">Supplier Baru</h3>
                <button onclick="closeModal('addSupplierModal')" class="text-coffee-gold hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form action="{{ route('owner.inventory.supplier.store') }}" method="POST" class="p-8 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-coffee-dark mb-1">Nama Perusahaan/Toko</label>
                    <input type="text" name="name" required class="w-full px-4 py-3 bg-coffee-cream/10 border-coffee-brown/20 rounded-xl focus:ring-coffee-gold">
                </div>
                <div>
                    <label class="block text-sm font-bold text-coffee-dark mb-1">Kontak (HP/Email)</label>
                    <input type="text" name="contact" class="w-full px-4 py-3 bg-coffee-cream/10 border-coffee-brown/20 rounded-xl focus:ring-coffee-gold">
                </div>
                <div>
                    <label class="block text-sm font-bold text-coffee-dark mb-1">Alamat</label>
                    <textarea name="address" class="w-full px-4 py-3 bg-coffee-cream/10 border-coffee-brown/20 rounded-xl focus:ring-coffee-gold" rows="2"></textarea>
                </div>
                <button type="submit" class="w-full bg-coffee-gold text-coffee-dark py-4 rounded-xl font-black uppercase tracking-widest hover:bg-coffee-dark hover:text-coffee-gold transition-all shadow-xl"> Simpan Supplier </button>
            </form>
        </div>
    </div>

    <!-- Adjust Modal -->
    <div id="adjustModal" class="hidden fixed inset-0 bg-coffee-dark/80 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden">
            <div class="bg-coffee-gold px-8 py-6 flex justify-between items-center">
                <h3 class="text-xl font-black text-coffee-dark uppercase tracking-wider italic" id="adjust_title">Update Stok</h3>
                <button onclick="closeModal('adjustModal')" class="text-coffee-dark hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form id="adjustForm" method="POST" class="p-8 space-y-4">
                @csrf
                <div class="bg-coffee-cream/30 p-4 rounded-xl text-center mb-4">
                    <p class="text-coffee-brown font-bold uppercase text-xs">Stok Saat Ini</p>
                    <p class="text-3xl font-black text-coffee-dark" id="current_stock_display">0</p>
                </div>
                <div>
                    <label class="block text-sm font-bold text-coffee-dark mb-1">Jenis Penyesuaian</label>
                    <select name="type" required class="w-full px-4 py-3 bg-coffee-cream/20 border-coffee-brown/20 rounded-xl focus:ring-coffee-gold focus:border-coffee-gold">
                        <option value="in">Penambahan (Barang Masuk)</option>
                        <option value="out">Pengurangan (Barang Keluar)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-coffee-dark mb-1">Jumlah</label>
                    <input type="number" name="qty" required class="w-full px-4 py-3 bg-coffee-cream/20 border-coffee-brown/20 rounded-xl focus:ring-coffee-gold focus:border-coffee-gold">
                </div>
                <div>
                    <label class="block text-sm font-bold text-coffee-dark mb-1">Catatan</label>
                    <textarea name="note" class="w-full px-4 py-3 bg-coffee-cream/20 border-coffee-brown/20 rounded-xl focus:ring-coffee-gold focus:border-coffee-gold" rows="2" placeholder="Contoh: Stok masuk dari supplier"></textarea>
                </div>
                <button type="submit" class="w-full bg-coffee-dark text-coffee-gold py-4 rounded-xl font-black uppercase tracking-widest hover:bg-coffee-brown hover:text-white transition-all shadow-xl mt-4"> Konfirmasi Update </button>
            </form>
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }
        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }
        function editItem(item) {
            document.getElementById('editForm').action = `/owner/inventory/${item.id}`;
            document.getElementById('edit_name').value = item.name;
            document.getElementById('edit_type').value = item.type;
            document.getElementById('edit_unit').value = item.unit;
            document.getElementById('edit_min_threshold').value = item.min_threshold;
            document.getElementById('edit_supplier_id').value = item.supplier_id;
            document.getElementById('edit_stock_qty').value = item.stock_qty;
            openModal('editModal');
        }
        function openAdjustModal(item) {
            document.getElementById('adjustForm').action = `/owner/inventory/${item.id}/adjust`;
            document.getElementById('adjust_title').innerText = `Update Stok: ${item.name}`;
            document.getElementById('current_stock_display').innerText = `${item.stock_qty} ${item.unit}`;
            openModal('adjustModal');
        }
    </script>
</x-owner-layout>
