<div>
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Manajemen Jabatan</h2>
            <p class="text-sm text-gray-600">Kelola posisi dan standar gaji pokok perusahaan.</p>
        </div>
        <button wire:click="create()"
            class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md shadow-sm transition">
            + Tambah Jabatan
        </button>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 bg-green-100 border-green-400 text-green-700 rounded-md">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('eror'))
        <div class="mb-4 bg-red-100 border-red-400 text-red-700 rounded-md">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <div class="w-full max-w-sm relative">
                <input type="text" wire:model.live="search" placeholder="Cari nama jabatan atau departemen..."
                    class="w-full pl-4 pr-10 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                <span class="absolute right-3 top-2 text-gray-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
            </div>
        </div>



        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 uppercase text-xs font-semibold">
                        <th class="px-6 py-3 border-b border-gray-200">No</th>
                        <th class="px-6 py-3 border-b border-gray-200">Departemen</th>
                        <th class="px-6 py-3 border-b border-gray-200">Nama Jabatan</th>
                        <th class="px-6 py-3 border-b border-gray-200">Gaji Pokok</th>
                        <th class="px-6 py-3 border-b border-gray-200 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($jabatans as $index => $jabatan)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-500">{{$jabatans->firstItem() + $index}}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <span class="bg-gray-200 text-gray-700 py-1 px-2 rounded-md text-xs font-bold">{{$jabatan->departemen->kode ?? '-'}}</span>
                                {{ $jabatan->Departemen->nama ?? ' Departemen Terhapus'}} 
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-800">{{$jabatan->nama}}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">Rp {{number_format($jabatan->gaji_pokok,  0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-sm text-center font-medium">
                                <button wire:click="edit({{ $jabatan->id }})"
                                    class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                                <button wire:click="delete({{ $jabatan->id }})" class="text-red-600 hover:text-red-900">Hapus</button>
                            </td>
                        </tr>

                    @empty
                     <tr>
                            <td colspan="4" class="px-6 py-4 text-sm text-gray-500">
                                Tidak ada data departemen
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-200">
            {{ $jabatans->links() }}
        </div>

    </div>

    @if ($isOpen)
        <div  
            class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-0">
        <div class="fixed inset-0 bg-gray-800/60 backdrop-blur-sm transition-opacity" @click="isOpen = false"></div>
        <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden z-10">
            <form wire:submit.prevent="store()">
                <div class="px-6 py-5">
                    <h3 class="text-xl font-bold text-gray-900 mb-5 border-b pb-2">
                        {{ $jabatan_id ? 'Edit JAbatan' : 'Tambah Jabatan' }}
                    </h3>
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Departemen</label>
                        <select
                            wire:model="departemen_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="">-- Pilih Departemen --</option>
                            <option value="1">
                                @foreach ($departemens as $dept )
                                    <option value="{{ $dept->id }}">{{$dept->kode}} - {{ $dept->nama}}</option>
                                @endforeach</option>
                            
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Jabatan</label>
                        <input type="text" wire:model="nama"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Contoh: Manager HRD, Staff IT">
                    </div>
                    <div class="mb-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Standar Gaji Pokok (Rp)</label>
                        <input type="number"
                        ``  wire:model="gaji_pokok"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Contoh: 5000000">
                        <span class="text-xs text-gray-500 mt-1 block">Tulis angka saja tanpa titik/koma.</span>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                    <button type="button" wire:click="closeModal()"
                        class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 font-medium shadow-sm transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 border border-transparent rounded-lg text-white hover:bg-blue-700 font-medium shadow-sm transition">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
