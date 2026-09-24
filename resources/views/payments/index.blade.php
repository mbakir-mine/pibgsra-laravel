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
                                @if (auth()->user()->hasAnyPibgsraRole(['OWNER', 'SCHOOL_ADMIN', 'HEADMASTER']))
                                    <th class="py-3 pr-4">Tindakan</th>
                                @endif
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
                                    <td class="py-3 pr-4 text-slate-600">
                                        {{ $payment->receipt?->receipt_number ?: $payment->gateway_reference }}
                                        @if ($payment->receipt?->status === 'CANCELLED')
                                            <span class="ml-2 rounded-full bg-red-50 px-2 py-1 text-xs font-semibold text-red-700">DIBATALKAN</span>
                                        @endif
                                    </td>
                                    @if (auth()->user()->hasAnyPibgsraRole(['OWNER', 'SCHOOL_ADMIN', 'HEADMASTER']))
                                        <td class="py-3 pr-4">
                                            @if ($payment->status === 'SUCCESS' && in_array($payment->receipt?->status, [null, 'ISSUED']) && auth()->user()->hasAnyPibgsraRole(['OWNER']))
                                                <form method="POST" action="{{ route('payments.cancel-receipt', $payment) }}" onsubmit="return confirm('Batalkan resit ini? Tindakan ini akan dipaparkan dalam audit.');">
                                                    @csrf
                                                    <input name="reason" required maxlength="500" placeholder="Sebab pembatalan" class="mb-1 w-40 rounded border border-slate-300 px-2 py-1 text-xs">
                                                    <button class="rounded bg-red-600 px-2 py-1 text-xs font-semibold text-white hover:bg-red-700">Batal resit</button>
                                                </form>
                                            @elseif ($payment->status === 'SUCCESS' && in_array($payment->receipt?->status, [null, 'ISSUED']) && auth()->user()->hasAnyPibgsraRole(['SCHOOL_ADMIN']))
                                                <form method="POST" action="{{ route('payments.request-cancellation', $payment) }}" onsubmit="return confirm('Hantar permohonan pembatalan kepada Guru Besar?');">
                                                    @csrf
                                                    <input name="reason" required maxlength="500" placeholder="Sebab permohonan" class="mb-1 w-40 rounded border border-slate-300 px-2 py-1 text-xs">
                                                    <button class="rounded bg-amber-600 px-2 py-1 text-xs font-semibold text-white hover:bg-amber-700">Mohon batal</button>
                                                </form>
                                            @elseif ($payment->receipt?->status === 'CANCELLATION_REQUESTED' && auth()->user()->hasAnyPibgsraRole(['HEADMASTER']))
                                                <form method="POST" action="{{ route('payments.approve-cancellation', $payment) }}" onsubmit="return confirm('Luluskan pembatalan resit ini?');">
                                                    @csrf
                                                    <input name="reason" required maxlength="500" placeholder="Sahkan sebab" class="mb-1 w-40 rounded border border-slate-300 px-2 py-1 text-xs">
                                                    <button class="rounded bg-red-600 px-2 py-1 text-xs font-semibold text-white hover:bg-red-700">Lulus & batal</button>
                                                </form>
                                            @elseif ($payment->receipt?->status === 'CANCELLATION_REQUESTED')
                                                <span class="text-xs text-amber-700">Menunggu kelulusan Guru Besar</span>
                                            @else
                                                <span class="text-xs text-slate-400">-</span>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-8 text-center text-slate-500">Tiada bayaran direkodkan.</td>
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
