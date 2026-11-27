@extends('layouts.app')

@section('title', 'Manajemen Pengguna')
@section('page_title', 'Manajemen Pengguna & Hak Akses')
@section('page_subtitle', 'Kelola akun pengguna, penetapan peran (Pengelola / Pegawai), dan status aktifitas')

@section('content')
<div class="space-y-6">
    <!-- Stat Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <!-- Total Users -->
        <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
            <p class="text-xs font-medium text-gray-500">Total Akun Pengguna</p>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-extrabold text-gray-900">{{ $totalUsers }}</span>
                <span class="text-[11px] text-gray-400 font-semibold">User</span>
            </div>
        </div>

        <!-- Pengelola -->
        <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
            <p class="text-xs font-medium text-gray-500">Peran Pengelola</p>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-extrabold text-amber-500">{{ $totalPengelola }}</span>
                <span class="text-[11px] text-amber-600 bg-amber-50 px-2 py-0.5 rounded-md font-semibold">Akses Penuh</span>
            </div>
        </div>

        <!-- Pegawai -->
        <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
            <p class="text-xs font-medium text-gray-500">Peran Pegawai</p>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-extrabold text-blue-600">{{ $totalPegawai }}</span>
                <span class="text-[11px] text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md font-semibold">Operasional</span>
            </div>
        </div>

        <!-- Akun Aktif -->
        <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
            <p class="text-xs font-medium text-gray-500">Status Aktif</p>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-extrabold text-[#00875a]">{{ $totalAktif }}</span>
                <span class="text-[11px] text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md font-semibold">Aktif</span>
            </div>
        </div>
    </div>

    <!-- Description & Add Button -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-xs sm:text-sm text-gray-600">
                Daftar akun yang memiliki akses ke sistem SI-MBEK. Pengelola dapat menonaktifkan akun untuk mencabut akses login.
            </p>
        </div>

        <a href="{{ route('users.create') }}" 
           class="inline-flex items-center justify-center gap-2 bg-[#00875a] hover:bg-[#00734c] text-white px-5 py-2.5 rounded-xl font-bold text-xs sm:text-sm shadow-sm transition-all flex-shrink-0">
            <i class="fa-solid fa-user-plus text-xs"></i>
            <span>Tambah Akun Baru</span>
        </a>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
        <form method="GET" action="{{ route('users.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
            <div class="sm:col-span-5">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 text-xs">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                    <input type="text" 
                           name="search" 
                           value="{{ $search }}" 
                           placeholder="Cari nama atau email pengguna..." 
                           class="w-full pl-9 pr-3 py-2 text-xs sm:text-sm rounded-xl border border-gray-200 bg-gray-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-[#00875a] transition-all">
                </div>
            </div>

            <div class="sm:col-span-3">
                <select name="role" class="w-full py-2 px-3 text-xs sm:text-sm rounded-xl border border-gray-200 bg-gray-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-[#00875a] transition-all text-gray-700">
                    <option value="">Semua Peran (Role)</option>
                    <option value="pengelola" {{ $role === 'pengelola' ? 'selected' : '' }}>Pengelola</option>
                    <option value="pegawai" {{ $role === 'pegawai' ? 'selected' : '' }}>Pegawai</option>
                </select>
            </div>

            <div class="sm:col-span-2">
                <select name="status" class="w-full py-2 px-3 text-xs sm:text-sm rounded-xl border border-gray-200 bg-gray-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-[#00875a] transition-all text-gray-700">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ $status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ $status === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <div class="sm:col-span-2 flex items-center gap-2">
                <button type="submit" 
                        class="w-full bg-gray-900 hover:bg-black text-white py-2 px-4 rounded-xl text-xs sm:text-sm font-semibold transition-colors flex items-center justify-center gap-1.5 shadow-sm">
                    <i class="fa-solid fa-filter text-xs"></i>
                    <span>Filter</span>
                </button>

                @if($search || $role || $status)
                    <a href="{{ route('users.index') }}" 
                       title="Reset Filter"
                       class="p-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-xs flex items-center justify-center transition-colors">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-100 text-gray-500 uppercase text-[11px] font-bold tracking-wider">
                        <th class="py-3.5 px-5">Nama Pengguna</th>
                        <th class="py-3.5 px-5">Email</th>
                        <th class="py-3.5 px-5">Peran (Role)</th>
                        <th class="py-3.5 px-5">Status Akun</th>
                        <th class="py-3.5 px-5">Tanggal Dibuat</th>
                        <th class="py-3.5 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $u)
                        <tr class="hover:bg-gray-50/70 transition-colors {{ !$u->status_aktif ? 'bg-gray-50/60 opacity-80' : '' }}">
                            <td class="py-3.5 px-5 font-bold text-gray-800">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full {{ $u->isPengelola() ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800' }} flex items-center justify-center text-xs font-bold">
                                        {{ strtoupper(substr($u->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <span>{{ $u->name }}</span>
                                        @if(auth()->id() === $u->id)
                                            <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-1.5 py-0.2 rounded ml-1">Saya</span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td class="py-3.5 px-5 text-gray-600 font-medium">
                                {{ $u->email }}
                            </td>

                            <td class="py-3.5 px-5">
                                @if($u->isPengelola())
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                                        <i class="fa-solid fa-user-shield text-[10px]"></i>
                                        <span>Pengelola</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-800 border border-blue-200">
                                        <i class="fa-solid fa-user-gear text-[10px]"></i>
                                        <span>Pegawai</span>
                                    </span>
                                @endif
                            </td>

                            <td class="py-3.5 px-5">
                                @if($u->status_aktif)
                                    <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs px-2.5 py-0.5 rounded-full font-semibold">
                                        <i class="fa-solid fa-circle-check text-[10px]"></i>
                                        <span>Aktif</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-red-50 text-red-700 border border-red-200 text-xs px-2.5 py-0.5 rounded-full font-semibold">
                                        <i class="fa-solid fa-ban text-[10px]"></i>
                                        <span>Nonaktif</span>
                                    </span>
                                @endif
                            </td>

                            <td class="py-3.5 px-5 text-gray-500 text-xs">
                                {{ $u->created_at ? $u->created_at->translatedFormat('d M Y') : '-' }}
                            </td>

                            <td class="py-3.5 px-5 text-right font-medium">
                                <div class="inline-flex items-center gap-3">
                                    <a href="{{ route('users.edit', $u->id) }}" 
                                       class="text-blue-600 hover:text-blue-800 text-xs font-semibold hover:underline">
                                        Edit
                                    </a>

                                    @if(auth()->id() !== $u->id)
                                        <!-- Toggle Status Aktif / Nonaktif -->
                                        <form action="{{ route('users.toggle-status', $u->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    onclick="return confirm('Apakah Anda yakin ingin {{ $u->status_aktif ? 'menonaktifkan' : 'mengaktifkan kembali' }} akun {{ $u->name }}?')"
                                                    class="text-xs font-semibold {{ $u->status_aktif ? 'text-amber-600 hover:text-amber-800' : 'text-emerald-600 hover:text-emerald-800' }} hover:underline">
                                                {{ $u->status_aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>

                                        <!-- Hapus Akun -->
                                        <form action="{{ route('users.destroy', $u->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    onclick="return confirm('Hapus permanen akun {{ $u->name }}? Pengguna tidak akan dapat login lagi.')"
                                                    class="text-red-500 hover:text-red-700 text-xs font-semibold hover:underline">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-gray-400 text-xs">
                                <i class="fa-solid fa-users text-4xl mb-2 text-gray-300"></i>
                                <p class="text-sm font-semibold text-gray-500">Tidak ada data pengguna ditemukan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
