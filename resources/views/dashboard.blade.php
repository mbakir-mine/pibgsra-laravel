<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-slate-900">Dashboard</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="rounded-lg bg-gradient-to-r from-slate-900 to-teal-800 p-6 text-white shadow-sm">
                <h3 class="text-2xl font-semibold sm:text-3xl">Selamat datang, {{ auth()->user()->name }}</h3>
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-4">
                <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="text-sm text-slate-500">Jumlah sekolah</div>
                    <div class="mt-2 text-3xl font-semibold text-slate-900">{{ $schools }}</div>
                </div>
                <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="text-sm text-slate-500">Jumlah keluarga</div>
                    <div class="mt-2 text-3xl font-semibold text-slate-900">{{ $families }}</div>
                </div>
                <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="text-sm text-slate-500">Pelajar aktif</div>
                    <div class="mt-2 text-3xl font-semibold text-slate-900">{{ $students }}</div>
                </div>
                <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="text-sm text-slate-500">Bayaran berjaya</div>
                    <div class="mt-2 text-3xl font-semibold text-slate-900">RM {{ number_format($payments, 2) }}</div>
                </div>
            </div>

            <div class="mt-6 grid gap-6 lg:grid-cols-3">
                <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm lg:col-span-2">
                    <h3 class="text-lg font-semibold text-slate-900">Tindakan pantas</h3>
                    <div class="mt-4 grid gap-3 sm:grid-cols-2">
                        <a href="{{ route('schools.index') }}" class="rounded-md border border-slate-200 p-4 font-semibold text-slate-800 hover:border-teal-300 hover:bg-teal-50">Urus sekolah</a>
                        <a href="{{ route('families.index') }}" class="rounded-md border border-slate-200 p-4 font-semibold text-slate-800 hover:border-teal-300 hover:bg-teal-50">Urus keluarga</a>
                        <a href="{{ route('students.index') }}" class="rounded-md border border-slate-200 p-4 font-semibold text-slate-800 hover:border-teal-300 hover:bg-teal-50">Urus pelajar</a>
                        <a href="{{ route('payments.create') }}" class="rounded-md border border-slate-200 p-4 font-semibold text-slate-800 hover:border-teal-300 hover:bg-teal-50">Checkout bayaran</a>
                    </div>
                </div>

                <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-900">Aliran kerja</h3>
                    <div class="mt-4 space-y-3 text-sm text-slate-600">
                        <p>1. Pilih sekolah dan keluarga.</p>
                        <p>2. Tetapkan kategori dan amaun yuran.</p>
                        <p>3. Rekod bayaran atau guna checkout UAT Direct FPX / DuitNow.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
