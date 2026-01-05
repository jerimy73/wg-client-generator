@php
    $user = auth()->user();
    $enabled = !empty($user->two_factor_secret) && !empty($user->two_factor_confirmed_at);
@endphp

<div class="space-y-4">
    <div class="text-sm text-slate-600">
        Two-Factor Authentication (2FA) menambah keamanan dengan kode OTP dari aplikasi Google Authenticator.
    </div>

    @if (!$enabled)
        <form method="POST" action="{{ route('2fa.enable') }}">
            @csrf
            <button type="submit"
                class="rounded-2xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800
                       focus:outline-none focus:ring-4 focus:ring-sky-100">
                Aktifkan 2FA
            </button>
        </form>

        @if (session('2fa_qr'))
            <div class="mt-4 rounded-2xl border border-sky-200 bg-white/70 p-4">
                <div class="text-sm font-semibold text-slate-900">Scan QR</div>
                <div class="mt-2">{!! session('2fa_qr') !!}</div>

                <form method="POST" action="{{ route('2fa.confirm') }}" class="mt-4 space-y-3">
                    @csrf
                    <input
                        name="otp"
                        inputmode="numeric"
                        autocomplete="one-time-code"
                        placeholder="Masukkan kode 6 digit"
                        class="w-full rounded-2xl border border-sky-200 bg-white/70 px-4 py-3 outline-none
                               focus:border-sky-300 focus:ring-4 focus:ring-sky-100"
                        required
                    />

                    <button type="submit"
                        class="w-full rounded-2xl bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900
                               px-4 py-3 text-sm font-semibold text-white hover:brightness-110
                               focus:outline-none focus:ring-4 focus:ring-sky-100">
                        Konfirmasi 2FA
                    </button>
                </form>

                @if ($errors->any())
                    <div class="mt-3 text-sm text-rose-700">{{ $errors->first() }}</div>
                @endif
            </div>
        @endif
    @else
        <div class="rounded-2xl border border-emerald-200 bg-white/70 p-4 text-sm text-emerald-800">
            2FA sudah aktif.
        </div>

        <form method="POST" action="{{ route('2fa.disable') }}">
            @csrf
            @method('DELETE')
            <button type="submit"
                class="rounded-2xl border border-rose-200 bg-white/70 px-4 py-2.5 text-sm font-semibold text-rose-700
                       hover:bg-rose-50 focus:outline-none focus:ring-4 focus:ring-rose-100">
                Nonaktifkan 2FA
            </button>
        </form>
    @endif
</div>
