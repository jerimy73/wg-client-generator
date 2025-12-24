<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold tracking-tight text-slate-900">Buat Batch Baru</h2>
                <p class="mt-1 text-sm text-slate-600">
                    Batch mengelompokkan permintaan pembuatan client WireGuard (per unit / gelombang).
                </p>
            </div>

            <a href="{{ route('batches.index') }}"
               class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M12.707 15.707a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 111.414 1.414L8.414 10l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8 space-y-6">

            {{-- Info card --}}
            <div class="rounded-2xl border border-indigo-200 bg-indigo-50 px-5 py-4 text-indigo-900">
                <div class="flex items-start gap-3">
                    <div class="mt-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-4a1 1 0 00-1 1v3a1 1 0 002 0V7a1 1 0 00-1-1zm0 8a1.25 1.25 0 100-2.5A1.25 1.25 0 0010 14z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <div class="font-semibold">Gunakan template supaya konsisten</div>
                        <div class="mt-1 text-sm text-indigo-800">
                            Pilih template, isi <span class="font-semibold">Unit Pemohon</span>, lalu sistem akan menyusun Nama Batch.
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form card --}}
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="border-b border-slate-200 px-6 py-4">
                    <h3 class="text-base font-semibold text-slate-900">Informasi Batch</h3>
                    <p class="mt-1 text-sm text-slate-500">Data ini membantu tracking permintaan VPN dan audit trail.</p>
                </div>

                <form method="POST" action="{{ route('batches.store') }}" class="px-6 py-6 space-y-5">
                    @csrf

                    {{-- Template + Unit --}}
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="text-sm font-medium text-slate-700">Template Nama Batch</label>
                            <select id="nameTemplate"
                                    class="mt-2 w-full rounded-xl border-slate-300 bg-white text-slate-900 shadow-sm
                                           focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">— Pilih Template —</option>
                                <option value="Operator Desa dan Kecamatan ">Operator Desa dan Kecamatan</option>
                                <option value="Operator Dinas">Operator Dinas</option>
                                <option value="Inspektorat">Inspektorat</option>
                                <option value="Sekretariat Daerah">Sekretariat Daerah</option>
                                <option value="Dinsos Provinsi">Dinsos Provinsi</option>
                                <option value="Kementerian Sosial">Kementerian Sosial</option>

                            </select>
                            <p class="mt-2 text-xs text-slate-500">Template akan mengisi otomatis “Nama Batch”.</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-slate-700">Unit Pemohon (requester_unit)</label>
                            <input id="requesterUnit" name="requester_unit" value="{{ old('requester_unit') }}"
                                   placeholder="Contoh: Kecamatan Pangandaran / Dinas Sosial"
                                   class="mt-2 w-full rounded-xl border-slate-300 bg-white text-slate-900 shadow-sm
                                          focus:border-indigo-500 focus:ring-indigo-500">
                            @error('requester_unit')
                                <div class="mt-2 text-sm text-rose-600">{{ $message }}</div>
                            @else
                                <div class="mt-2 text-xs text-slate-500">
                                    Disarankan diisi agar batch mudah dicari & cocok untuk audit.
                                </div>
                            @enderror
                        </div>
                    </div>

                    {{-- Nama batch --}}
                    <div>
                        <label class="text-sm font-medium text-slate-700">Nama Batch <span class="text-rose-600">*</span></label>
                        <div class="mt-2">
                            <input id="batchName" name="name" value="{{ old('name') }}" required
                                   placeholder="Contoh: UPTD A - PPKS - Laptop Operator - Gelombang 1"
                                   class="w-full rounded-xl border-slate-300 bg-white text-slate-900 shadow-sm
                                          focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        @error('name')
                            <div class="mt-2 text-sm text-rose-600">{{ $message }}</div>
                        @else
                            <div class="mt-2 text-xs text-slate-500">
                                Nama batch boleh diedit manual meskipun memakai template.
                            </div>
                        @enderror

                        <div class="mt-3 flex items-center gap-2">
                            <label class="text-xs font-semibold text-slate-600">Gelombang:</label>
                            <div class="flex items-center gap-2">
                                <button type="button" id="decWave"
                                        class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">-</button>
                                <input id="waveNumber" type="number" min="1" value="1"
                                       class="w-20 rounded-xl border-slate-300 bg-white text-center text-sm font-semibold text-slate-900 shadow-sm
                                              focus:border-indigo-500 focus:ring-indigo-500">
                                <button type="button" id="incWave"
                                        class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">+</button>
                            </div>
                            <span class="text-xs text-slate-500">Dipakai untuk placeholder <span class="font-mono">{n}</span>.</span>
                        </div>
                    </div>

                    {{-- Notes --}}
                    <div>
                        <label class="text-sm font-medium text-slate-700">Catatan (Opsional)</label>
                        <div class="mt-2">
                            <textarea name="notes" rows="4"
                                      placeholder="Contoh: 10 laptop operator. Prioritas minggu ini. Akses hanya host aplikasi (192.168.180.3)."
                                      class="w-full rounded-xl border-slate-300 bg-white text-slate-900 shadow-sm
                                             focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes') }}</textarea>
                        </div>
                        @error('notes')
                            <div class="mt-2 text-sm text-rose-600">{{ $message }}</div>
                        @else
                            <div class="mt-2 text-xs text-slate-500">
                                Catatan membantu saat audit / tracking permintaan (siapa, kapan, untuk apa).
                            </div>
                        @enderror
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-col-reverse gap-2 sm:flex-row sm:items-center sm:justify-between pt-2">
                        <div class="text-xs text-slate-500">
                            Setelah batch dibuat, client bisa ditambahkan via modal pada halaman detail batch.
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('batches.index') }}"
                               class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                Batal
                            </a>

                            <button
                                class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm
                                       hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M17 8h-2V6a3 3 0 00-3-3H8a3 3 0 00-3 3v2H3a1 1 0 000 2h2v6a3 3 0 003 3h4a1 1 0 000-2H8a1 1 0 01-1-1v-6h8v2a1 1 0 102 0V10h2a1 1 0 000-2z"/>
                                </svg>
                                Simpan Batch
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- JS: Template -> auto fill name --}}
            <script>
                (() => {
                    const template = document.getElementById('nameTemplate');
                    const unit = document.getElementById('requesterUnit');
                    const name = document.getElementById('batchName');
                    const wave = document.getElementById('waveNumber');
                    const dec = document.getElementById('decWave');
                    const inc = document.getElementById('incWave');

                    // Jangan override kalau user sudah mengetik manual setelah template dipakai
                    let autoMode = true;

                    const todayStr = () => {
                        const d = new Date();
                        const pad = n => String(n).padStart(2,'0');
                        return `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}`;
                    };

                    const build = () => {
                        if (!autoMode) return;

                        const t = (template.value || '').trim();
                        if (!t) return;

                        const u = (unit.value || 'UNIT').trim() || 'UNIT';
                        const n = Math.max(1, parseInt(wave.value || '1', 10));

                        let result = t.replaceAll('UNIT', u)
                                      .replaceAll('{n}', String(n))
                                      .replaceAll('{date}', todayStr());

                        name.value = result;
                    };

                    // Jika user mengetik manual di name, matikan autoMode
                    name.addEventListener('input', () => {
                        // kalau perubahan berasal dari build(), tetap autoMode
                        // jadi kita pakai heuristik: kalau template ada, dan user edit name, matikan auto
                        autoMode = false;
                    });

                    // Kalau user memilih template, nyalakan autoMode lagi
                    template.addEventListener('change', () => {
                        autoMode = true;
                        build();
                    });

                    unit.addEventListener('input', () => build());
                    wave.addEventListener('input', () => build());

                    dec.addEventListener('click', () => {
                        wave.value = Math.max(1, parseInt(wave.value || '1', 10) - 1);
                        build();
                    });
                    inc.addEventListener('click', () => {
                        wave.value = Math.max(1, parseInt(wave.value || '1', 10) + 1);
                        build();
                    });

                    // Jika halaman reload dengan old(name) terisi, jangan override.
                    if ((name.value || '').trim().length > 0) {
                        autoMode = false;
                    }
                })();
            </script>

        </div>
    </div>
</x-app-layout>
