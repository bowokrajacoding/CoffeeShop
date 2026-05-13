<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Kedai Kopi - Management System</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&family=Outfit:wght@800&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-coffee-dark text-coffee-cream selection:bg-coffee-gold selection:text-coffee-dark">
        <div class="relative min-h-screen flex flex-col items-center justify-center overflow-hidden">
            <!-- Background Image -->
            <div class="absolute inset-0 z-0">
                <div class="absolute inset-0 bg-coffee-dark opacity-90 z-10"></div>
                <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&q=80&w=2070" class="w-full h-full object-cover" alt="Coffee Background">
            </div>

            <div class="relative z-10 w-full max-w-md px-6 py-12 bg-coffee-brown/20 backdrop-blur-md rounded-3xl border border-coffee-gold/20 shadow-2xl mx-4">
                <div class="text-center mb-8">
                    <h2 class="text-5xl font-black text-coffee-gold tracking-tighter font-outfit uppercase">Kedai Kopi</h2>
                    <div class="h-1 w-20 bg-coffee-gold mx-auto mt-2 rounded-full"></div>
                </div>
                <h1 class="text-2xl font-bold mb-2 text-center tracking-tight text-white">SISTEM POS & INVENTARIS</h1>
                <p class="text-coffee-gold/60 font-medium mb-8 text-center text-sm">Masuk untuk mengelola bisnis Anda</p>
                
                @auth
                    <div class="text-center">
                        <p class="mb-6 text-lg">Selamat datang kembali, <span class="font-bold text-white">{{ Auth::user()->name }}</span></p>
                        <a href="{{ url('/dashboard') }}" class="block w-full bg-coffee-gold text-coffee-dark py-4 rounded-xl font-black text-lg hover:bg-white transition-all shadow-xl uppercase tracking-widest">
                            KE DASHBOARD
                        </a>
                    </div>
                @else
                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label for="email" class="block text-sm font-bold mb-1 text-coffee-gold uppercase tracking-wider">Email</label>
                            <input id="email" type="email" name="email" required autofocus class="block w-full px-4 py-3 bg-white border-2 border-coffee-gold/30 rounded-xl text-coffee-dark font-semibold focus:ring-coffee-gold focus:border-coffee-gold transition-all" placeholder="nama@email.com">
                            @error('email')
                                <p class="text-red-400 text-xs mt-1 font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-bold mb-1 text-coffee-gold uppercase tracking-wider">Kata Sandi</label>
                            <input id="password" type="password" name="password" required class="block w-full px-4 py-3 bg-white border-2 border-coffee-gold/30 rounded-xl text-coffee-dark font-semibold focus:ring-coffee-gold focus:border-coffee-gold transition-all" placeholder="••••••••">
                            @error('password')
                                <p class="text-red-400 text-xs mt-1 font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between py-2">
                            <label for="remember_me" class="flex items-center cursor-pointer">
                                <input id="remember_me" type="checkbox" name="remember" class="rounded bg-coffee-dark border-coffee-gold/30 text-coffee-gold focus:ring-coffee-gold">
                                <span class="ml-2 text-xs text-white/60 font-medium">Ingat saya</span>
                            </label>
                        </div>

                        <button type="submit" class="block w-full bg-coffee-gold text-coffee-dark py-4 rounded-xl font-black text-lg hover:bg-white transition-all shadow-xl uppercase tracking-[0.2em]">
                            MASUK SISTEM
                        </button>
                    </form>
                @endauth
            </div>

            <div class="absolute bottom-8 z-10 text-[10px] text-coffee-gold/40 font-black uppercase tracking-[0.3em]">
                &copy; {{ date('Y') }} Kedai Kopi Premium Management System.
            </div>
        </div>
    </body>
</html>
