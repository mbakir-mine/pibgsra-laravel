<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-slate-900">Bayaran & Resit</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 rounded-md border border-teal-200 bg-teal-50 px-4 py-3 text-sm text-teal-800">
                    {{ session('status') }}
                </div>
            @endif

            <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Transaksi bayaran</h3>
                        <p class="text-sm text-slate-500">Semak status bayaran, nombor rujukan dan resit.</p>
                    </div>
                    <a href="{{ route('payments.create') }}" class="inline-flex items-center justify-center rounded-md bg-teal-700 px-4 py-2 text-sm font-semibold text-white hover:bg-teal-800">
                        Rekod / Checkout Bayaran
                    </a>
                </div>

                <div class="mt-6 overflow-x-auto">
                    <table class="w-full min-w-[760px] text-left text-sm">
                        <thead class="border-b border-slate-200 text-xs uppercase tracking-wide text-slate-500">
                            <tr>
                                <th class="py-3 pr-4">Sekolah</th>
                                <th class="py-3 pr-4">Keluarga</th>
                                <th class="py-3 pr-4">Amaun</th>
                                <th class="py-3 pr-4">Kaedah</th>
                                <th class="py-3 pr-4">Status</th>
                                <th class="py-3 pr-4">Resit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $payment)
                                <tr class="border-b border-slate-100">
                                    <td class="py-3 pr-4 text-slate-700">{{ $payment->school?->name }}</td>
                                    <td class="py-3 pr-4 font-medium text-slate-900">{{ $payment->family?->name }}</td>
                                    <td class="py-3 pr-4 text-slate-900">RM {{ number_format($payment->amount, 2) }}</td>
                                    <td class="py-3 pr-4 text-slate-600">{{ $payment->method ?: '-' }}</td>
                                    <td class="py-3 pr-4">
                                        <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $payment->status === 'SUCCESS' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                            {{ $payment->status }}
                                        </span>
                                    </td>
                                    <td class="py-3 pr-4 text-slate-600">{{ $payment->receipt?->receipt_number ?: $payment->gateway_reference }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-slate-500">Tiada bayaran direkodkan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">{{ $payments->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
