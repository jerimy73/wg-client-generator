<x-guest-layout>
    <div class=" flex items-center justify-center bg-gradient-to-br from-sky-50 via-white to-indigo-100 px-4 py-12">
        <div class="w-full max-w-xl">
            <div
                class="relative overflow-hidden rounded-3xl border border-sky-200
                       bg-gradient-to-br from-sky-200/90 via-sky-100/90 to-white/90
                       backdrop-blur-xl p-10
                       shadow-[0_20px_40px_-15px_rgba(2,132,199,0.35)]
                       ring-1 ring-white/60"
            >
                {{-- subtle decorative gradient --}}
                <div class="pointer-events-none absolute -top-24 -right-24 h-64 w-64 rounded-full bg-sky-300/30 blur-3xl"></div>
                <div class="pointer-events-none absolute -bottom-24 -left-24 h-64 w-64 rounded-full bg-indigo-300/20 blur-3xl"></div>

                {{-- Logo --}}
                <div class="relative mb-8 flex justify-center">
                    <img
                        src="{{ asset('assets/images/logo.png') }}"
                        alt="Logo"
                        width="60px" height="60px"
                    />
                </div>

                {{-- Error --}}
                @if ($errors->any())
                    <div class="relative mb-6 rounded-2xl border border-rose-200 bg-white/70 px-4 py-3 text-sm text-rose-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="relative space-y-6">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label for="email" class="mb-1.5 block text-sm font-medium text-slate-800">
                            Email
                        </label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="nama@domain.com"
                            class="w-full rounded-2xl border border-sky-200 bg-white/70 px-5 py-3 text-slate-900 outline-none
                                   focus:border-sky-300 focus:ring-4 focus:ring-sky-100"
                        />
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="mb-1.5 block text-sm font-medium text-slate-800">
                            Password
                        </label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            required
                            autocomplete="current-password"
                            placeholder="••••••••"
                            class="w-full rounded-2xl border border-sky-200 bg-white/70 px-5 py-3 text-slate-900 outline-none
                                   focus:border-sky-300 focus:ring-4 focus:ring-sky-100"
                        />
                    </div>

                    {{-- Button --}}
                    <button
                        type="submit"
                        class="group w-full rounded-2xl
                               bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900
                               px-6 py-3.5 text-sm font-semibold text-white
                               shadow-md transition
                               hover:shadow-lg hover:brightness-110
                               focus:outline-none focus:ring-4 focus:ring-sky-100"
                    >
                        Login
                    </button>
                </form>

                <div class="relative mt-8 text-center text-xs text-slate-600/90">
                    vpn.jeri.my.id
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
