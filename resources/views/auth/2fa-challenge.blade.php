<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-sky-50 via-white to-indigo-100 px-4 py-12">
        <div class="w-full max-w-md">
            <div class="rounded-3xl border border-sky-200 bg-gradient-to-br from-sky-200/90 via-sky-100/90 to-white/90 backdrop-blur-xl p-8 shadow-[0_20px_40px_-15px_rgba(2,132,199,0.35)] ring-1 ring-white/60">
                <div class="mb-6 text-center">
                    <div class="text-base font-semibold text-slate-900">Verifikasi 2FA</div>
                </div>

                @if ($errors->any())
                    <div class="mb-4 rounded-2xl border border-rose-200 bg-white/70 px-4 py-3 text-sm text-rose-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('2fa.verify') }}" class="space-y-4">
                    @csrf
                    <input
                        name="otp"
                        inputmode="numeric"
                        autocomplete="one-time-code"
                        placeholder="Kode 6 digit"
                        class="w-full rounded-2xl border border-sky-200 bg-white/70 px-4 py-3 outline-none focus:ring-4 focus:ring-sky-100"
                        required
                    />

                    <button type="submit"
                        class="w-full rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-sky-100">
                        Verifikasi
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
