<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Laporan Kewangan</h2></x-slot>
    <div class="py-8"><div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="rounded-lg bg-white p-5 shadow-sm"><div class="text-sm text-slate-500">{{ $roleLabel }} · Skop laporan</div><div class="font-semibold text-teal-800">{{ $scopeLabel }}</div></div>
        <form class="flex flex-wrap items-end gap-3 rounded-lg bg-white p-5 shadow-sm">
            <label class="text-sm">Dari<input type="date" name="from" value="{{ $from->format('Y-m-d') }}" class="mt-1 block rounded border-slate-300"></label>
            <label class="text-sm">Hingga<input type="date" name="to" value="{{ $to->format('Y-m-d') }}" class="mt-1 block rounded border-slate-300"></label>
            <button class="rounded bg-indigo-600 px-4 py-2 text-white">Tapis</button>
            <a href="{{ route('reports.export', request()->only('from','to')) }}" class="rounded bg-teal-700 px-4 py-2 text-white">Eksport CSV</a>
        </form>
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
            @foreach ([['Bayaran berjaya','RM '.number_format($totalPayments,2)],['Bilangan bayaran',$paymentCount],['Baki tertunggak','RM '.number_format($outstanding,2)],['Dibatalkan','RM '.number_format($cancelledAmount,2)],['Bil. pembatalan',$cancelledCount]] as [$label,$value])
                <div class="rounded-lg bg-white p-5 shadow-sm"><div class="text-sm text-slate-500">{{ $label }}</div><div class="mt-2 text-2xl font-bold text-slate-900">{{ $value }}</div></div>
            @endforeach
        </div>
        <div class="rounded-lg bg-white p-5 shadow-sm"><h3 class="font-semibold text-lg">Pecahan bayaran mengikut sekolah</h3>
            <div class="mt-4 overflow-x-auto"><table class="min-w-full text-left text-sm"><thead><tr class="border-b text-slate-500"><th class="py-3">Sekolah</th><th class="py-3">Daerah</th><th class="py-3">Bilangan</th><th class="py-3">Jumlah</th></tr></thead><tbody>
                @forelse ($schools as $school)<tr class="border-b last:border-0"><td class="py-3">{{ $school->school?->name }}</td><td class="py-3">{{ $school->school?->district ?? '-' }}</td><td class="py-3">{{ $school->payment_count }}</td><td class="py-3">RM {{ number_format($school->payment_total,2) }}</td></tr>@empty
                    <tr><td colspan="4" class="py-6 text-center text-slate-500">Tiada bayaran berjaya dalam tempoh ini.</td></tr>
                @endforelse
            </tbody></table></div>
        </div>
    </div></div>
</x-app-layout>
