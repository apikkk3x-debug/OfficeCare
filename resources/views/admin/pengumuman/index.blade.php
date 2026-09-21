@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header Halaman -->
    <div class="bg-gradient-to-r from-slate-900 to-slate-800 border border-indigo-700/50 rounded-2xl p-6 shadow-md text-white flex items-center justify-between">
        <div>
            <span class="px-2.5 py-0.5 bg-indigo-500/30 border border-indigo-400/30 text-indigo-200 text-[10px] font-semibold tracking-wider uppercase rounded-md">
                ADMIN PANEL
            </span>
            <h1 class="text-xl sm:text-2xl font-bold text-white tracking-wide mt-1">
                Kelola Pengumuman Kantor
            </h1>
            <p class="text-xs text-indigo-100/80 mt-0.5">
                Buat dan kelola informasi siaran resmi yang akan tampil di dashboard karyawan.
            </p>
        </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Tambah Pengumuman -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
            <h2 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Buat Pengumuman Baru
            </h2>

            <form action="{{ route('admin.pengumuman.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Judul Pengumuman</label>
                    <input type="text" name="judul" required placeholder="Contoh: Pemeliharaan Lift Utama" 
                           class="w-full px-3.5 py-2 text-xs border border-slate-200 rounded-xl focus:outline-none focus:border-indigo-500 bg-slate-50/50">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Target Role Penerima</label>
                    <select name="target_role" class="w-full px-3.5 py-2 text-xs border border-slate-200 rounded-xl focus:outline-none focus:border-indigo-500 bg-slate-50/50">
                        <option value="karyawan">Karyawan</option>
                        <option value="semua">Semua Role</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Isi Pesan</label>
                    <textarea name="pesan" rows="4" required placeholder="Tulis detail informasi pengumuman di sini..." 
                              class="w-full px-3.5 py-2 text-xs border border-slate-200 rounded-xl focus:outline-none focus:border-indigo-500 bg-slate-50/50"></textarea>
                </div>

                <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl shadow-md transition cursor-pointer">
                    Publikasikan Pengumuman
                </button>
            </form>
        </div>

        <!-- Tabel Daftar Pengumuman -->
        <div class="lg:col-span-2 bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
            <h2 class="text-sm font-bold text-slate-800 mb-4">Daftar Pengumuman Aktif</h2>

            <div class="overflow-x-visible"> <!-- Diubah agar dropdown tidak terpotong tabel -->
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase">
                            <th class="py-3 px-3">Judul & Pesan</th>
                            <th class="py-3 px-3">Target</th>
                            <th class="py-3 px-3">Status</th>
                            <th class="py-3 px-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                        @forelse($pengumumans as $p)
                            <tr>
                                <td class="py-3 px-3 max-w-xs">
                                    <div class="font-bold text-slate-900">{{ $p->judul }}</div>
                                    <div class="text-slate-500 truncate mt-0.5">{{ $p->pesan }}</div>
                                </td>
                                <td class="py-3 px-3">
                                    <span class="px-2 py-0.5 bg-slate-100 text-slate-700 rounded-md text-[10px] font-bold uppercase">
                                        {{ $p->target_role }}
                                    </span>
                                </td>
                                <td class="py-3 px-3">
                                    @if($p->is_active)
                                        <span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 border border-emerald-200 rounded-md text-[10px] font-bold">Aktif</span>
                                    @else
                                        <span class="px-2 py-0.5 bg-rose-50 text-rose-600 border border-rose-200 rounded-md text-[10px] font-bold">Non-Aktif</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 whitespace-nowrap text-center align-middle relative">
                                    <!-- Tombol Pemicu Titik Tiga (Vanilla JS Toggle) -->
                                    <button onclick="toggleDropdown('dropdown-{{ $p->id }}')" type="button" class="p-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition cursor-pointer inline-flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                        </svg>
                                    </button>

                                    <!-- Menu Dropdown Pop-up (Vanilla JS) -->
                                    <div id="dropdown-{{ $p->id }}" class="dropdown-menu hidden absolute right-8 mt-1 w-36 bg-white border border-slate-200 rounded-xl shadow-xl z-50 py-1 text-left">
                                        
                                        <!-- Tombol Detail -->
                                        <button onclick="openModal('modal-{{ $p->id }}'); closeAllDropdowns();" type="button" class="w-full text-left px-3.5 py-2 hover:bg-slate-50 text-slate-700 font-medium flex items-center gap-2 cursor-pointer">
                                            <svg class="w-3.5 h-3.5 text-indigo-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Detail
                                        </button>

                                        <!-- Tombol Toggle Status -->
                                        <form action="{{ route('admin.pengumuman.toggle', $p->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="w-full text-left px-3.5 py-2 hover:bg-slate-50 text-slate-700 font-medium flex items-center gap-2 cursor-pointer">
                                                <svg class="w-3.5 h-3.5 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                {{ $p->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>

                                        <!-- Tombol Hapus -->
                                        <form action="{{ route('admin.pengumuman.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengumuman ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full text-left px-3.5 py-2 hover:bg-rose-50 text-rose-600 font-semibold flex items-center gap-2 cursor-pointer">
                                                <svg class="w-3.5 h-3.5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Modal Detail Pengumuman (Vanilla JS Modal) -->
                                    <div id="modal-{{ $p->id }}" class="custom-modal hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4">
                                        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6 space-y-4 text-left border border-slate-200">
                                            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                                                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Detail Pengumuman Kantor
                                                </h3>
                                                <button onclick="closeModal('modal-{{ $p->id }}')" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100 transition cursor-pointer">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                            </div>

                                            <div class="space-y-3 text-xs">
                                                <div>
                                                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Judul Pengumuman</span>
                                                    <p class="font-bold text-slate-900 mt-0.5 text-sm">{{ $p->judul }}</p>
                                                </div>

                                                <div class="grid grid-cols-2 gap-3">
                                                    <div>
                                                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Target Role</span>
                                                        <span class="inline-block mt-1 px-2 py-0.5 bg-slate-100 text-slate-700 rounded-md text-[10px] font-bold uppercase">
                                                            {{ $p->target_role }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Status Publikasi</span>
                                                        <span class="inline-block mt-1 px-2 py-0.5 {{ $p->is_active ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : 'bg-rose-50 text-rose-600 border border-rose-200' }} rounded-md text-[10px] font-bold">
                                                            {{ $p->is_active ? 'Aktif' : 'Non-Aktif' }}
                                                        </span>
                                                    </div>
                                                </div>

                                                <div>
                                                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Isi Pesan Lengkap</span>
                                                    <div class="mt-1 p-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 whitespace-pre-line leading-relaxed max-h-48 overflow-y-auto">
                                                        {{ $p->pesan }}
                                                    </div>
                                                </div>

                                                <div>
                                                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Waktu Dibuat</span>
                                                    <p class="text-slate-600 mt-0.5">{{ $p->created_at->format('d M Y, H:i') }} ({{ $p->created_at->diffForHumans() }})</p>
                                                </div>
                                            </div>

                                            <div class="pt-2 border-t border-slate-100 flex justify-end">
                                                <button onclick="closeModal('modal-{{ $p->id }}')" class="px-4 py-2 bg-slate-700 hover:bg-slate-900 text-slate-200 font-semibold rounded-xl text-xs transition cursor-pointer">
                                                    Tutup
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-slate-400">Belum ada pengumuman yang dibuat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $pengumumans->links() }}
            </div>
        </div>
    </div>
</div>

<!-- SCRIPT PENDUKUNG DROPDOWN & MODAL -->
<script>
function toggleDropdown(id) {
    const dropdown = document.getElementById(id);
    const isHidden = dropdown.classList.contains('hidden');
    closeAllDropdowns();
    if (isHidden) {
        dropdown.classList.remove('hidden');
    }
}

function closeAllDropdowns() {
    document.querySelectorAll('.dropdown-menu').forEach(menu => {
        menu.classList.add('hidden');
    });
}

function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

// Tutup dropdown jika klik di luar area
window.addEventListener('click', function(e) {
    if (!e.target.closest('button')) {
        closeAllDropdowns();
    }
});
</script>
@endsection