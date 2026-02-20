@extends('layouts.app')

@section('title', 'Riwayat Penggunaan')

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-6">
  <div class="col-span-12">
    <div class="rounded-sm border border-stroke bg-white px-5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
      <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div>
          <h2 class="text-xl font-semibold text-black dark:text-white">Riwayat Penggunaan</h2>
          <p class="text-sm text-gray-500 mt-1">Lihat histori aset per anggota dan tanggal.</p>
        </div>
      </div>

      <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800">
          <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Riwayat</h3>
        </div>
        <div class="p-4 sm:p-6">
          <div class="rounded-lg border border-dashed border-gray-200 p-6 text-center text-sm text-gray-500 dark:border-gray-800 dark:text-gray-400">
            Riwayat peminjaman akan ditampilkan di sini.
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
