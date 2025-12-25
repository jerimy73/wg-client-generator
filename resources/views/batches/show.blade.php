<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="min-w-0">
                <div class="flex items-center gap-2">
                    <a href="{{ route('batches.index') }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M12.707 15.707a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 111.414 1.414L8.414 10l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                        </svg>
                        Kembali
                    </a>

                    @php
                        $statusStyle = fn($s) => match($s) {
                            'draft' => 'bg-slate-100 text-slate-700 ring-slate-200',
                            'generated' => 'bg-indigo-50 text-indigo-700 ring-indigo-200',
                            'revoked' => 'bg-rose-50 text-rose-700 ring-rose-200',
                            default => 'bg-slate-100 text-slate-700 ring-slate-200',
                        };
                    @endphp

                    <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold ring-1 {{ $statusStyle($batch->status) }}">
                        <span class="h-1.5 w-1.5 rounded-full
                            {{ $batch->status === 'draft' ? 'bg-slate-500' : '' }}
                            {{ $batch->status === 'generated' ? 'bg-indigo-600' : '' }}
                            {{ $batch->status === 'revoked' ? 'bg-rose-600' : '' }}
                        "></span>
                        {{ strtoupper($batch->status) }}
                    </span>
                </div>

                <h2 class="mt-4 truncate text-xl font-semibold tracking-tight text-slate-900">
                    {{ $batch->name }}
                </h2>
                @if($batch->requester_unit)
                    <div class="mt-2">
                        <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700 ring-1 ring-slate-200">
                            Unit Pemohon: {{ $batch->requester_unit }}
                        </span>
                    </div>
                @endif
                <p class="mt-1 text-sm text-slate-600">
                    {{ $batch->notes ?: 'Tidak ada catatan.' }}
                </p>
            </div>

            <div class="flex items-center gap-2">
                <button id="openAddClient"
                        class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm
                               hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"/>
                    </svg>
                    Tambah Client
                </button>

                @if($batch->status === 'draft')
                <form method="POST" action="{{ route('batches.destroy', $batch) }}"
                    class="inline"
                    onsubmit="return confirm('Yakin hapus batch ini? Semua client di dalamnya akan ikut terhapus.');">
                    @csrf
                    @method('DELETE')
                    <button class="inline-flex items-center gap-2 rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">
                        Hapus Batch
                    </button>
                </form>
                @endif

                <button id="openBulkActions"
                        class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                    Bulk Action
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.94a.75.75 0 011.08 1.04l-4.24 4.5a.75.75 0 01-1.08 0l-4.24-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">

            {{-- Alerts --}}
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

            {{-- Stats + Search --}}
            @php
                $total = $clients->count();
                $pending = $clients->where('status','pending')->count();
                $active = $clients->where('status','active')->count();
                $revoked = $clients->where('status','revoked')->count();

                $clientBadge = fn($s) => match($s) {
                    'pending' => 'bg-slate-100 text-slate-700 ring-slate-200',
                    'active' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
                    'revoked' => 'bg-rose-50 text-rose-700 ring-rose-200',
                    default => 'bg-slate-100 text-slate-700 ring-slate-200',
                };
            @endphp

            <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-slate-500">Total Client</p>
                    <p class="mt-1 text-2xl font-semibold text-slate-900">{{ $total }}</p>
                    <p class="mt-2 text-xs text-slate-500">Jumlah device yang tercatat pada batch ini.</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-slate-500">Pending</p>
                    <p class="mt-1 text-2xl font-semibold text-slate-900">{{ $pending }}</p>
                    <p class="mt-2 text-xs text-slate-500">Belum diprovision ke MikroTik (peer belum dibuat).</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-slate-500">Active</p>
                    <p class="mt-1 text-2xl font-semibold text-slate-900">{{ $active }}</p>
                    <p class="mt-2 text-xs text-slate-500">Sudah punya peer di MikroTik & file .conf.</p>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex-1">
                        <label class="text-sm font-medium text-slate-700">Cari client</label>
                        <div class="mt-2 flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 focus-within:ring-2 focus-within:ring-indigo-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.9 14.32a8 8 0 111.414-1.414l3.387 3.387a1 1 0 01-1.414 1.414l-3.387-3.387zM14 8a6 6 0 11-12 0 6 6 0 0112 0z" clip-rule="evenodd"/>
                            </svg>
                            <input id="clientSearch" type="text" placeholder="Cari label / nama pemilik / ID…"
                                   class="w-full border-0 bg-transparent p-0 text-sm text-slate-900 placeholder:text-slate-400 focus:ring-0" />
                        </div>
                        <p class="mt-2 text-xs text-slate-500">Filter instan pada halaman (tanpa reload).</p>
                    </div>

                    <div class="flex items-center gap-2">
                        <button data-status="all" class="cStatusBtn rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Semua</button>
                        <button data-status="pending" class="cStatusBtn rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Pending</button>
                        <button data-status="active" class="cStatusBtn rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Active</button>
                        <button data-status="revoked" class="cStatusBtn rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Revoked</button>
                    </div>
                </div>
            </div>

            {{-- Client Table --}}
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between border-b border-slate-200 px-5 py-4">
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Daftar Client</h3>
                        <p class="text-sm text-slate-500">Centang beberapa client untuk bulk action.</p>
                    </div>
                    <div class="text-sm text-slate-600">
                        <span id="visibleClientCount">0</span> ditampilkan ·
                        <span id="selectedCount">0</span> dipilih
                    </div>
                </div>

                <form id="bulkActionForm" method="POST" class="hidden">
                    @csrf
                </form>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 text-slate-600">
                            <tr class="text-left">
                                <th class="px-5 py-3 w-10">
                                    <input id="selectAll" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                </th>
                                <th class="px-5 py-3 font-semibold">Client</th>
                                <th class="px-5 py-3 font-semibold">VPN IP</th>
                                <th class="px-5 py-3 font-semibold">Status</th>
                                <th class="px-5 py-3 font-semibold text-right">Aksi</th>
                            </tr>
                        </thead>

                        <tbody id="clientTable">
                            @forelse($clients as $c)
                                @php
                                    $searchKey = strtolower(
                                        ($c->label ?? '').' '.
                                        ($c->owner_name ?? '').' '.
                                        ($c->owner_id ?? '')
                                    );
                                @endphp
                                <tr class="clientRow border-t border-slate-100 hover:bg-slate-50 transition"
                                    data-key="{{ $searchKey }}"
                                    data-status="{{ $c->status }}">
                                    <td class="px-5 py-4 align-top">
                                        <input type="checkbox" class="rowCheck h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                            value="{{ $c->id }}">
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="font-semibold text-slate-900">{{ $c->label }}</div>
                                        <div class="mt-1 text-xs text-slate-500">
                                            <span class="font-medium text-slate-700">{{ $c->owner_name ?: '—' }}</span>
                                            <span class="mx-1">·</span>
                                            <span class="font-mono">{{ $c->owner_id ?: '—' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 font-mono text-xs text-slate-700">
                                        {{ $c->vpn_ip ?: '—' }}
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold ring-1 {{ $clientBadge($c->status) }}">
                                            <span class="h-1.5 w-1.5 rounded-full
                                                {{ $c->status === 'pending' ? 'bg-slate-500' : '' }}
                                                {{ $c->status === 'active' ? 'bg-emerald-600' : '' }}
                                                {{ $c->status === 'revoked' ? 'bg-rose-600' : '' }}
                                            "></span>
                                            {{ strtoupper($c->status) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-right space-x-2 whitespace-nowrap">
                                        <div class="flex flex-wrap gap-2 justify-end">
                                            {{-- PENDING / REVOKED: boleh provision --}}
                                            @if(in_array($c->status, ['pending', 'revoked']))
                                                <form method="POST" action="{{ route('clients.provision', $c) }}" class="inline">
                                                    @csrf
                                                    <button class="rounded-xl bg-indigo-600 px-3 py-2 text-xs font-semibold text-white hover:bg-indigo-700">
                                                        Provision
                                                    </button>
                                                </form>
                                            @endif

                                            {{-- ACTIVE: tampilkan download + revoke --}}
                                            @if($c->status === 'active')
                                                <a href="{{ route('clients.download', $c) }}"
                                                class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                                                    Download .conf
                                                </a>

                                                <form method="POST" action="{{ route('clients.revoke', $c) }}" class="inline"
                                                    onsubmit="return confirm('Yakin revoke client ini?');">
                                                    @csrf
                                                    <button class="rounded-xl bg-amber-600 px-3 py-2 text-xs font-semibold text-white hover:bg-amber-700">
                                                        Revoke
                                                    </button>
                                                </form>
                                            @endif

                                            {{-- REVOKED: tampilkan provision ulang --}}
                                            @if($c->status === 'revoked')
                                                <span class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-semibold text-slate-600">
                                                    Revoked
                                                </span>
                                            @endif

                                            {{-- PENDING / REVOKED: boleh hapus --}}
                                            @if(in_array($c->status, ['pending', 'revoked']))
                                                <form method="POST" action="{{ route('clients.destroy', $c) }}" class="inline"
                                                    onsubmit="return confirm('Yakin hapus client ini? Data akan hilang dari sistem.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-700 hover:bg-rose-100">
                                                        Hapus
                                                    </button>
                                                </form>
                                            @endif
                                        </div>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-10 text-center text-slate-500">
                                        Belum ada client. Klik <span class="font-semibold">Tambah Client</span> untuk mulai.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-slate-200 px-5 py-4">
                    <p class="text-xs text-slate-500">
                        Catatan: tombol Provision/Revoke masih memakai route yang ada. Logic provisioning (API MikroTik + generate .conf) akan kita isi setelah UI beres.
                    </p>
                </div>
            </div>

        </div>
    </div>

    {{-- Modal: Tambah Client --}}
    <div id="clientModal" class="fixed inset-0 z-50 hidden">
        <div id="modalBackdrop" class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"></div>

        <div class="relative mx-auto mt-16 w-full max-w-lg px-4">
            <div class="rounded-2xl bg-white shadow-xl ring-1 ring-slate-200 overflow-hidden">
                <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Tambah Client</h3>
                        <p class="text-sm text-slate-500">Tambahkan device/user yang akan dibuatkan file .conf.</p>
                    </div>
                    <button id="closeModal"
                            class="rounded-xl border border-slate-200 bg-white p-2 text-slate-600 hover:bg-slate-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('clients.store', $batch) }}" class="px-5 py-5 space-y-4">
                    @csrf

                    <div>
                        <label class="text-sm font-medium text-slate-700">Label Device *</label>
                        <input name="label" required
                               class="mt-2 w-full rounded-xl border-slate-300 bg-white text-slate-900 shadow-sm
                                      focus:border-indigo-500 focus:ring-indigo-500"
                               placeholder="mis: laptop-operator-pangandaran">
                        @error('label') <div class="mt-2 text-sm text-rose-600">{{ $message }}</div> @enderror
                        <p class="mt-2 text-xs text-slate-500">
                            Disarankan unik dan konsisten: unit-nama-device (contoh: <span class="font-mono">uptdA-rina-laptop</span>).
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <div>
                            <label class="text-sm font-medium text-slate-700">Nama Pemilik</label>
                            <input name="owner_name"
                                   class="mt-2 w-full rounded-xl border-slate-300 bg-white text-slate-900 shadow-sm
                                          focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="opsional">
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-700">Nomor HP</label>
                            <input name="owner_id"
                                   class="mt-2 w-full rounded-xl border-slate-300 bg-white text-slate-900 shadow-sm
                                          focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="opsional">
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button type="button" id="cancelModal"
                                class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                            Batal
                        </button>
                        <button
                            class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700">
                            Simpan
                        </button>
                    </div>
                </form>
                <p class="mt-3 mb-3 text-center text-xs text-slate-400">
                    Tips: Untuk banyak device, bisa tambah berkali-kali lalu gunakan Bulk Action untuk Provision massal.
                </p>
            </div>
        </div>
    </div>

    {{-- Modal: Bulk Actions (UI saja dulu) --}}
    <div id="bulkMenu" class="fixed inset-0 z-40 hidden">
        <div id="bulkBackdrop" class="absolute inset-0"></div>
        <div class="absolute right-6 top-24 w-72 rounded-2xl border border-slate-200 bg-white shadow-lg overflow-hidden">
            <div class="px-4 py-3 border-b border-slate-200">
                <div class="text-sm font-semibold text-slate-900">Bulk Action</div>
                <div class="text-xs text-slate-500">Berlaku untuk client yang dicentang.</div>
            </div>
            <div class="p-2 space-y-2">
                <button id="bulkProvisionBtn" class="w-full rounded-xl bg-indigo-600 px-3 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                    Provision Selected
                </button>
                <button id="bulkRevokeBtn"
                        class="w-full rounded-xl bg-rose-600 px-3 py-2 text-sm font-semibold text-white hover:bg-rose-700">
                    Revoke Selected
                </button>
                <!-- <button class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                        onclick="alert('Nanti: Export mapping CSV');">
                    Export Mapping (CSV)
                </button> -->
            </div>
        </div>
    </div>

    {{-- JS interaktif: search/filter/select + modal --}}
    <script>
        (() => {
            // ===== Search + Filter =====
            const search = document.getElementById('clientSearch');
            const rows = Array.from(document.querySelectorAll('.clientRow'));
            const btns = Array.from(document.querySelectorAll('.cStatusBtn'));
            const visibleCount = document.getElementById('visibleClientCount');

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

            const apply = () => {
                const q = (search?.value || '').trim().toLowerCase();
                let count = 0;

                rows.forEach(r => {
                    const key = (r.dataset.key || '');
                    const status = (r.dataset.status || '');
                    const okText = !q || key.includes(q);
                    const okStatus = (activeStatus === 'all') || (status === activeStatus);
                    const ok = okText && okStatus;

                    r.style.display = ok ? '' : 'none';
                    if (ok) count++;
                });

                visibleCount.textContent = count.toString();
            };

            btns.forEach(b => {
                b.addEventListener('click', () => {
                    activeStatus = b.dataset.status;
                    setActiveButton();
                    apply();
                });
            });
            search?.addEventListener('input', apply);

            setActiveButton();
            apply();

            // ===== Checkbox select =====
            const selectAll = document.getElementById('selectAll');
            const rowChecks = Array.from(document.querySelectorAll('.rowCheck'));
            const selectedCount = document.getElementById('selectedCount');

            const updateSelected = () => {
                const n = rowChecks.filter(c => c.checked).length;
                selectedCount.textContent = n.toString();
                if (selectAll) {
                    const visibleChecks = rowChecks.filter(c => c.closest('tr')?.style.display !== 'none');
                    const allVisibleChecked = visibleChecks.length > 0 && visibleChecks.every(c => c.checked);
                    selectAll.checked = allVisibleChecked;
                    selectAll.indeterminate = !allVisibleChecked && visibleChecks.some(c => c.checked);
                }
            };

            rowChecks.forEach(c => c.addEventListener('change', updateSelected));
            if (selectAll) {
                selectAll.addEventListener('change', () => {
                    const visibleChecks = rowChecks.filter(c => c.closest('tr')?.style.display !== 'none');
                    visibleChecks.forEach(c => c.checked = selectAll.checked);
                    updateSelected();
                });
            }
            updateSelected();

            // ===== Modal Tambah Client =====
            const modal = document.getElementById('clientModal');
            const openBtn = document.getElementById('openAddClient');
            const closeBtn = document.getElementById('closeModal');
            const cancelBtn = document.getElementById('cancelModal');
            const backdrop = document.getElementById('modalBackdrop');

            const openModal = () => {
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
                // focus ke input pertama
                setTimeout(() => {
                    modal.querySelector('input[name="label"]')?.focus();
                }, 50);
            };

            const closeModal = () => {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            };

            openBtn?.addEventListener('click', openModal);
            closeBtn?.addEventListener('click', closeModal);
            cancelBtn?.addEventListener('click', closeModal);
            backdrop?.addEventListener('click', closeModal);

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
            });

            // ===== Bulk menu (UI) =====
            const bulkMenu = document.getElementById('bulkMenu');
            const openBulk = document.getElementById('openBulkActions');
            const bulkBackdrop = document.getElementById('bulkBackdrop');

            const openBulkMenu = () => {
                bulkMenu.classList.remove('hidden');
            };
            const closeBulkMenu = () => {
                bulkMenu.classList.add('hidden');
            };

            openBulk?.addEventListener('click', (e) => {
                e.preventDefault();
                bulkMenu.classList.contains('hidden') ? openBulkMenu() : closeBulkMenu();
            });
            bulkBackdrop?.addEventListener('click', closeBulkMenu);

            // ===== Bulk actions (real submit) =====
            const bulkProvisionBtn = document.getElementById('bulkProvisionBtn');
            const bulkRevokeBtn = document.getElementById('bulkRevokeBtn');
            const bulkForm = document.getElementById('bulkActionForm');

            const getSelectedIds = () => rowChecks
            .filter(c => c.checked && c.closest('tr')?.style.display !== 'none')
            .map(c => c.value);

            const submitBulk = (actionUrl, confirmText) => {
                const ids = getSelectedIds();
                if (ids.length < 1) return alert('Pilih minimal 1 client yang ditampilkan.');
                if (!confirm(confirmText + `\n\nTotal: ${ids.length} client`)) return;

                // reset form
                bulkForm.innerHTML = `@csrf`;
                bulkForm.action = actionUrl;
                bulkForm.method = 'POST';

                ids.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'client_ids[]';
                input.value = id;
                bulkForm.appendChild(input);
                });

                bulkForm.submit();
            };

            bulkProvisionBtn?.addEventListener('click', () => {
                closeBulkMenu();
                submitBulk("{{ route('clients.bulkProvision', $batch) }}", 'Provision semua client yang dipilih?');
            });

            bulkRevokeBtn?.addEventListener('click', () => {
                closeBulkMenu();
                submitBulk("{{ route('clients.bulkRevoke', $batch) }}", 'Revoke semua client yang dipilih?');
            });

        })();
        @if($errors->any())
            // auto open modal if validation error happens
            document.getElementById('clientModal')?.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        @endif
        

    </script>
</x-app-layout>
