<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-slate-900">Direct FPX / DuitNow UAT</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                <div class="text-sm font-semibold uppercase tracking-wide text-teal-700">Bank Direct UAT</div>
                <h3 class="mt-2 text-xl font-semibold text-slate-900">Sahkan bayaran PIBG</h3>

                <dl class="mt-6 divide-y divide-slate-100 text-sm">
                    <div class="flex justify-between py-3">
                        <dt class="text-slate-500">Sekolah</dt>
                        <dd class="font-medium text-slate-900">{{ $payment->school?->name }}</dd>
                    </div>
                    <div class="flex justify-between py-3">
                        <dt class="text-slate-500">Keluarga</dt>
                        <dd class="font-medium text-slate-900">{{ $payment->family?->name }}</dd>
                    </div>
                    <div class="flex justify-between py-3">
                        <dt class="text-slate-500">Rujukan</dt>
                        <dd class="font-medium text-slate-900">{{ $payment->gateway_reference }}</dd>
                    </div>
                    <div class="flex justify-between py-3">
                        <dt class="text-slate-500">Amaun</dt>
                        <dd class="text-lg font-semibold text-slate-900">RM {{ number_format($payment->amount, 2) }}</dd>
                    </div>
                </dl>

                <form method="POST" action="{{ route('payments.uat-success', $payment) }}" class="mt-6">
                    @csrf
                    <button class="w-full rounded-md bg-teal-700 px-4 py-3 text-sm font-semibold text-white hover:bg-teal-800">
                        Simulasi Bayaran Berjaya
                    </button>
                </form>

                <p class="mt-4 text-xs text-slate-500">
                    Skrin ini menggantikan halaman bank semasa UAT. Integrasi sebenar perlu menggunakan credential rasmi bank/acquirer sekolah.
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
