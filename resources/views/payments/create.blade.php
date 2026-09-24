<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-slate-900">Checkout Bayaran</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                <form method="POST" action="{{ route('payments.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Keluarga</label>
                        <select name="family_id" id="family_id" class="mt-1 w-full rounded-md border-slate-300 shadow-sm" required>
                            @foreach($families as $family)
                                <option value="{{ $family->id }}" data-school="{{ $family->school_id }}">
                                    {{ $family->family_code }} - {{ $family->name }} / {{ $family->school?->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <input name="school_id" id="school_id" type="hidden" value="{{ $families->first()?->school_id }}">

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Amaun bayaran</label>
                        <input name="amount" type="number" step="0.01" min="0.01" class="mt-1 w-full rounded-md border-slate-300 shadow-sm" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Kaedah bayaran</label>
                        <select name="method" class="mt-1 w-full rounded-md border-slate-300 shadow-sm" required>
                            <option value="DIRECT_FPX_DUITNOW">Direct FPX / DuitNow UAT</option>
                            <option value="MANUAL">Manual / Kaunter</option>
                        </select>
                    </div>

                    <div class="rounded-md border border-teal-100 bg-teal-50 px-4 py-3 text-sm text-teal-900">
                        Mod Direct FPX / DuitNow ini ialah UAT. Duit sebenar hanya boleh bergerak selepas sekolah mendapat Merchant ID, kunci API, sijil dan endpoint rasmi daripada bank/acquirer.
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('payments.index') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">Batal</a>
                        <button class="rounded-md bg-teal-700 px-4 py-2 text-sm font-semibold text-white hover:bg-teal-800">Teruskan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const family = document.getElementById('family_id');
        const school = document.getElementById('school_id');

        family?.addEventListener('change', () => {
            school.value = family.options[family.selectedIndex]?.dataset.school || '';
        });
    </script>
</x-app-layout>
