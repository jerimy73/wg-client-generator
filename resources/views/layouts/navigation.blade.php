<nav class="sticky top-0 z-50 border-b border-sky-200 bg-sky-200/90 backdrop-blur supports-[backdrop-filter]:bg-sky-200/70">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 justify-between">
            <div class="flex items-center gap-6">
                {{-- Logo placeholder: nanti kamu ganti sendiri --}}
                <a href="{{ route('batches.index') }}" class="flex items-center gap-3">
                    <img src="{{ asset('assets/images/logo.png') }}"
                        alt="App Logo"
                        class="h-9 w-auto rounded-lg ring-1 ring-white/60 bg-white p-1">
                </a>

                {{-- Menu --}}
                <div class="hidden space-x-2 sm:flex">
                    <a href="{{ route('batches.index') }}"
                       class="inline-flex items-center rounded-xl px-3 py-2 text-sm font-semibold
                              {{ request()->routeIs('batches.*') ? 'bg-white/70 text-sky-900 ring-1 ring-sky-300' : 'text-sky-900 hover:bg-white/50' }}">
                        Dashboard
                    </a>

                    <a href="{{ route('mt.test') }}"
                       class="inline-flex items-center rounded-xl px-3 py-2 text-sm font-semibold
                              {{ request()->routeIs('mt.test') ? 'bg-white/70 text-sky-900 ring-1 ring-sky-300' : 'text-sky-900 hover:bg-white/50' }}">
                        Test Page
                    </a>
                </div>
            </div>

            {{-- Right: User dropdown bawaan Breeze --}}
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center rounded-xl bg-white/70 px-3 py-2 text-sm font-semibold text-sky-900 ring-1 ring-sky-300 hover:bg-white">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-2">
                                <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.94a.75.75 0 011.08 1.04l-4.24 4.5a.75.75 0 01-1.08 0l-4.24-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            Profile
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                Log Out
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            {{-- Mobile burger (optional): kalau kamu mau, kita rapikan belakangan --}}
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                        class="inline-flex items-center justify-center rounded-xl p-2 text-sky-900 hover:bg-white/50 focus:bg-white/50 focus:outline-none">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }"
                              class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{'hidden': ! open, 'inline-flex': open }"
                              class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile menu: minimal --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-sky-200">
        <div class="space-y-1 px-4 py-3">
            <a href="{{ route('batches.index') }}"
               class="block rounded-xl px-3 py-2 text-sm font-semibold text-sky-900 hover:bg-white/50">
                Dashboard
            </a>
            <a href="{{ route('mt.test') }}"
               class="block rounded-xl px-3 py-2 text-sm font-semibold text-sky-900 hover:bg-white/50">
                Test Page
            </a>
        </div>
    </div>
</nav>
