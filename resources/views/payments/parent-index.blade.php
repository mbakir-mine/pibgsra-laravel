<x-app-layout>
    <x-slot name="header"><h2 class="text-2xl font-semibold text-slate-900">Bayaran Yuran</h2></x-slot>
    <div class="py-8"><div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
        <div class="rounded-2xl bg-gradient-to-r from-slate-900 to-teal-800 p-6 text-white shadow-sm">
            <p class="text-sm font-semibold uppercase tracking-wide text-teal-200">{{ auth()->user()->platformScopeLabel() }}</p>
            <h1 class="mt-2 text-3xl font-bold">Bayaran yuran anak</h1>
            <p class="mt-2 text-teal-50">Semak baki keluarga dan teruskan bayaran melalui gerbang pembayaran.</p>
        </div>
        <div class="mt-6 flex justify-end"><a href="{{ route('payments.create') }}" class="rounded-lg bg-teal-700 px-5 py-3 font-semibold text-white hover:bg-teal-800">Buat bayaran</a></div>
        @forelse($families as $family)
            @php($balance = $family->charges->sum('balance_amount'))
            <section class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex flex-wrap items-start justify-between gap-4"><div><p class="text-sm text-slate-500">{{ $family->school?->name }}</p><h2 class="text-xl font-bold text-slate-900">{{ $family->name }}</h2><p class="mt-1 text-sm text-slate-500">{{ $family->students->pluck('full_name')->join(', ') ?: 'Tiada murid dipautkan' }}</p></div><div class="text-right"><p class="text-sm text-slate-500">Baki perlu dibayar</p><p class="text-2xl font-bold text-teal-700">RM {{ number_format($balance, 2) }}</p></div></div>
                <div class="mt-5 divide-y divide-slate-100">@forelse($family->charges as $charge)<div class="flex justify-between py-3 text-sm"><span>{{ $charge->feeCategory?->name ?: 'Yuran sekolah' }}</span><span class="font-semibold">RM {{ number_format($charge->balance_amount, 2) }}</span></div>@empty<p class="py-3 text-sm text-slate-500">Belum ada caj yuran untuk keluarga ini.</p>@endforelse</div>
            </section>
        @empty
            <div class="mt-6 rounded-2xl border border-amber-200 bg-amber-50 p-6 text-amber-800">Akaun ibu bapa belum dipautkan kepada keluarga. Sila hubungi pihak sekolah.</div>
        @endforelse
    </div></div>
</x-app-layout>
