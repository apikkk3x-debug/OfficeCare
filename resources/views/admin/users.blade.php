@extends('layouts.app')

@section('content')
<div class="space-y-6">
    
    <!-- Banner Header Admin -->
    <div class="bg-gradient-to-r from-slate-900 to-slate-800 border border-slate-400/50 rounded-2xl p-5 shadow-md text-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <span class="inline-block px-2.5 py-0.5 bg-indigo-500/30 border border-indigo-400/30 text-indigo-200 text-[10px] font-semibold tracking-wider uppercase rounded-md mb-1.5">
                Panel Admin • Hak Akses & Akun
            </span>
            <h2 class="text-xl font-bold text-white tracking-wide">Manajemen Pengguna & Verifikasi</h2>
            <p class="text-xs text-indigo-100/80 mt-1">Kelola seluruh data akun terdaftar, tinjau verifikasi pendaftaran karyawan baru, dan atur hak akses sistem.</p>
        </div>
        
        <button onclick="toggleUserModal(true)" class="inline-flex items-center justify-center gap-2 bg-indigo-800 hover:bg-indigo-900 text-white font-semibold px-4 py-2.5 rounded-xl text-xs transition shadow-md shadow-indigo-600/20 cursor-pointer shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
            </svg>
            <span>+ Tambah Pengguna Baru</span>
        </button>
    </div>

    <!-- Tabel Kelola Pengguna Modern (Card Pembungkus Utama) -->
    <div class="bg-slate-100/80 p-6 rounded-2xl shadow-sm border border-slate-200/90 space-y-4">
        
        <!-- Header Tabel & Dropdown Batas Tampil Data -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 pb-3 border-b border-slate-200/60">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Daftar Akun Pengguna Kantor & Status Verifikasi</h3>
            
            <form method="GET" action="{{ route('admin.users') }}" class="flex items-center gap-2 text-xs text-slate-600">
                <span>Tampilkan</span>
                <select name="per_page" onchange="this.form.submit()" 
                        class="bg-white border border-slate-300 rounded-xl px-2.5 py-1.5 text-xs focus:outline-none focus:border-indigo-600 cursor-pointer shadow-sm">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                </select>
                <span>data</span>
            </form>
        </div>

        <!-- Wrapper Tabel -->
        <div class="overflow-x-auto rounded-2xl border border-slate-200/80 bg-white">
            <table class="w-full text-left text-xs text-slate-600 border-collapse">
                <thead class="text-slate-100 bg-gradient-to-r from-indigo-900 via-indigo-800 to-slate-800 border-b border-indigo-700/50">
                    <tr>
                        <th class="p-3.5 font-bold">No</th>
                        <th class="p-3.5 font-bold">Pengguna</th>
                        <th class="p-3.5 font-bold">Email</th>
                        <th class="p-3.5 font-bold">Role / Hak Akses</th>
                        <th class="p-3.5 font-bold">Status Akun</th>
                        <th class="p-3.5 font-bold">Tanggal Bergabung</th>
                        <th class="p-3.5 font-bold text-center">Aksi Manajemen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200/80 bg-white">
                    @forelse($users ?? [] as $index => $user)
                        @php
                            $role = strtolower($user->role ?? 'karyawan');
                            $roleBadge = [
                                'admin'    => 'bg-purple-50 text-purple-700 border-purple-200',
                                'pimpinan' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'karyawan' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                            ][$role] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-3.5 font-semibold text-slate-500">
                                {{ method_exists($users, 'firstItem') ? $users->firstItem() + $index : $index + 1 }}
                            </td>
                            
                            <!-- Profil, Nama, NIK & Divisi -->
                            <td class="p-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-slate-800 text-white font-bold text-xs flex items-center justify-center shrink-0 uppercase border border-slate-300">
                                        @if(!empty($user->foto))
                                            <img src="{{ asset('storage/' . $user->foto) }}" class="w-full h-full rounded-full object-cover">
                                        @else
                                            {{ strtoupper(substr($user->nama ?? $user->name ?? 'U', 0, 1)) }}
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-800">{{ $user->nama ?? $user->name }}</div>
                                        <div class="text-[10px] text-slate-400 font-mono">NIK: {{ $user->nik ?? '-' }} • Divisi: {{ $user->divisi ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="p-3.5 font-medium text-slate-600">{{ $user->email }}</td>
                            
                            <!-- Badge Role -->
                            <td class="p-3.5">
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase border {{ $roleBadge }}">
                                    {{ ucfirst($role) }}
                                </span>
                            </td>

                            <!-- Status Verifikasi -->
                            <td class="p-3.5">
                                @if($user->status == 'pending')
                                    <span class="px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200 rounded-full text-[10px] font-bold uppercase">Pending</span>
                                @elseif($user->status == 'disetujui')
                                    <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-full text-[10px] font-bold uppercase">Disetujui</span>
                                @else
                                    <span class="px-2.5 py-1 bg-rose-50 text-rose-700 border border-rose-200 rounded-full text-[10px] font-bold uppercase">Ditolak</span>
                                @endif
                            </td>

                            <td class="p-3.5 text-slate-400 text-[11px]">
                                {{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') : '-' }}
                            </td>

                            <!-- Menu Aksi -->
                            <td class="p-3.5 text-center align-middle">
                                <div class="flex items-center justify-center gap-1.5">
                                    
                                    @if($user->status == 'pending')
                                        <form action="{{ route('admin.users.updateStatus', $user->id_user ?? $user->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="disetujui">
                                            <button type="submit" title="Setujui Akun" class="px-2.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-[10px] font-semibold transition cursor-pointer shadow-sm">
                                                Setujui
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.users.updateStatus', $user->id_user ?? $user->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="ditolak">
                                            <button type="submit" title="Tolak Akun" class="px-2.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-[10px] font-semibold transition cursor-pointer shadow-sm">
                                                Tolak
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Menu Titik Tiga untuk Edit & Hapus -->
                                    <div class="relative inline-flex items-center justify-center text-left" x-data="{ openMenu: false }">
                                        <button @click="openMenu = !openMenu" type="button" class="p-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition cursor-pointer inline-flex items-center justify-center">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                            </svg>
                                        </button>

                                        <div x-show="openMenu" 
                                             @click.away="openMenu = false"
                                             x-transition:enter="transition ease-out duration-100"
                                             x-transition:enter-start="transform opacity-0 scale-95"
                                             x-transition:enter-end="transform opacity-100 scale-100"
                                             x-transition:leave="transition ease-in duration-75"
                                             x-transition:leave-start="transform opacity-100 scale-100"
                                             x-transition:leave-end="transform opacity-0 scale-95"
                                             class="absolute right-full mr-2 top-1/2 -translate-y-1/2 w-36 bg-white border border-slate-200 rounded-xl shadow-xl z-50 py-1 text-left space-y-1"
                                             style="display: none;">
                                            
                                            <button type="button" onclick="openEditModal('{{ $user->id_user ?? $user->id }}', '{{ $user->nama }}', '{{ $user->email }}', '{{ $user->role }}')" class="w-full flex items-center gap-2 px-3 py-2 text-xs font-semibold text-indigo-600 hover:bg-indigo-50 transition cursor-pointer">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                <span>Edit Akun</span>
                                            </button>

                                            @if(Auth::id() !== ($user->id_user ?? $user->id))
                                                <form action="{{ route('admin.users.hapus', $user->id_user ?? $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $user->email }}?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition cursor-pointer">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                        <span>Hapus Akun</span>
                                                    </button>
                                                </form>
                                            @else
                                                <div class="px-3 py-2 text-[10px] text-slate-400 italic font-semibold text-center select-none">
                                                    Akun Anda
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-slate-400">Belum ada pengguna terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Navigasi Paginasi Laravel -->
        @if(method_exists($users, 'links'))
            <div class="pt-3 border-t border-slate-200/60 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
                <div>
                    Menampilkan {{ $users->firstItem() ?? 0 }} sampai {{ $users->lastItem() ?? 0 }} dari total {{ $users->total() }} data
                </div>
                <div class="[&_p]:hidden">
                    {{ $users->links() }}
                </div>
            </div>
        @endif

    </div>
</div>

<!-- Modal Tambah Pengguna Baru (NIK Digenerate Otomatis oleh Sistem) -->
<div id="userModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs hidden items-center justify-center z-50 p-4">
    <div class="bg-white w-full max-w-lg p-6 rounded-2xl shadow-xl border border-slate-100 space-y-4">
        <div class="flex justify-between items-center border-b border-slate-100 pb-3">
            <h3 class="text-base font-bold text-slate-800">Form Tambah Pengguna Baru</h3>
            <button onclick="toggleUserModal(false)" class="text-slate-400 hover:text-slate-600 font-bold text-xl cursor-pointer">&times;</button>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-slate-700 text-xs font-semibold mb-1">Nama Lengkap</label>
                <input type="text" name="nama" value="{{ old('nama') }}" required placeholder="Contoh: Budi Santoso" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 @error('nama') border-rose-500 @enderror">
                @error('nama')
                    <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-slate-700 text-xs font-semibold mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="budi@gmail.com" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 @error('email') border-rose-500 @enderror">
                @error('email')
                    <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-slate-700 text-xs font-semibold mb-1">Divisi Kerja</label>
                <input type="text" name="divisi" value="{{ old('divisi') }}" required placeholder="Contoh: IT / Operasional / Keuangan" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 @error('divisi') border-rose-500 @enderror">
                @error('divisi')
                    <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-slate-700 text-xs font-semibold mb-1">Role / Hak Akses</label>
                <select name="role" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs bg-white focus:ring-2 focus:ring-indigo-500 @error('role') border-rose-500 @enderror">
                    <option value="karyawan" {{ old('role') == 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                    <option value="pimpinan" {{ old('role') == 'pimpinan' ? 'selected' : '' }}>Pimpinan</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin Sarpras</option>
                </select>
                @error('role')
                    <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-slate-700 text-xs font-semibold mb-1">Password</label>
                <input type="password" name="password" required placeholder="Minimal 6 karakter" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 @error('password') border-rose-500 @enderror">
                @error('password')
                    <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="toggleUserModal(false)" class="px-4 py-2.5 border border-slate-200 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 cursor-pointer">Batal</button>
                <button type="submit" class="px-4 py-2.5 bg-indigo-600 text-white rounded-xl text-xs font-semibold hover:bg-indigo-700 transition shadow-md shadow-indigo-600/20 cursor-pointer">Simpan Akun</button>
            </div>
        </form>
    </div>
</div>

<!-- ================= MODAL EDIT PENGGUNA ================= -->
<div id="editUserModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 z-50 opacity-0 pointer-events-none transition-all duration-300">
    <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 max-w-md w-full p-6 sm:p-7 transform scale-95 transition-all duration-300 relative" id="editModalCard">
        
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-indigo-50 text-indigo-600 rounded-2xl border border-indigo-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 text-base">Edit Data Pengguna</h3>
                    <p class="text-[11px] text-slate-400">Perbarui informasi akun dan hak akses</p>
                </div>
            </div>
            <button type="button" onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-full hover:bg-slate-100 transition cursor-pointer">✕</button>
        </div>

        <form id="formEditUser" method="POST" class="mt-5 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Nama Lengkap</label>
                <input type="text" name="nama" id="edit_nama" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-indigo-600 focus:bg-white" required>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Alamat Email</label>
                <input type="email" name="email" id="edit_email" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-indigo-600 focus:bg-white" required>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Role / Hak Akses</label>
                <select name="role" id="edit_role" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-indigo-600 focus:bg-white" required>
                    <option value="admin">Admin</option>
                    <option value="pimpinan">Pimpinan</option>
                    <option value="karyawan">Karyawan</option>
                </select>
            </div>

            <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl text-xs transition shadow-md shadow-indigo-600/20 cursor-pointer">
                Simpan Perubahan
            </button>
        </form>
    
    </div>
</div>

<script>
    function toggleUserModal(show) {
        const modal = document.getElementById('userModal');
        if (show) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        } else {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    function openEditModal(id, nama, email, role) {
        const modal = document.getElementById('editUserModal');
        const modalCard = document.getElementById('editModalCard');
        const form = document.getElementById('formEditUser');
        
        form.action = `/admin/users/${id}`;
        
        document.getElementById('edit_nama').value = nama;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_role').value = role.toLowerCase();
        
        modal.classList.remove('opacity-0', 'pointer-events-none');
        modalCard.classList.remove('scale-95');
        modalCard.classList.add('scale-100');
    }

    let closeEditModal = function() {
        const modal = document.getElementById('editUserModal');
        const modalCard = document.getElementById('editModalCard');
        
        modal.classList.add('opacity-0', 'pointer-events-none');
        modalCard.classList.remove('scale-100');
        modalCard.classList.add('scale-95');
    };

    @if ($errors->any())
        document.addEventListener("DOMContentLoaded", function() {
            toggleUserModal(true);
        });
    @endif
</script>
@endsection