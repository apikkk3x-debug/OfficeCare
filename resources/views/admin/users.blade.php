@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Manajemen Pengguna</h2>
            <p class="text-slate-500 text-sm mt-1">Daftar seluruh akun yang terdaftar dalam sistem OfficeCare.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-lg text-sm font-semibold transition">
            &larr; Kembali ke Dashboard
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded-xl text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-4 rounded-xl text-sm font-medium">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
        <h3 class="text-lg font-bold text-slate-800 mb-4">Daftar Akun Pengguna Kantor</h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-slate-50 text-slate-600 border-b">
                        <th class="p-3">No</th>
                        <th class="p-3">Nama Lengkap</th>
                        <th class="p-3">Email Kantor</th>
                        <th class="p-3">Tanggal Bergabung</th>
                        <th class="p-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($users as $index => $u)
                        <tr>
                            <td class="p-3 text-slate-600">{{ $index + 1 }}</td>
                            <td class="p-3 font-medium text-slate-800">{{ $u->name }}</td>
                            <td class="p-3 text-slate-600">{{ $u->email }}</td>
                            <td class="p-3 text-slate-600">{{ $u->created_at->format('d/m/Y') }}</td>
                            <td class="p-3">
                                @if($u->id !== auth()->id())
                                   <form action="{{ route('admin.users.hapus', $u->id_user) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus akun ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 px-3 py-1 rounded-lg text-xs font-semibold transition border border-red-200 cursor-pointer">
                                            Hapus Akun
                                        </button>
                                    </form>
                                 @else
                                    <span class="text-slate-400 text-xs italic">Akun Anda</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-6 text-center text-slate-400">Belum ada pengguna terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection