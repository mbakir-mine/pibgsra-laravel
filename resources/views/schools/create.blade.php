<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-slate-900">Tambah Sekolah</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                <form method="POST" action="{{ route('schools.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Nama sekolah</label>
                        <input name="name" class="mt-1 w-full rounded-md border-slate-300 shadow-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Kod sekolah</label>
                        <input name="code" class="mt-1 w-full rounded-md border-slate-300 shadow-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Daerah</label>
                        <input name="district" class="mt-1 w-full rounded-md border-slate-300 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Kategori</label>
                        <select name="category" class="mt-1 w-full rounded-md border-slate-300 shadow-sm">
                            <option value="">Pilih kategori</option>
                            <option value="SRA">SRA</option>
                            <option value="SRAI">SRAI</option>
                        </select>
                    </div>
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('schools.index') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">Batal</a>
                        <button class="rounded-md bg-teal-700 px-4 py-2 text-sm font-semibold text-white hover:bg-teal-800">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
