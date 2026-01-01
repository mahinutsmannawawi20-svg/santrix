@extends('owner.layouts.app')

@section('title', 'Edit Subscription')
@section('subtitle', 'Update package and status for ' . $pesantren->nama)

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
        <form action="{{ route('owner.pesantren.update', $pesantren->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Package Selection -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Subscription Package</label>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Basic -->
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="package" value="basic" class="peer sr-only" {{ $pesantren->package == 'basic' ? 'checked' : '' }}>
                        <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-indigo-500 peer-checked:bg-indigo-50/50 transition-all hover:border-slate-300">
                            <h3 class="font-bold text-slate-800 peer-checked:text-indigo-700">Basic</h3>
                            <p class="text-xs text-slate-500 mt-1">Foundational features</p>
                        </div>
                        <div class="absolute top-4 right-4 text-indigo-600 opacity-0 peer-checked:opacity-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                    </label>

                    <!-- Advance -->
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="package" value="advance" class="peer sr-only" {{ $pesantren->package == 'advance' ? 'checked' : '' }}>
                        <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-indigo-500 peer-checked:bg-indigo-50/50 transition-all hover:border-slate-300">
                            <h3 class="font-bold text-slate-800 peer-checked:text-indigo-700">Advance</h3>
                            <p class="text-xs text-slate-500 mt-1">Extended limits</p>
                        </div>
                        <div class="absolute top-4 right-4 text-indigo-600 opacity-0 peer-checked:opacity-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                    </label>

                    <!-- Enterprise -->
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="package" value="enterprise" class="peer sr-only" {{ $pesantren->package == 'enterprise' ? 'checked' : '' }}>
                        <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-indigo-500 peer-checked:bg-indigo-50/50 transition-all hover:border-slate-300">
                            <h3 class="font-bold text-slate-800 peer-checked:text-indigo-700">Enterprise</h3>
                            <p class="text-xs text-slate-500 mt-1">Full access</p>
                        </div>
                         <div class="absolute top-4 right-4 text-indigo-600 opacity-0 peer-checked:opacity-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Expiry Date -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Expiry Date</label>
                <input type="date" name="expired_at" value="{{ $pesantren->expired_at ? \Carbon\Carbon::parse($pesantren->expired_at)->format('Y-m-d') : '' }}" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-shadow text-slate-700">
                <p class="text-xs text-slate-500 mt-1">Set the date when the subscription service should end.</p>
            </div>

            <!-- Bank Details (Advance Requirement) -->
            <div id="bank-section" class="hidden bg-indigo-50 border border-indigo-100 rounded-xl p-6">
                <h3 class="text-sm font-bold text-indigo-900 mb-4 flex items-center gap-2">
                    <i data-feather="credit-card" class="w-4 h-4"></i>
                    Rekening Pencairan Dana (Wajib untuk Advance)
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-indigo-700 mb-1">Nama Bank</label>
                        <input type="text" name="bank_name" value="{{ old('bank_name', $pesantren->bank_name) }}" placeholder="Contoh: BCA, BSI" class="w-full px-3 py-2 border border-indigo-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div>
                            <label class="block text-xs font-semibold text-indigo-700 mb-1">No. Rekening</label>
                            <input type="text" name="account_number" value="{{ old('account_number', $pesantren->account_number) }}" class="w-full px-3 py-2 border border-indigo-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-indigo-700 mb-1">Atas Nama</label>
                            <input type="text" name="account_name" value="{{ old('account_name', $pesantren->account_name) }}" class="w-full px-3 py-2 border border-indigo-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                </div>
                <p class="text-xs text-indigo-600 mt-3">*Dana dari Payment Gateway Syahriah akan dicairkan ke rekening ini.</p>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    if (typeof feather !== 'undefined') { feather.replace(); }
                    
                    const radios = document.querySelectorAll('input[name="package"]');
                    const bankSection = document.getElementById('bank-section');
                    
                    function toggleBank() {
                        const selectedRadio = document.querySelector('input[name="package"]:checked');
                        if (!selectedRadio) return;
                        
                        const selected = selectedRadio.value;
                        if(selected === 'advance' || selected === 'enterprise') {
                            bankSection.classList.remove('hidden');
                        } else {
                            // Show only if data exists, otherwise hide for streamlined UI
                            if ('{{ $pesantren->bank_name }}' === '') {
                                bankSection.classList.add('hidden');
                            } else {
                                bankSection.classList.remove('hidden');
                            }
                        }
                    }

                    radios.forEach(radio => radio.addEventListener('change', toggleBank));
                    toggleBank(); // Run on load
                });
            </script>

            <!-- Status -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Tenant Status</label>
                <select name="status" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-shadow text-slate-700 bg-white">
                    <option value="active" {{ $pesantren->status == 'active' ? 'selected' : '' }}>Active (Allow Access)</option>
                    <option value="suspended" {{ $pesantren->status == 'suspended' ? 'selected' : '' }}>Suspended (Block Access)</option>
                </select>
            </div>

            <!-- Actions -->
            <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('owner.pesantren.show', $pesantren->id) }}" class="px-5 py-2.5 text-slate-600 font-medium hover:bg-slate-50 rounded-lg transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/30">
                    Update Subscription
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
