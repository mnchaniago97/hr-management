@extends('layouts.app')

@section('title', 'Detail Absensi')

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-6">
  <div class="col-span-12">
    <div class="rounded-sm border border-stroke bg-white px-5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
      <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <h2 class="text-xl font-semibold text-black dark:text-white">Detail Absensi</h2>
        <a href="{{ route('hr.attendances.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
          Kembali
        </a>
      </div>

      <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <!-- Detail Card -->
        <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
          <h3 class="mb-4 text-lg font-semibold text-black dark:text-white">Informasi Absensi</h3>
          
          <div class="space-y-3">
            <div class="flex justify-between">
              <span class="text-sm text-gray-500 dark:text-gray-400">Anggota</span>
              <a href="{{ route('hr.members.show', $attendance->member->id) }}" class="font-medium text-primary hover:underline">{{ $attendance->member->name }}</a>
            </div>
            <div class="flex justify-between">
              <span class="text-sm text-gray-500 dark:text-gray-400">Email</span>
              <span class="font-medium text-black dark:text-white">{{ $attendance->member->email }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-sm text-gray-500 dark:text-gray-400">Tanggal</span>
              <span class="font-medium text-black dark:text-white">{{ $attendance->date }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-sm text-gray-500 dark:text-gray-400">Jam Masuk</span>
              <span class="font-medium text-black dark:text-white">{{ $attendance->check_in ? $attendance->check_in->format('H:i') : '-' }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-sm text-gray-500 dark:text-gray-400">Jam Keluar</span>
              <span class="font-medium text-black dark:text-white">{{ $attendance->check_out ? $attendance->check_out->format('H:i') : '-' }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-sm text-gray-500 dark:text-gray-400">Status</span>
              @switch($attendance->status)
                @case('present')
                  <span class="text-theme-xs inline-block rounded-full px-2 py-0.5 font-medium bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500">Hadir</span>
                  @break
                @case('absent')
                  <span class="text-theme-xs inline-block rounded-full px-2 py-0.5 font-medium bg-red-50 text-red-700 dark:bg-red-500/15 dark:text-red-500">Tidak Hadir</span>
                  @break
                @case('late')
                  <span class="text-theme-xs inline-block rounded-full px-2 py-0.5 font-medium bg-yellow-50 text-yellow-700 dark:bg-yellow-500/15 dark:text-yellow-400">Terlambat</span>
                  @break
                @case('on_leave')
                  <span class="text-theme-xs inline-block rounded-full px-2 py-0.5 font-medium bg-blue-50 text-blue-700 dark:bg-blue-500/15 dark:text-blue-400">Cuti</span>
                  @break
              @endswitch
            </div>
            <div class="flex justify-between">
              <span class="text-sm text-gray-500 dark:text-gray-400">Catatan</span>
              <span class="text-right font-medium text-black dark:text-white">{{ $attendance->notes ?? '-' }}</span>
            </div>
          </div>
        </div>

        <!-- Info Card -->
        <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
          <h3 class="mb-4 text-lg font-semibold text-black dark:text-white">Informasi Tambahan</h3>
          
          <div class="space-y-3">
            <div class="flex justify-between">
              <span class="text-sm text-gray-500 dark:text-gray-400">Dibuat pada</span>
              <span class="font-medium text-black dark:text-white">{{ $attendance->created_at->format('d M Y H:i') }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-sm text-gray-500 dark:text-gray-400">Diupdate pada</span>
              <span class="font-medium text-black dark:text-white">{{ $attendance->updated_at->format('d M Y H:i') }}</span>
            </div>
          </div>

          <div class="mt-6 border-t border-gray-200 pt-4 dark:border-gray-700">
            <form action="{{ route('hr.attendances.destroy', $attendance->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data absensi ini?')">
              @csrf
              @method('DELETE')
              <button type="submit" class="w-full rounded-lg bg-red-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-red-600">
                Hapus Absensi
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection


