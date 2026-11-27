@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="space-y-6">
    <!-- Header Halaman Langsung di Fitur (Bukan di Navbar) -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-1">
        <div>
            <div class="flex items-center gap-2.5">
                <span class="text-2xl">👥</span>
                <h1 class="text-2xl font-black text-stone-900 tracking-tight">Manajemen Pengguna &amp; Hak Akses</h1>
            </div>
            <p class="text-xs sm:text-sm text-stone-500 mt-1">
                Kelola akun pengguna, penetapan peran (Pengelola / Pegawai), dan status aktifitas login.
            </p>
        </div>

        <a href="{{ route('users.create') }}" 
           class="inline-flex items-center justify-center gap-2 bg-[#0D4231] hover:bg-[#082E22] text-white px-5 py-2.5 rounded-xl font-bold text-xs sm:text-sm shadow-xs transition-all flex-shrink-0">
            <i class="fa-solid fa-user-plus text-xs"></i>
            <span>Tambah Akun Baru</span>
        </a>
    </div>

    <!-- 4 Kartu Ringkasan (White Cards, Rounded Corners) -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
        <!-- Total Users -->
        <div class="bg-white rounded-2xl border border-[#ECE5DA] p-4 shadow-2xs">
            <p class="text-xs font-semibold text-stone-500">Total Pengguna</p>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-black text-stone-900">{{ $totalUsers }}</span>
                <span class="text-[11px] text-stone-400 font-bold">Akun</span>
            </div>
        </div>

        <!-- Pengelola -->
        <div class="bg-white rounded-2xl border border-[#ECE5DA] p-4 shadow-2xs">
            <p class="text-xs font-semibold text-stone-500">Peran Pengelola</p>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-black text-[#A3704C]">{{ $totalPengelola }}</span>
                <span class="text-[11px] text-[#A3704C] bg-amber-50 px-2 py-0.5 rounded-lg font-bold">Akses Penuh</span>
            </div>
        </div>

        <!-- Pegawai -->
        <div class="bg-white rounded-2xl border border-[#ECE5DA] p-4 shadow-2xs">
            <p class="text-xs font-semibold text-stone-500">Peran Pegawai</p>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-black text-[#6B9080]">{{ $totalPegawai }}</span>
                <span class="text-[11px] text-[#6B9080] bg-[#FAF7F2] px-2 py-0.5 rounded-lg font-bold">Operasional</span>
            </div>
        </div>

        <!-- Akun Aktif -->
        <div class="bg-white rounded-2xl border border-[#ECE5DA] p-4 shadow-2xs">
            <p class="text-xs font-semibold text-stone-500">Status Aktif</p>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-black text-[#0D4231]">{{ $totalAktif }}</span>
                <span class="text-[11px] text-[#0D4231] bg-[#E8F3EE] px-2 py-0.5 rounded-lg font-bold">Aktif</span>
            </div>
        </div>
    </div>

    <!-- Search & Filter Card -->
    <div class="bg-white rounded-2xl border border-[#ECE5DA] p-4 shadow-2xs">
        <form method="GET" action="{{ route('users.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
            <div class="sm:col-span-5">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400 text-xs">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                    <input type="text" 
                           name="search" 
                           value="{{ $search }}" 
                           placeholder="Cari nama atau email pengguna..." 
                           class="w-full pl-9 pr-3 py-2 text-xs sm:text-sm rounded-xl border border-[#ECE5DA] bg-[#FAF7F2]/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#6B9080] focus:border-[#0D4231] transition-all">
                </div>
            </div>

            <div class="sm:col-span-3">
                <select name="role" class="w-full py-2 px-3 text-xs sm:text-sm rounded-xl border border-[#ECE5DA] bg-[#FAF7F2]/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#6B9080] focus:border-[#0D4231] transition-all text-stone-700">
                    <option value="">Semua Peran (Role)</option>
                    <option value="pengelola" {{ $role === 'pengelola' ? 'selected' : '' }}>Pengelola</option>
                    <option value="pegawai" {{ $role === 'pegawai' ? 'selected' : '' }}>Pegawai</option>
                </select>
            </div>

            <div class="sm:col-span-2">
                <select name="status" class="w-full py-2 px-3 text-xs sm:text-sm rounded-xl border border-[#ECE5DA] bg-[#FAF7F2]/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#6B9080] focus:border-[#0D4231] transition-all text-stone-700">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ $status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ $status === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <div class="sm:col-span-2 flex items-center gap-2">
                <button type="submit" 
                        class="w-full bg-[#2D2A26] hover:bg-black text-white py-2 px-4 rounded-xl text-xs sm:text-sm font-semibold transition-colors flex items-center justify-center gap-1.5 shadow-2xs">
                    <i class="fa-solid fa-filter text-xs"></i>
                    <span>Filter</span>
                </button>

                @if($search || $role || $status)
                    <a href="{{ route('users.index') }}" 
                       title="Reset Filter"
                       class="p-2 bg-[#FAF7F2] hover:bg-[#F3EDE2] text-stone-600 border border-[#ECE5DA] rounded-xl text-xs flex items-center justify-center transition-colors">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Users Table Minimalis dengan Garis Pembatas Tipis -->
    <div class="bg-white rounded-3xl border border-[#ECE5DA] shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                    <tr class="bg-[#F5F0E6]/70 border-b border-[#ECE5DA] text-stone-600 uppercase text-[11px] font-bold tracking-wider">
                        <th class="py-3.5 px-5">Nama Pengguna</th>
                        <th class="py-3.5 px-5">Email</th>
                        <th class="py-3.5 px-5">Peran (Role)</th>
                        <th class="py-3.5 px-5">Status Akun</th>
                        <th class="py-3.5 px-5">Tanggal Dibuat</th>
                        <th class="py-3.5 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#ECE5DA]">
                    @forelse($users as $u)
                        <tr class="hover:bg-[#FAF7F2] transition-colors {{ !$u->status_aktif ? 'bg-stone-50/60 opacity-80' : '' }}">
                            <td class="py-3.5 px-5 font-bold text-stone-900">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full {{ $u->isPengelola() ? 'bg-amber-100 text-amber-900' : 'bg-[#EAE4D9] text-stone-800' }} flex items-center justify-center text-xs font-bold">
                                        {{ strtoupper(substr($u->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <span>{{ $u->name }}</span>
                                        @if(auth()->id() === $u->id)
                                            <span class="bg-[#E8F3EE] text-[#0D4231] text-[10px] font-bold px-1.5 py-0.2 rounded ml-1">Saya</span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td class="py-3.5 px-5 text-stone-600 font-medium">
                                {{ $u->email }}
                            </td>

                            <td class="py-3.5 px-5">
                                @if($u->isPengelola())
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                                        <i class="fa-solid fa-user-shield text-[10px]"></i>
                                        <span>Pengelola</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#FAF7F2] text-stone-700 border border-[#ECE5DA]">
                                        <i class="fa-solid fa-user-gear text-[10px] text-[#6B9080]"></i>
                                        <span>Pegawai</span>
                                    </span>
                                @endif
                            </td>

                            <td class="py-3.5 px-5">
                                @if($u->status_aktif)
                                    <span class="inline-flex items-center gap-1 bg-[#E8F3EE] text-[#0D4231] border border-[#C5E2D4] text-xs px-2.5 py-0.5 rounded-full font-semibold">
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

                            <td class="py-3.5 px-5 text-stone-500 text-xs">
                                {{ $u->created_at ? $u->created_at->translatedFormat('d M Y') : '-' }}
                            </td>

                            <td class="py-3.5 px-5 text-right font-medium">
                                <div class="inline-flex items-center gap-3">
                                    <a href="{{ route('users.edit', $u->id) }}" 
                                       class="text-[#8D5B4C] hover:text-[#6F4E37] text-xs font-semibold hover:underline">
                                        Edit
                                    </a>

                                    @if(auth()->id() !== $u->id)
                                        <!-- Toggle Status Aktif / Nonaktif -->
                                        <form action="{{ route('users.toggle-status', $u->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    onclick="return confirm('Apakah Anda yakin ingin {{ $u->status_aktif ? 'menonaktifkan' : 'mengaktifkan kembali' }} akun {{ $u->name }}?')"
                                                    class="text-xs font-semibold {{ $u->status_aktif ? 'text-amber-700 hover:text-amber-900' : 'text-[#0D4231] hover:text-[#165B43]' }} hover:underline">
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
                            <td colspan="6" class="py-12 text-center text-stone-400 text-xs">
                                <span class="text-4xl block mb-2">👥</span>
                                <p class="text-sm font-semibold text-stone-600">Tidak ada data pengguna ditemukan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-4 border-t border-[#ECE5DA] bg-[#FAF7F2]/50">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
