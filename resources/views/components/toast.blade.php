@props(['type' => 'success', 'message' => '', 'autoClose' => true])

<div x-data="{
    show: false,
    message: '{{ $message }}',
    type: '{{ $type }}',
    autoClose: {{ $autoClose ? 'true' : 'false' }},
    timeout: null,
    init() {
        if (this.message) {
            this.showToast(this.message, this.type);
        }
        
        // Listen for custom events
        window.addEventListener('toast-success', (e) => {
            this.showToast(e.detail.message, 'success');
        });
        
        window.addEventListener('toast-error', (e) => {
            this.showToast(e.detail.message, 'error');
        });
        
        window.addEventListener('toast-warning', (e) => {
            this.showToast(e.detail.message, 'warning');
        });
        
        window.addEventListener('toast-info', (e) => {
            this.showToast(e.detail.message, 'info');
        });
    },
    showToast(message, type) {
        this.message = message;
        this.type = type;
        this.show = true;
        
        if (this.autoClose) {
            clearTimeout(this.timeout);
            this.timeout = setTimeout(() => {
                this.show = false;
            }, 5000);
        }
    },
    close() {
        this.show = false;
        clearTimeout(this.timeout);
    }
}" 
x-show="show" 
x-transition:enter="transition ease-out duration-300"
x-transition:enter-start="opacity-0 translate-y-2"
x-transition:enter-end="opacity-100 translate-y-0"
x-transition:leave="transition ease-in duration-300"
x-transition:leave-start="opacity-100 translate-y-0"
x-transition:leave-end="opacity-0 translate-y-2"
class="fixed top-4 right-4 z-50 max-w-sm w-full">
    <div :class="{
        'bg-emerald-50 border-emerald-200 text-emerald-800': type === 'success',
        'bg-rose-50 border-rose-200 text-rose-800': type === 'error',
        'bg-amber-50 border-amber-200 text-amber-800': type === 'warning',
        'bg-sky-50 border-sky-200 text-sky-800': type === 'info'
    }" 
    class="rounded-xl border p-4 shadow-lg">
        <div class="flex items-start gap-3">
            <!-- Icon -->
            <div :class="{
                'text-emerald-500': type === 'success',
                'text-rose-500': type === 'error',
                'text-amber-500': type === 'warning',
                'text-sky-500': type === 'info'
            }">
                <template x-if="type === 'success'">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </template>
                <template x-if="type === 'error'">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </template>
                <template x-if="type === 'warning'">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </template>
                <template x-if="type === 'info'">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </template>
            </div>
            
            <!-- Message -->
            <div class="flex-1">
                <h3 class="font-semibold" x-text="type === 'success' ? 'Berhasil!' : type === 'error' ? 'Error!' : type === 'warning' ? 'Peringatan!' : 'Info!'"></h3>
                <p class="mt-1 text-sm" x-text="message"></p>
            </div>
            
            <!-- Close Button -->
            <button @click="close" class="text-slate-400 hover:text-slate-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
</div>