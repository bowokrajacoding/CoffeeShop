<nav x-data="{ open: false }" class="bg-coffee-dark border-b border-coffee-brown">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('owner.dashboard') }}" class="text-coffee-gold font-bold text-xl flex items-center gap-2">
                        <span class="text-coffee-gold font-black uppercase tracking-tighter">Kedai Kopi</span>
                        <span>OWNER</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-4 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('owner.dashboard')" :active="request()->routeIs('owner.dashboard')" class="text-coffee-cream hover:text-coffee-gold active:text-coffee-gold">
                        Dashboard
                    </x-nav-link>
                    <x-nav-link :href="route('owner.inventory.index')" :active="request()->routeIs('owner.inventory.*')" class="text-coffee-cream hover:text-coffee-gold">
                        Inventaris
                    </x-nav-link>
                    <x-nav-link :href="route('owner.menu.index')" :active="request()->routeIs('owner.menu.*')" class="text-coffee-cream hover:text-coffee-gold">
                        Menu & Kategori
                    </x-nav-link>
                    <x-nav-link :href="route('owner.reports.index')" :active="request()->routeIs('owner.reports.*')" class="text-coffee-cream hover:text-coffee-gold">
                        Laporan
                    </x-nav-link>
                    <x-nav-link :href="route('owner.users.index')" :active="request()->routeIs('owner.users.*')" class="text-coffee-cream hover:text-coffee-gold">
                        Kelola Kasir
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-coffee-cream bg-coffee-brown hover:bg-coffee-dark focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }} (Owner)</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            Profil
                        </x-dropdown-link>

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
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-coffee-gold hover:text-coffee-cream hover:bg-coffee-brown focus:outline-none focus:bg-coffee-brown focus:text-coffee-cream transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-coffee-brown">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('owner.dashboard')" :active="request()->routeIs('owner.dashboard')" class="text-coffee-cream">
                Dashboard
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('owner.inventory.index')" :active="request()->routeIs('owner.inventory.*')" class="text-coffee-cream">
                Inventaris
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('owner.menu.index')" :active="request()->routeIs('owner.menu.*')" class="text-coffee-cream">
                Menu & Kategori
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('owner.reports.index')" :active="request()->routeIs('owner.reports.*')" class="text-coffee-cream">
                Laporan
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('owner.users.index')" :active="request()->routeIs('owner.users.*')" class="text-coffee-cream">
                Kelola Kasir
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-coffee-dark">
            <div class="px-4">
                <div class="font-medium text-base text-coffee-gold">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-coffee-cream">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="text-coffee-cream">
                    Profil
                </x-responsive-nav-link>

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
