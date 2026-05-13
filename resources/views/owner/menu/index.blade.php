<x-owner-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-black text-coffee-dark tracking-tight uppercase">Manajemen Menu & Resep</h2>
                    <p class="text-coffee-brown font-medium">Atur daftar menu dan resep bahan baku Anda</p>
                </div>
                <button onclick="openModal('addMenuModal')" class="bg-coffee-gold text-coffee-dark px-6 py-3 rounded-xl font-bold shadow-lg hover:bg-coffee-brown hover:text-white transition-all flex items-center gap-2">
                    <span class="text-xl font-black">+</span>
                    Tambah Menu
                </button>
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

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($menus as $menu)
                    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-coffee-brown/10 group">
                        <div class="relative h-48 overflow-hidden">
                            <img src="{{ Str::startsWith($menu->image, ['http://', 'https://']) ? $menu->image : asset($menu->image) }}" alt="{{ $menu->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            <div class="absolute top-4 right-4">
                                <span class="px-3 py-1 bg-coffee-dark/80 backdrop-blur-md text-coffee-gold rounded-full text-[10px] font-black uppercase tracking-widest">
                                    {{ $menu->category->name }}
                                </span>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-xl font-black text-coffee-dark leading-tight">{{ $menu->name }}</h3>
                                <span class="text-coffee-brown font-black text-lg">Rp{{ number_format($menu->price, 0, ',', '.') }}</span>
                            </div>
                            <p class="text-coffee-brown/70 text-sm mb-4 line-clamp-2 italic">{{ $menu->description ?? 'Tidak ada deskripsi.' }}</p>
                            
                            <div class="mb-4">
                                <p class="text-[10px] font-black text-coffee-dark uppercase tracking-widest mb-2">Resep / Bahan:</p>
                                <div class="flex flex-wrap gap-1">
                                    @foreach($menu->ingredients as $ing)
                                        <span class="px-2 py-1 bg-coffee-cream/30 text-coffee-brown text-[10px] rounded-lg border border-coffee-brown/10">
                                            {{ $ing->inventory->name }} ({{ $ing->qty_per_serving }} {{ $ing->inventory->unit }})
                                        </span>
                                    @endforeach
                                </div>
                            </div>

                            <div class="flex justify-between items-center pt-4 border-t border-coffee-brown/10">
                                <span class="text-xs {{ $menu->is_active ? 'text-green-600' : 'text-red-600' }} font-bold uppercase tracking-tighter">
                                    {{ $menu->is_active ? '● Aktif' : '○ Non-Aktif' }}
                                </span>
                                <div class="flex gap-2">
                                    <button onclick="editMenu({{ $menu->toJson() }})" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <form action="{{ route('owner.menu.destroy', $menu) }}" method="POST" onsubmit="return confirm('Hapus menu ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Add Menu Modal -->
    <div id="addMenuModal" class="hidden fixed inset-0 bg-coffee-dark/80 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden max-h-[90vh] flex flex-col">
            <div class="bg-coffee-gold px-8 py-6 flex justify-between items-center shrink-0">
                <h3 class="text-xl font-black text-coffee-dark uppercase tracking-wider">Tambah Menu Baru</h3>
                <button onclick="closeModal('addMenuModal')" class="text-coffee-dark hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form action="{{ route('owner.menu.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6 overflow-y-auto">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-coffee-dark mb-1">Nama Menu</label>
                            <input type="text" name="name" required class="w-full px-4 py-3 bg-coffee-cream/20 border-coffee-brown/20 rounded-xl focus:ring-coffee-gold focus:border-coffee-gold">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-coffee-dark mb-1">Kategori</label>
                            <select name="category_id" id="add_category_id" required onchange="filterIngredientsBySelection(this, 'add')" class="w-full px-4 py-3 bg-coffee-cream/20 border-coffee-brown/20 rounded-xl focus:ring-coffee-gold focus:border-coffee-gold">
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" data-name="{{ strtolower($cat->name) }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-coffee-dark mb-1">Harga (Rp)</label>
                            <input type="number" name="price" required class="w-full px-4 py-3 bg-coffee-cream/20 border-coffee-brown/20 rounded-xl focus:ring-coffee-gold focus:border-coffee-gold">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-coffee-dark mb-1">Gambar Menu</label>
                            <input type="file" name="image" required class="w-full px-4 py-3 bg-coffee-cream/20 border-coffee-brown/20 rounded-xl focus:ring-coffee-gold focus:border-coffee-gold text-sm">
                        </div>
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" checked class="rounded text-coffee-gold focus:ring-coffee-gold">
                                <span class="ml-2 text-sm font-bold text-coffee-dark">Aktifkan Menu</span>
                            </label>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <label class="block text-sm font-bold text-coffee-dark">Resep (Bahan Baku)</label>
                                <button type="button" onclick="toggleAllIngredients('add')" class="text-[10px] font-bold text-coffee-brown hover:text-coffee-gold uppercase">Lihat Semua Bahan</button>
                            </div>
                            <div class="bg-coffee-cream/10 border border-coffee-brown/10 rounded-2xl p-4 max-h-64 overflow-y-auto space-y-4">
                                <div id="no_ingredients_msg_add" class="hidden text-center py-4">
                                    <p class="text-xs text-coffee-brown italic">Tidak ada bahan baku untuk kategori ini.</p>
                                    <button type="button" onclick="toggleAllIngredients('add')" class="text-[10px] font-bold text-blue-600 mt-1 uppercase underline">Tampilkan semua bahan</button>
                                </div>
                                @foreach($inventories->groupBy('type') as $type => $items)
                                    <div class="space-y-2 ingredient-group-add" data-type="{{ $type }}">
                                        <p class="text-[10px] font-black text-coffee-gold uppercase tracking-widest border-b border-coffee-brown/10 pb-1 flex justify-between">
                                            <span>
                                                @if($type == 'kopi') KOPI
                                                @elseif($type == 'non_kopi') NON-KOPI
                                                @elseif($type == 'snack') SNACK / CAMILAN
                                                @elseif($type == 'makanan_berat') MAKANAN BERAT
                                                @else {{ strtoupper($type) }} @endif
                                            </span>
                                            <button type="button" onclick="this.parentElement.parentElement.classList.toggle('hidden')" class="text-coffee-brown hover:text-coffee-gold">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
                                            </button>
                                        </p>
                                        <div class="grid grid-cols-1 gap-2">
                                            @foreach($items as $inv)
                                                <div class="flex items-center justify-between gap-4">
                                                    <span class="text-xs font-semibold text-coffee-brown">{{ $inv->name }} ({{ $inv->unit }})</span>
                                                    <input type="number" step="0.01" name="ingredients[{{ $inv->id }}]" placeholder="0" class="w-20 px-2 py-1 bg-white border border-coffee-brown/20 rounded-lg text-sm text-right focus:ring-coffee-gold">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-coffee-dark mb-1">Deskripsi Singkat</label>
                            <textarea name="description" class="w-full px-4 py-3 bg-coffee-cream/20 border-coffee-brown/20 rounded-xl focus:ring-coffee-gold focus:border-coffee-gold" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <button type="submit" class="w-full bg-coffee-dark text-coffee-gold py-4 rounded-xl font-black uppercase tracking-widest hover:bg-coffee-brown hover:text-white transition-all shadow-xl"> Simpan Menu </button>
            </form>
        </div>
    </div>

    <!-- Edit Menu Modal -->
    <div id="editMenuModal" class="hidden fixed inset-0 bg-coffee-dark/80 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden max-h-[90vh] flex flex-col">
            <div class="bg-coffee-gold px-8 py-6 flex justify-between items-center shrink-0">
                <h3 class="text-xl font-black text-coffee-dark uppercase tracking-wider">Edit Menu</h3>
                <button onclick="closeModal('editMenuModal')" class="text-coffee-dark hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form id="editMenuForm" method="POST" enctype="multipart/form-data" class="p-8 space-y-6 overflow-y-auto">
                @csrf @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-coffee-dark mb-1">Nama Menu</label>
                            <input type="text" name="name" id="edit_name" required class="w-full px-4 py-3 bg-coffee-cream/20 border-coffee-brown/20 rounded-xl focus:ring-coffee-gold focus:border-coffee-gold">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-coffee-dark mb-1">Kategori</label>
                            <select name="category_id" id="edit_category_id" required onchange="filterIngredientsBySelection(this, 'edit')" class="w-full px-4 py-3 bg-coffee-cream/20 border-coffee-brown/20 rounded-xl focus:ring-coffee-gold focus:border-coffee-gold">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" data-name="{{ strtolower($cat->name) }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-coffee-dark mb-1">Harga (Rp)</label>
                            <input type="number" name="price" id="edit_price" required class="w-full px-4 py-3 bg-coffee-cream/20 border-coffee-brown/20 rounded-xl focus:ring-coffee-gold focus:border-coffee-gold">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-coffee-dark mb-1">Ganti Gambar Menu (Opsional)</label>
                            <input type="file" name="image" class="w-full px-4 py-3 bg-coffee-cream/20 border-coffee-brown/20 rounded-xl focus:ring-coffee-gold focus:border-coffee-gold text-sm">
                        </div>
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" id="edit_is_active" class="rounded text-coffee-gold focus:ring-coffee-gold">
                                <span class="ml-2 text-sm font-bold text-coffee-dark">Aktifkan Menu</span>
                            </label>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <label class="block text-sm font-bold text-coffee-dark">Resep (Bahan Baku)</label>
                                <button type="button" onclick="toggleAllIngredients('edit')" class="text-[10px] font-bold text-coffee-brown hover:text-coffee-gold uppercase">Lihat Semua Bahan</button>
                            </div>
                            <div class="bg-coffee-cream/10 border border-coffee-brown/10 rounded-2xl p-4 max-h-64 overflow-y-auto space-y-4">
                                <div id="no_ingredients_msg_edit" class="hidden text-center py-4">
                                    <p class="text-xs text-coffee-brown italic">Tidak ada bahan baku untuk kategori ini.</p>
                                    <button type="button" onclick="toggleAllIngredients('edit')" class="text-[10px] font-bold text-blue-600 mt-1 uppercase underline">Tampilkan semua bahan</button>
                                </div>
                                @foreach($inventories->groupBy('type') as $type => $items)
                                    <div class="space-y-2 ingredient-group-edit" data-type="{{ $type }}">
                                        <p class="text-[10px] font-black text-coffee-gold uppercase tracking-widest border-b border-coffee-brown/10 pb-1 flex justify-between">
                                            <span>
                                                @if($type == 'kopi') KOPI
                                                @elseif($type == 'non_kopi') NON-KOPI
                                                @elseif($type == 'snack') SNACK / CAMILAN
                                                @elseif($type == 'makanan_berat') MAKANAN BERAT
                                                @else {{ strtoupper($type) }} @endif
                                            </span>
                                            <button type="button" onclick="this.parentElement.parentElement.classList.toggle('hidden')" class="text-coffee-brown hover:text-coffee-gold">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
                                            </button>
                                        </p>
                                        <div class="grid grid-cols-1 gap-2">
                                            @foreach($items as $inv)
                                                <div class="flex items-center justify-between gap-4">
                                                    <span class="text-xs font-semibold text-coffee-brown">{{ $inv->name }} ({{ $inv->unit }})</span>
                                                    <input type="number" step="0.01" name="ingredients[{{ $inv->id }}]" id="edit_ing_{{ $inv->id }}" placeholder="0" class="w-20 px-2 py-1 bg-white border border-coffee-brown/20 rounded-lg text-sm text-right focus:ring-coffee-gold">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-coffee-dark mb-1">Deskripsi Singkat</label>
                            <textarea name="description" id="edit_description" class="w-full px-4 py-3 bg-coffee-cream/20 border-coffee-brown/20 rounded-xl focus:ring-coffee-gold focus:border-coffee-gold" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <button type="submit" class="w-full bg-coffee-dark text-coffee-gold py-4 rounded-xl font-black uppercase tracking-widest hover:bg-coffee-brown hover:text-white transition-all shadow-xl"> Update Menu </button>
            </form>
        </div>
    </div>

    <script>
        function filterIngredientsBySelection(select, mode) {
            const categoryName = select.options[select.selectedIndex].getAttribute('data-name') || '';
            const groups = document.querySelectorAll(`.ingredient-group-${mode}`);
            
            groups.forEach(group => {
                const type = group.getAttribute('data-type');
                // Simple keyword matching
                let shouldShow = false;
                if (categoryName.includes('kopi') && type === 'kopi') shouldShow = true;
                if ((categoryName.includes('minum') || categoryName.includes('non')) && type === 'non_kopi') shouldShow = true;
                if (categoryName.includes('snack') && type === 'snack') shouldShow = true;
                if ((categoryName.includes('makan') || categoryName.includes('food')) && type === 'makanan_berat') shouldShow = true;
                
                // If no match found or "Pilih Kategori", show all
                if (!categoryName) shouldShow = true;

                if (shouldShow) {
                    group.classList.remove('hidden');
                } else {
                    group.classList.add('hidden');
                }
            });

            // Show message if no groups are visible
            const visibleGroups = Array.from(groups).filter(g => !g.classList.contains('hidden'));
            const msg = document.getElementById(`no_ingredients_msg_${mode}`);
            if (visibleGroups.length === 0 && categoryName) {
                msg.classList.remove('hidden');
            } else {
                msg.classList.add('hidden');
            }
        }

        function toggleAllIngredients(mode) {
            const groups = document.querySelectorAll(`.ingredient-group-${mode}`);
            groups.forEach(g => g.classList.remove('hidden'));
            document.getElementById(`no_ingredients_msg_${mode}`).classList.add('hidden');
        }
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }
        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }
        function editMenu(menu) {
            document.getElementById('editMenuForm').action = `/owner/menu/${menu.id}`;
            document.getElementById('edit_name').value = menu.name;
            document.getElementById('edit_category_id').value = menu.category_id;
            document.getElementById('edit_price').value = menu.price;
            document.getElementById('edit_description').value = menu.description || '';
            document.getElementById('edit_is_active').checked = !!menu.is_active;

            // Trigger ingredient filter
            setTimeout(() => {
                const select = document.getElementById('edit_category_id');
                filterIngredientsBySelection(select, 'edit');
            }, 100);

            // Reset all ingredients first
            @foreach($inventories as $inv)
                document.getElementById('edit_ing_{{ $inv->id }}').value = '';
            @endforeach

            // Set ingredients from menu resep
            if (menu.ingredients) {
                menu.ingredients.forEach(ing => {
                    let input = document.getElementById(`edit_ing_${ing.inventory_id}`);
                    if (input) input.value = ing.qty_per_serving;
                });
            }

            openModal('editMenuModal');
        }
    </script>
</x-owner-layout>
