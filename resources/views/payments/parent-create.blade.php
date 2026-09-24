<x-app-layout>
    <x-slot name="header"><h2 class="text-2xl font-semibold text-slate-900">Buat bayaran</h2></x-slot>
    <div class="py-8"><div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8"><div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-sm text-slate-500">Pilih keluarga, semak jumlah dan teruskan ke gerbang pembayaran.</p>
        <form method="POST" action="{{ route('payments.store') }}" class="mt-6 space-y-5">@csrf
            <input type="hidden" name="school_id" id="school_id">
            <div><label class="block text-sm font-medium">Keluarga</label><select name="family_id" id="family_id" class="mt-1 w-full rounded-lg border-slate-300" required><option value="">Pilih keluarga</option>@foreach($families as $family)<option value="{{ $family->id }}" data-school="{{ $family->school_id }}" data-balance="{{ $family->charges->sum('balance_amount') }}">{{ $family->name }} — {{ $family->school?->name }}</option>@endforeach</select></div>
            <div class="rounded-xl bg-slate-50 p-4"><p class="text-sm text-slate-500">Jumlah maksimum berdasarkan baki semasa</p><p id="balance" class="mt-1 text-2xl font-bold text-teal-700">RM 0.00</p></div>
            <div><label class="block text-sm font-medium">Amaun bayaran</label><input name="amount" id="amount" type="number" step="0.01" min="0.01" class="mt-1 w-full rounded-lg border-slate-300" required></div>
            <input type="hidden" name="method" value="DIRECT_FPX_DUITNOW">
            <p class="rounded-lg border border-blue-200 bg-blue-50 p-3 text-sm text-blue-800">Gerbang Direct FPX / DuitNow akan dibuka selepas pengesahan. Mod semasa ialah UAT sehingga pihak bank memberikan Merchant ID dan kunci API rasmi.</p>
            <button class="w-full rounded-lg bg-teal-700 px-5 py-3 font-semibold text-white hover:bg-teal-800">Teruskan ke bayaran</button>
        </form>
    </div></div></div>
    <script>const f=document.getElementById('family_id'),s=document.getElementById('school_id'),b=document.getElementById('balance'),a=document.getElementById('amount');f?.addEventListener('change',()=>{const o=f.options[f.selectedIndex];const v=Number(o?.dataset.balance||0);s.value=o?.dataset.school||'';b.textContent='RM '+v.toFixed(2);a.max=v;a.value=v>0?v.toFixed(2):'';});</script>
</x-app-layout>
