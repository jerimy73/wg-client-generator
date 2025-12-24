<x-app-layout>
    <div class="py-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">

            {{-- Alert --}}
            @if(session('ok'))
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-900">
                    <div class="flex items-start gap-2">
                        <div class="mt-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.707a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="text-sm font-medium">{{ session('ok') }}</div>
                    </div>
                </div>
            @endif

            @if(session('err'))
                <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-rose-900">
                    <div class="flex items-start gap-2">
                        <div class="mt-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-rose-600" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v4a1 1 0 102 0V7zm-1 8a1.25 1.25 0 100-2.5A1.25 1.25 0 0010 15z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="text-sm font-medium">{{ session('err') }}</div>
                    </div>
                </div>
            @endif

            {{-- Top cards --}}
            @php
                $total = $batches->total();
                $draft = $batches->getCollection()->where('status','draft')->count();
                $generated = $batches->getCollection()->where('status','generated')->count();
                $revoked = $batches->getCollection()->where('status','revoked')->count();

                $statusStyle = fn($s) => match($s) {
                    'draft' => 'bg-slate-100 text-slate-700 ring-slate-200',
                    'generated' => 'bg-indigo-50 text-indigo-700 ring-indigo-200',
                    'revoked' => 'bg-rose-50 text-rose-700 ring-rose-200',
                    default => 'bg-slate-100 text-slate-700 ring-slate-200',
                };

                $statusLabel = fn($s) => strtoupper($s);
            @endphp

            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Total Batch</p>
                            <p class="mt-1 text-2xl font-semibold text-slate-900">{{ $total }}</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 p-3 ring-1 ring-slate-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate-600" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M4 7a3 3 0 013-3h10a3 3 0 013 3v10a3 3 0 01-3 3H7a3 3 0 01-3-3V7zm3-1a1 1 0 00-1 1v1h12V7a1 1 0 00-1-1H7zm11 5H6v6a1 1 0 001 1h10a1 1 0 001-1v-6z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Draft (halaman ini)</p>
                            <p class="mt-1 text-2xl font-semibold text-slate-900">{{ $draft }}</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 p-3 ring-1 ring-slate-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate-600" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M7 3a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 002-2V9.414a2 2 0 00-.586-1.414l-2.414-2.414A2 2 0 0016.586 5H7zm9 3.414L18.586 9H16a0 0 0 010 0V6.414z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Generated (halaman ini)</p>
                            <p class="mt-1 text-2xl font-semibold text-slate-900">{{ $generated }}</p>
                        </div>
                        <div class="rounded-2xl bg-indigo-50 p-3 ring-1 ring-indigo-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2a10 10 0 100 20 10 10 0 000-20zm1 5a1 1 0 10-2 0v5a1 1 0 00.293.707l3 3a1 1 0 001.414-1.414L13 11.586V7z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

           

            {{-- Search + filter (client-side interaktif) --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex-1">
                        <label class="text-sm font-medium text-slate-700">Cari batch</label>
                        <div class="mt-2 flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 focus-within:ring-2 focus-within:ring-indigo-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.9 14.32a8 8 0 111.414-1.414l3.387 3.387a1 1 0 01-1.414 1.414l-3.387-3.387zM14 8a6 6 0 11-12 0 6 6 0 0112 0z" clip-rule="evenodd"/>
                            </svg>
                            <input id="batchSearch" type="text" placeholder="Ketik nama batch…"
                                   class="w-full border-0 bg-transparent p-0 text-sm text-slate-900 placeholder:text-slate-400 focus:ring-0" />
                        </div>
                        <p class="mt-2 text-xs text-slate-500">Filter ini bekerja instan di halaman (tanpa reload).</p>
                    </div>

                    <div class="flex items-center gap-2">
                        <button data-status="all"
                                class="statusBtn rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                            Semua
                        </button>
                        <button data-status="draft"
                                class="statusBtn rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                            Draft
                        </button>
                        <button data-status="generated"
                                class="statusBtn rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                            Generated
                        </button>
                    </div>
                </div>
            </div>

            {{-- List: table -> cards responsive --}}
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Daftar Batch</h3>
                        <p class="text-sm text-slate-500">Klik “Detail” untuk mengelola client dan provisioning.</p>
                    </div>
                    <div class="text-sm text-slate-600">
                        <a href="{{ route('batches.create') }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm
                                hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"/>
                            </svg>
                            Buat Batch
                        </a>
                    </div>
                </div>
                

                {{-- Desktop table --}}
                <div class="hidden md:block">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-slate-50 text-slate-600">
                                <tr class="text-left">
                                    <th class="px-5 py-3 font-semibold">Batch</th>
                                    <th class="px-5 py-3 font-semibold">Status</th>
                                    <th class="px-5 py-3 font-semibold">Dibuat</th>
                                    <th class="px-5 py-3 font-semibold text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="batchTable">
                                @forelse($batches as $b)
                                    <tr class="batchRow border-t border-slate-100 hover:bg-slate-50 transition"
                                        data-name="{{ strtolower($b->name) }}"
                                        data-status="{{ $b->status }}">
                                        <td class="px-5 py-4">
                                            <div class="font-semibold text-slate-900">{{ $b->name }}</div>
                                            <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-slate-500">
                                                @if($b->requester_unit)
                                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 font-semibold text-slate-700 ring-1 ring-slate-200">
                                                        {{ $b->requester_unit }}
                                                    </span>
                                                @endif
                                                <span class="line-clamp-1">{{ $b->notes ?: '—' }}</span>
                                            </div>

                                        </td>
                                        <td class="px-5 py-4">
                                            <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold ring-1 {{ $statusStyle($b->status) }}">
                                                <span class="h-1.5 w-1.5 rounded-full
                                                    {{ $b->status === 'draft' ? 'bg-slate-500' : '' }}
                                                    {{ $b->status === 'generated' ? 'bg-indigo-600' : '' }}
                                                    {{ $b->status === 'revoked' ? 'bg-rose-600' : '' }}
                                                "></span>
                                                {{ $statusLabel($b->status) }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-4 text-slate-700">
                                            <div>{{ $b->created_at->format('d M Y') }}</div>
                                            <div class="text-xs text-slate-500">{{ $b->created_at->format('H:i') }}</div>
                                        </td>
                                        <td class="px-5 py-4 text-right">
                                            <a href="{{ route('batches.show', $b) }}"
                                               class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-800">
                                                Detail
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10.293 15.707a1 1 0 010-1.414L13.586 11H4a1 1 0 110-2h9.586l-3.293-3.293a1 1 0 011.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-5 py-10 text-center text-slate-500">
                                            Belum ada batch. Klik <span class="font-semibold">Buat Batch</span> untuk mulai.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Mobile cards --}}
                <div class="md:hidden p-5 space-y-3" id="batchCards">
                    @foreach($batches as $b)
                        <div class="batchCard rounded-2xl border border-slate-200 p-4 hover:bg-slate-50 transition"
                             data-name="{{ strtolower($b->name) }}"
                             data-status="{{ $b->status }}">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="truncate font-semibold text-slate-900">{{ $b->name }}</div>
                                    <div class="mt-1 text-xs text-slate-500 line-clamp-2">{{ $b->notes ?: '—' }}</div>
                                </div>
                                <span class="inline-flex shrink-0 items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold ring-1 {{ $statusStyle($b->status) }}">
                                    <span class="h-1.5 w-1.5 rounded-full
                                        {{ $b->status === 'draft' ? 'bg-slate-500' : '' }}
                                        {{ $b->status === 'generated' ? 'bg-indigo-600' : '' }}
                                        {{ $b->status === 'revoked' ? 'bg-rose-600' : '' }}
                                    "></span>
                                    {{ $statusLabel($b->status) }}
                                </span>
                            </div>

                            <div class="mt-4 flex items-center justify-between">
                                <div class="text-xs text-slate-500">
                                    {{ $b->created_at->format('d M Y, H:i') }}
                                </div>
                                <a href="{{ route('batches.show', $b) }}"
                                   class="rounded-xl bg-slate-900 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-800">
                                    Detail
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="border-t border-slate-200 px-5 py-4">
                    {{ $batches->links() }}
                </div>
            </div>

            {{-- Client-side filter JS (ringan, tanpa dependency) --}}
            <script>
                (() => {
                    const search = document.getElementById('batchSearch');
                    const rows = Array.from(document.querySelectorAll('.batchRow'));
                    const cards = Array.from(document.querySelectorAll('.batchCard'));
                    const btns = Array.from(document.querySelectorAll('.statusBtn'));
                    
                    let activeStatus = 'all';

                    const setActiveButton = () => {
                        btns.forEach(b => {
                            const on = b.dataset.status === activeStatus;
                            b.classList.toggle('bg-slate-900', on);
                            b.classList.toggle('text-white', on);
                            b.classList.toggle('border-slate-900', on);

                            b.classList.toggle('bg-white', !on);
                            b.classList.toggle('text-slate-700', !on);
                            b.classList.toggle('border-slate-200', !on);
                        });
                    };

                    const match = (name, status, q) => {
                        const okText = !q || name.includes(q);
                        const okStatus = (activeStatus === 'all') || (status === activeStatus);
                        return okText && okStatus;
                    };

                    const apply = () => {
                        const q = (search?.value || '').trim().toLowerCase();
                        let count = 0;

                        rows.forEach(r => {
                            const ok = match(r.dataset.name || '', r.dataset.status || '', q);
                            r.style.display = ok ? '' : 'none';
                            if (ok) count++;
                        });

                        cards.forEach(c => {
                            const ok = match(c.dataset.name || '', c.dataset.status || '', q);
                            c.style.display = ok ? '' : 'none';
                            if (ok) count++;
                        });

                        // Catatan: table dan cards tidak tampil bersamaan (responsif),
                        // tapi kita tetap hitung keduanya; agar angka tetap masuk akal,
                        // ambil nilai dari elemen yang sedang tampil.
                        const isMobile = window.matchMedia('(max-width: 767px)').matches;
                        let realCount = 0;
                        if (isMobile) {
                            realCount = cards.filter(c => c.style.display !== 'none').length;
                        } else {
                            realCount = rows.filter(r => r.style.display !== 'none').length;
                        }
                        
                    };

                    btns.forEach(b => {
                        b.addEventListener('click', () => {
                            activeStatus = b.dataset.status;
                            setActiveButton();
                            apply();
                        });
                    });

                    search?.addEventListener('input', apply);

                    // init
                    setActiveButton();
                    apply();
                })();
            </script>

        </div>
    </div>
</x-app-layout>
