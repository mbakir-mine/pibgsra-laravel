<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-slate-900">Tetapan Yuran</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="rounded-lg bg-gradient-to-r from-slate-900 to-teal-800 p-6 text-white shadow-sm">
                <h3 class="text-2xl font-semibold">Tetapan Yuran Sekolah</h3>
                <p class="mt-2 max-w-2xl text-sm text-teal-50">Pilih daerah, kemudian klik sekolah untuk urus item yuran SRA dan SRAI.</p>
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-3">
                <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="text-sm text-slate-500">Jumlah sekolah</div>
                    <div class="mt-2 text-3xl font-semibold text-slate-900">{{ $schools->count() }}</div>
                </div>
                <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="text-sm text-slate-500">SRA</div>
                    <div class="mt-2 text-3xl font-semibold text-slate-900">{{ $sraCount }}</div>
                </div>
                <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="text-sm text-slate-500">SRAI</div>
                    <div class="mt-2 text-3xl font-semibold text-slate-900">{{ $sraiCount }}</div>
                </div>
            </div>

            <div class="mt-6 rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-900">Daerah</h3>
                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach($districts as $district => $districtSchools)
                        <a href="#district-{{ \Illuminate\Support\Str::slug($district) }}" class="rounded-full bg-teal-50 px-4 py-2 text-sm font-semibold text-teal-800 hover:bg-teal-100">
                            {{ $district }} ({{ $districtSchools->count() }})
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="mt-6 space-y-4">
                @foreach($districts as $district => $districtSchools)
                    <section id="district-{{ \Illuminate\Support\Str::slug($district) }}" class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-slate-900">{{ $district }}</h3>
                        <p class="text-sm text-slate-500">{{ $districtSchools->count() }} sekolah</p>

                        <div class="mt-4 divide-y divide-slate-100">
                            @foreach($districtSchools as $school)
                                <a href="{{ route('fees.create', ['school_id' => $school->id]) }}" class="flex flex-col gap-1 px-2 py-3 hover:bg-slate-50 sm:flex-row sm:items-center sm:justify-between">
                                    <span class="font-semibold text-slate-900">{{ $school->name }}</span>
                                    <span class="text-sm text-slate-500">{{ $school->code }} · {{ $school->display_category }}</span>
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
