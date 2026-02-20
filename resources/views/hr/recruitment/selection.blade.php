@extends('layouts.app')

@section('title', 'Seleksi Rekrutmen')

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-6">
  <div class="col-span-12">
    <div class="rounded-sm border border-stroke bg-white px-5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
      <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div>
          <h2 class="text-xl font-semibold text-black dark:text-white">Seleksi Rekrutmen</h2>
          <p class="text-sm text-gray-500 mt-1">Halaman seleksi rekrutmen belum tersedia.</p>
        </div>
        <a href="{{ route('hr.recruitment.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
          Kembali
        </a>
      </div>

      <div class="rounded-xl border border-gray-200 bg-gray-50 p-6 text-sm text-gray-600 dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-300">
        Fitur seleksi akan ditambahkan pada tahap berikutnya.
      </div>
    </div>
  </div>
</div>
@endsection
