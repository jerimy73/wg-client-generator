<nav class="sticky top-0 z-50 border-b border-sky-200 bg-sky-200/90 backdrop-blur supports-[backdrop-filter]:bg-sky-200/70">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 justify-between items-center">
            
            {{-- Kiri: Logo & Dashboard (Dashboard sembunyi di HP) --}}
            <div class="flex items-center gap-4">
                <a href="{{ route('batches.index') }}" class="flex items-center">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="h-8 w-auto rounded-lg bg-white p-1 ring-1 ring-white/60">
                </a>

                {{-- Dashboard: Hanya muncul di tablet/desktop --}}
                <div class="hidden sm:flex">
                    <a href="{{ route('batches.index') }}"
                       class="rounded-xl px-3 py-2 text-sm font-semibold {{ request()->routeIs('batches.*') ? 'bg-white/70 text-sky-900 ring-1 ring-sky-300' : 'text-sky-900 hover:bg-white/50' }}">
                        Dashboard
                    </a>
                </div>
            </div>

            {{-- Kanan: Test Page (Selalu Muncul) & User Dropdown --}}
            <div class="flex items-center gap-2">
                {{-- Tombol Test Page: Selalu muncul di HP & Desktop --}}
                <a href="{{ route('mt.test') }}"
                   class="inline-flex items-center rounded-xl px-3 py-2 text-xs sm:text-sm font-bold
                          {{ request()->routeIs('mt.test') ? 'bg-white/70 text-sky-900 ring-1 ring-sky-300' : 'text-sky-900  hover:bg-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-globe-lock-icon lucide-globe-lock"><path d="M15.686 15A14.5 14.5 0 0 1 12 22a14.5 14.5 0 0 1 0-20 10 10 0 1 0 9.542 13"/><path d="M2 12h8.5"/><path d="M20 6V4a2 2 0 1 0-4 0v2"/><rect width="8" height="5" x="14" y="6" rx="1"/></svg>
                    <span>Test Page</span>
                </a>

                {{-- User Dropdown --}}
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center rounded-xl bg-yellow-100 p-2 sm:px-3 sm:py-2 text-xs sm:text-sm font-semibold text-sky-900 ring-1  hover:bg-white">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-lock-keyhole-icon lucide-lock-keyhole"><circle cx="12" cy="16" r="1"/><rect x="3" y="10" width="18" height="12" rx="2"/><path d="M7 10V7a5 5 0 0 1 10 0v3"/></svg>
                            <span class="hidden sm:inline-block me-2">{{ Auth::user()->name }}</span>
                            <svg class="h-5 w-5 sm:h-4 sm:w-4 fill-current" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.94a.75.75 0 011.08 1.04l-4.24 4.5a.75.75 0 01-1.08 0l-4.24-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">Profile</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">Log Out</x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
</nav>