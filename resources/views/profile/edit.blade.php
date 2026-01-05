<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-slate-900 leading-tight">
                {{ __('Profile') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-5">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-1">
            {{-- background panel --}}
            <div class="relative overflow-hidden rounded-3xl border border-sky-200 bg-gradient-to-br from-sky-50 via-white to-indigo-50 shadow-sm ring-1 ring-white/60">
                <div class="pointer-events-none absolute -top-24 -right-24 h-72 w-72 rounded-full bg-sky-200/40 blur-3xl"></div>
                <div class="pointer-events-none absolute -bottom-24 -left-24 h-72 w-72 rounded-full bg-indigo-200/30 blur-3xl"></div>

                <div class="relative p-4 sm:p-8">
                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                        {{-- Card: Profile Information --}}
                        <div class="rounded-2xl border border-sky-200 bg-sky-200/60 backdrop-blur p-6 shadow-[0_16px_30px_-18px_rgba(2,132,199,0.35)]">

                            <div class="[&>form]:space-y-4 [&_label]:text-slate-800 [&_label]:font-medium">
                                @include('profile.partials.update-profile-information-form')
                            </div>
                        </div>

                        {{-- Card: Update Password --}}
                        <div class="rounded-2xl border border-sky-200 bg-sky-200/60 backdrop-blur p-6 shadow-[0_16px_30px_-18px_rgba(2,132,199,0.35)]">

                            <div class="[&>form]:space-y-4 [&_label]:text-slate-800 [&_label]:font-medium">
                                @include('profile.partials.update-password-form')
                            </div>
                        </div>

                        {{-- Card : 2FA --}}
                        <div class="rounded-2xl border border-sky-200 bg-sky-200/60 backdrop-blur p-6 shadow-[0_16px_30px_-18px_rgba(2,132,199,0.35)]">
                            <div class="mb-4">
                                <h3 class="text-base font-semibold text-slate-900">Two-Factor Authentication</h3>
                                <p class="mt-1 text-sm text-slate-600">Tambahkan OTP saat login.</p>
                            </div>

                            @include('profile.partials.two-factor-form')
                        </div>

                    </div>

                    {{-- Note / footer --}}
                    <div class="mt-6 text-xs text-slate-500">
                        Tip: Setelah mengubah password, disarankan logout dari perangkat lain jika diperlukan.
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Listen for form submissions
        document.addEventListener('DOMContentLoaded', function() {
            // Profile update form
            const profileForm = document.querySelector('#profile-update-form');
            if (profileForm) {
                profileForm.addEventListener('submit', function(e) {
                    // Optional: Show loading state
                    const submitBtn = profileForm.querySelector('button[type="submit"]');
                    const originalText = submitBtn.textContent;
                    submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mr-2 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Menyimpan...';
                    submitBtn.disabled = true;
                    
                });
            }

            // Password update form
            const passwordForm = document.querySelector('#password-update-form');
            if (passwordForm) {
                passwordForm.addEventListener('submit', function(e) {
                    // Optional: Show loading state
                    const submitBtn = passwordForm.querySelector('button[type="submit"]');
                    const originalText = submitBtn.textContent;
                    submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mr-2 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Menyimpan...';
                    submitBtn.disabled = true;
                });
            }
        });
    </script>
    @endpush
</x-app-layout>