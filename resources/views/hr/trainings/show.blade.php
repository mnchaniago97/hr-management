@extends('layouts.app')

@section('title', 'Detail Pelatihan')

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-6">
  <div class="col-span-12">
    <div class="rounded-sm border border-stroke bg-white px-5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
      <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <h2 class="text-xl font-semibold text-black dark:text-white">Detail Pelatihan</h2>
        <div class="flex flex-wrap gap-2">
          <a href="{{ route('hr.trainings.edit', $training->id) }}" class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">Edit</a>
          <a href="{{ route('hr.trainings.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">Kembali</a>
        </div>
      </div>

      <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
          <h3 class="mb-4 text-lg font-semibold text-black dark:text-white">Informasi Pelatihan</h3>
          <div class="space-y-3">
            <div class="flex justify-between"><span class="text-sm text-gray-500">Nama</span><span class="font-medium">{{ $training->name }}</span></div>
            <div class="flex justify-between"><span class="text-sm text-gray-500">Lokasi</span><span class="font-medium">{{ $training->location ?? '-' }}</span></div>
            <div class="flex justify-between"><span class="text-sm text-gray-500">Tanggal Mulai</span><span class="font-medium">{{ $training->start_date }}</span></div>
            <div class="flex justify-between"><span class="text-sm text-gray-500">Tanggal Selesai</span><span class="font-medium">{{ $training->end_date }}</span></div>
            <div class="flex justify-between"><span class="text-sm text-gray-500">Kapasitas</span><span class="font-medium">{{ $training->capacity ?? '-' }}</span></div>
            <div class="flex justify-between"><span class="text-sm text-gray-500">Status</span>
              @switch($training->status)
                @case('planned')<span class="text-theme-xs inline-block rounded-full px-2 py-0.5 font-medium bg-blue-50 text-blue-700">Direncanakan</span>@break
                @case('ongoing')<span class="text-theme-xs inline-block rounded-full px-2 py-0.5 font-medium bg-green-50 text-green-700">Berlangsung</span>@break
                @case('completed')<span class="text-theme-xs inline-block rounded-full px-2 py-0.5 font-medium bg-gray-100 text-gray-700">Selesai</span>@break
                @case('cancelled')<span class="text-theme-xs inline-block rounded-full px-2 py-0.5 font-medium bg-red-50 text-red-700">Dibatalkan</span>@break
              @endswitch
            </div>
          </div>
        </div>
        <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
          <h3 class="mb-4 text-lg font-semibold text-black dark:text-white">Deskripsi</h3>
          <p class="text-sm text-gray-500 dark:text-gray-400">{{ $training->description ?? 'Tidak ada deskripsi.' }}</p>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection


