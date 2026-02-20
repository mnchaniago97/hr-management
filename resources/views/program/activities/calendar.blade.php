@extends('layouts.app')

@section('title', 'Kalender Kegiatan')

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-6">
  <div class="col-span-12">
    <div class="rounded-sm border border-stroke bg-white px-5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
      <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div>
          <h2 class="text-xl font-semibold text-black dark:text-white">Kalender Agenda</h2>
          <p class="text-sm text-gray-500 mt-1">Tampilkan daftar kegiatan dalam format kalender.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
          <a href="{{ route('program.activities.create') }}" class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
            Tambah Kegiatan
          </a>
          <a href="{{ route('program.activities.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
            Lihat Tabel
          </a>
        </div>
      </div>

      <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800">
          <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Kalender Kegiatan</h3>
        </div>
        <div class="p-4 sm:p-6">
          <div class="rounded-lg border border-dashed border-gray-200 p-6 text-center text-sm text-gray-500 dark:border-gray-800 dark:text-gray-400">
            Kalender akan ditampilkan di sini. Integrasikan dengan FullCalendar bila diperlukan.
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
