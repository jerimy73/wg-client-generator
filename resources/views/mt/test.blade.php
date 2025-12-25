<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold tracking-tight text-slate-900">MikroTik API Test</h2>
                <p class="mt-1 text-sm text-slate-600">Validasi koneksi API sebelum provisioning WireGuard peer.</p>
            </div>
            <a href="{{ route('batches.index') }}"
               class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8 space-y-6">

            @if($data['ok'])
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 text-emerald-900">
                    <div class="flex items-start gap-3">
                        <div class="mt-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-600" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.707a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <div class="font-semibold">Koneksi API Berhasil</div>
                            <div class="mt-1 text-sm text-emerald-800">
                                Router: <span class="font-mono">{{ $data['identity'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <h3 class="text-base font-semibold text-slate-900 mb-4">Ringkasan</h3>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="rounded-xl bg-slate-50 p-4 ring-1 ring-slate-200">
                            <div class="text-xs text-slate-500">RouterOS Version</div>
                            <div class="mt-1 font-semibold text-slate-900">{{ $data['routeros_version'] }}</div>
                        </div>
                        <div class="rounded-xl bg-slate-50 p-4 ring-1 ring-slate-200">
                            <div class="text-xs text-slate-500">Board</div>
                            <div class="mt-1 font-semibold text-slate-900">{{ $data['board_name'] }}</div>
                        </div>
                        <div class="rounded-xl bg-slate-50 p-4 ring-1 ring-slate-200">
                            <div class="text-xs text-slate-500">CPU</div>
                            <div class="mt-1 font-semibold text-slate-900">{{ $data['cpu'] }} ({{ $data['cpu_count'] }} core)</div>
                        </div>
                        <div class="rounded-xl bg-slate-50 p-4 ring-1 ring-slate-200">
                            <div class="text-xs text-slate-500">Uptime</div>
                            <div class="mt-1 font-semibold text-slate-900">{{ $data['uptime'] }}</div>
                        </div>
                    </div>
                </div>
            @else
                <div class="rounded-2xl border border-rose-200 bg-rose-50 p-5 text-rose-900">
                    <div class="flex items-start gap-3">
                        <div class="mt-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-rose-600" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v4a1 1 0 102 0V7zm-1 8a1.25 1.25 0 100-2.5A1.25 1.25 0 0010 15z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <div class="font-semibold">Koneksi API Gagal</div>
                            <div class="mt-1 text-sm text-rose-800 break-words">
                                {{ $data['error'] }}
                            </div>
                            <div class="mt-3 text-xs text-rose-700">
                                Target: {{ $data['host'] }}:{{ $data['port'] }} · User: {{ $data['user'] }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <h3 class="text-base font-semibold text-slate-900 mb-2">Checklist cepat</h3>
                    <ul class="text-sm text-slate-600 space-y-1">
                        <li>• Pastikan service API RouterOS aktif dan port sesuai (di MikroTik).</li>
                        <li>• Pastikan firewall/NAT mengizinkan akses ke port API dari IP laptop kamu.</li>
                        <li>• Pastikan MT_USER/MT_PASS benar dan user punya permission API.</li>
                    </ul>
                </div>
            @endif

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="font-semibold text-slate-900">Refresh Test</div>
                        <div class="text-sm text-slate-500">Klik untuk test ulang setelah ubah firewall/port/password.</div>
                    </div>
                    <a href="{{ route('mt.test') }}"
                       class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                        Test Ulang
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
