<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-slate-900">Sekolah</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Senarai sekolah</h3>
                        <p class="text-sm text-slate-500">Data sekolah mengikut skop akses pengguna.</p>
                    </div>
                    <a href="{{ route('schools.create') }}" class="inline-flex rounded-md bg-teal-700 px-4 py-2 text-sm font-semibold text-white hover:bg-teal-800">Tambah sekolah</a>
                </div>

                <div class="mt-6 overflow-x-auto">
                    <table class="w-full min-w-[760px] text-left text-sm">
                        <thead class="border-b border-slate-200 text-xs uppercase tracking-wide text-slate-500">
                            <tr>
                                <th class="py-3 pr-4">Kod</th>
                                <th class="py-3 pr-4">Nama</th>
                                <th class="py-3 pr-4">Daerah</th>
                                <th class="py-3 pr-4">Kategori</th>
                                <th class="py-3 pr-4">Keluarga</th>
                                <th class="py-3 pr-4">Murid</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($schools as $school)
                                <tr class="border-b border-slate-100">
                                    <td class="py-3 pr-4 font-medium text-slate-900">{{ $school->code }}</td>
                                    <td class="py-3 pr-4 text-slate-800">{{ $school->name }}</td>
                                    <td class="py-3 pr-4 text-slate-600">{{ $school->district ?: '-' }}</td>
                                    <td class="py-3 pr-4 text-slate-600">{{ $school->category ?: '-' }}</td>
                                    <td class="py-3 pr-4 text-slate-600">{{ $school->families_count }}</td>
                                    <td class="py-3 pr-4 text-slate-600">{{ $school->students_count }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="py-8 text-center text-slate-500">Tiada data sekolah.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">{{ $schools->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
