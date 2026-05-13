<x-owner-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-black text-coffee-dark tracking-tight">KELOLA KASIR</h2>
                    <p class="text-coffee-brown font-medium">Manajemen akun dan hak akses kasir</p>
                </div>
                <button onclick="openModal('addModal')" class="bg-coffee-gold text-coffee-dark px-6 py-3 rounded-xl font-bold shadow-lg hover:bg-coffee-brown hover:text-white transition-all flex items-center gap-2">
                    <span class="text-xl font-black">+</span>
                    Tambah Kasir
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
                            <th class="px-6 py-4 font-bold uppercase text-sm">Nama</th>
                            <th class="px-6 py-4 font-bold uppercase text-sm">Email</th>
                            <th class="px-6 py-4 font-bold uppercase text-sm">Dibuat Pada</th>
                            <th class="px-6 py-4 font-bold uppercase text-sm text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-coffee-brown/10">
                        @foreach($users as $user)
                            <tr class="hover:bg-coffee-cream/20 transition-colors">
                                <td class="px-6 py-4 font-semibold text-coffee-dark">{{ $user->name }}</td>
                                <td class="px-6 py-4 text-coffee-brown">{{ $user->email }}</td>
                                <td class="px-6 py-4 text-coffee-brown/70 text-sm">{{ $user->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <button onclick="editUser({{ $user->toJson() }})" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <form action="{{ route('owner.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus akun ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
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
                <h3 class="text-xl font-black text-coffee-dark uppercase tracking-wider">Tambah Kasir</h3>
                <button onclick="closeModal('addModal')" class="text-coffee-dark hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form action="{{ route('owner.users.store') }}" method="POST" class="p-8 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-coffee-dark mb-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-3 bg-coffee-cream/20 border-coffee-brown/20 rounded-xl focus:ring-coffee-gold focus:border-coffee-gold">
                </div>
                <div>
                    <label class="block text-sm font-bold text-coffee-dark mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-3 bg-coffee-cream/20 border-coffee-brown/20 rounded-xl focus:ring-coffee-gold focus:border-coffee-gold">
                </div>
                <div>
                    <label class="block text-sm font-bold text-coffee-dark mb-1">Kata Sandi</label>
                    <input type="password" name="password" required class="w-full px-4 py-3 bg-coffee-cream/20 border-coffee-brown/20 rounded-xl focus:ring-coffee-gold focus:border-coffee-gold">
                </div>
                <div>
                    <label class="block text-sm font-bold text-coffee-dark mb-1">Konfirmasi Kata Sandi</label>
                    <input type="password" name="password_confirmation" required class="w-full px-4 py-3 bg-coffee-cream/20 border-coffee-brown/20 rounded-xl focus:ring-coffee-gold focus:border-coffee-gold">
                </div>
                <button type="submit" class="w-full bg-coffee-dark text-coffee-gold py-4 rounded-xl font-black uppercase tracking-widest hover:bg-coffee-brown hover:text-white transition-all shadow-xl mt-4"> Simpan Akun </button>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="hidden fixed inset-0 bg-coffee-dark/80 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden">
            <div class="bg-coffee-gold px-8 py-6 flex justify-between items-center">
                <h3 class="text-xl font-black text-coffee-dark uppercase tracking-wider">Edit Kasir</h3>
                <button onclick="closeModal('editModal')" class="text-coffee-dark hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form id="editForm" method="POST" class="p-8 space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="block text-sm font-bold text-coffee-dark mb-1">Nama Lengkap</label>
                    <input type="text" name="name" id="edit_name" required class="w-full px-4 py-3 bg-coffee-cream/20 border-coffee-brown/20 rounded-xl focus:ring-coffee-gold focus:border-coffee-gold">
                </div>
                <div>
                    <label class="block text-sm font-bold text-coffee-dark mb-1">Email</label>
                    <input type="email" name="email" id="edit_email" required class="w-full px-4 py-3 bg-coffee-cream/20 border-coffee-brown/20 rounded-xl focus:ring-coffee-gold focus:border-coffee-gold">
                </div>
                <div>
                    <label class="block text-sm font-bold text-coffee-dark mb-1">Ganti Kata Sandi (Kosongkan jika tidak ganti)</label>
                    <input type="password" name="password" class="w-full px-4 py-3 bg-coffee-cream/20 border-coffee-brown/20 rounded-xl focus:ring-coffee-gold focus:border-coffee-gold">
                </div>
                <div>
                    <label class="block text-sm font-bold text-coffee-dark mb-1">Konfirmasi Kata Sandi Baru</label>
                    <input type="password" name="password_confirmation" class="w-full px-4 py-3 bg-coffee-cream/20 border-coffee-brown/20 rounded-xl focus:ring-coffee-gold focus:border-coffee-gold">
                </div>
                <button type="submit" class="w-full bg-coffee-dark text-coffee-gold py-4 rounded-xl font-black uppercase tracking-widest hover:bg-coffee-brown hover:text-white transition-all shadow-xl mt-4"> Update Akun </button>
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
        function editUser(user) {
            document.getElementById('editForm').action = `/owner/users/${user.id}`;
            document.getElementById('edit_name').value = user.name;
            document.getElementById('edit_email').value = user.email;
            openModal('editModal');
        }
        
        // Auto-open modal if validation fails
        @if($errors->any())
            // This is a simple way, but better to know which modal failed. 
            // For now, we'll just show the main content again.
        @endif
    </script>
</x-owner-layout>
