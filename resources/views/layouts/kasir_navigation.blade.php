<nav x-data="{ open: false }" class="bg-coffee-brown border-b border-coffee-dark">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('kasir.dashboard') }}" class="text-coffee-cream font-bold text-xl flex items-center gap-2">
                        <span class="text-coffee-cream font-black uppercase tracking-tighter">Kedai Kopi</span>
                        <span>KASIR</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-4 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('kasir.dashboard')" :active="request()->routeIs('kasir.dashboard')" class="text-coffee-cream hover:text-coffee-gold active:text-coffee-gold">
                        Dashboard
                    </x-nav-link>
                    <x-nav-link :href="route('kasir.order.index')" :active="request()->routeIs('kasir.order.*')" class="text-coffee-cream hover:text-coffee-gold">
                        Order Baru
                    </x-nav-link>
                    <x-nav-link :href="route('kasir.history')" :active="request()->routeIs('kasir.history')" class="text-coffee-cream hover:text-coffee-gold">
                        Riwayat Transaksi
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-coffee-cream bg-coffee-dark hover:bg-coffee-brown focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }} (Kasir)</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                Keluar
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-coffee-cream hover:text-coffee-gold hover:bg-coffee-dark focus:outline-none focus:bg-coffee-dark focus:text-coffee-cream transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-coffee-dark">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('kasir.dashboard')" :active="request()->routeIs('kasir.dashboard')" class="text-coffee-cream">
                Dashboard
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('kasir.order.index')" :active="request()->routeIs('kasir.order.*')" class="text-coffee-cream">
                Order Baru
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('kasir.history')" :active="request()->routeIs('kasir.history')" class="text-coffee-cream">
                Riwayat Transaksi
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-coffee-brown">
            <div class="px-4">
                <div class="font-medium text-base text-coffee-gold">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-coffee-cream">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();" class="text-coffee-cream">
                        Keluar
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
