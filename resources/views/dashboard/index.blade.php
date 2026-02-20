@extends('layouts.app')

@section('title', $title)

@section('content')
  <div class="grid grid-cols-12 gap-4 md:gap-6">
    <!-- Periode Aktif -->
    @if($currentPeriod)
    <div class="col-span-12">
      <div class="rounded-sm border border-stroke bg-white px-5 py-4 shadow-default dark:border-strokedark dark:bg-boxdark">
        <div class="flex items-center justify-between">
          <div>
            <div class="flex items-center gap-2">
              <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                  <path d="M7 3V6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                  <path d="M17 3V6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                  <path d="M4 9H20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                  <rect x="4" y="6" width="16" height="14" rx="2" stroke="currentColor" stroke-width="1.5"/>
                </svg>
              </span>
              <h3 class="text-lg font-semibold text-black dark:text-white">
                Periode Aktif: {{ $currentPeriod->name }}
              </h3>
            </div>
            <p class="mt-1 text-sm text-gray-500">
              {{ \Carbon\Carbon::parse($currentPeriod->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($currentPeriod->end_date)->format('d M Y') }}
            </p>
          </div>
          <span class="inline-flex items-center rounded-full bg-success px-3 py-1 text-xs font-medium text-white">
            Aktif
          </span>
        </div>
      </div>
    </div>
    @endif

    <!-- HR / Keanggotaan Stats -->
    <div class="col-span-12 xl:col-span-3">
      <div class="rounded-xl border border-stroke bg-white px-5 py-5 shadow-sm dark:border-strokedark dark:bg-boxdark">
        <div class="flex items-center gap-4">
          <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">
            <svg class="fill-primary" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
              <path d="M16 11a4 4 0 1 0-4-4 4 4 0 0 0 4 4Zm-8 1a4 4 0 1 0-4-4 4 4 0 0 0 4 4Zm0 2c-3.87 0-7 2.24-7 5v1h9.5a6.96 6.96 0 0 1 1.82-4.69A7.98 7.98 0 0 0 8 14Zm8 0a5 5 0 0 0-5 5v1h11v-1a5 5 0 0 0-5-5Z"/>
            </svg>
          </div>
          <div class="flex-1">
            <h4 class="text-2xl font-semibold text-black dark:text-white">{{ $stats['total_members'] }}</h4>
            <p class="text-sm text-gray-500">Total Anggota</p>
          </div>
          <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-300">
            Live
          </span>
        </div>
      </div>
    </div>

    <div class="col-span-12 xl:col-span-3">
      <div class="rounded-xl border border-stroke bg-white px-5 py-5 shadow-sm dark:border-strokedark dark:bg-boxdark">
        <div class="flex items-center gap-4">
          <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">
            <svg class="fill-success" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
              <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2Zm-1.2 13.6-3.4-3.4L5.6 14l5.2 5.2L18.4 11l-1.8-1.8Z"/>
            </svg>
          </div>
          <div class="flex-1">
            <h4 class="text-2xl font-semibold text-black dark:text-white">{{ $stats['active_members'] }}</h4>
            <p class="text-sm text-gray-500">Anggota Aktif</p>
          </div>
          <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-300">
            Live
          </span>
        </div>
      </div>
    </div>

    <div class="col-span-12 xl:col-span-3">
      <div class="rounded-xl border border-stroke bg-white px-5 py-5 shadow-sm dark:border-strokedark dark:bg-boxdark">
        <div class="flex items-center gap-4">
          <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">
            <svg class="fill-warning" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
              <path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4Zm-7 9v-1a6 6 0 0 1 6-6h2a6 6 0 0 1 6 6v1Z"/>
              <path d="M18.5 10h-2V8h-2v2h-2v2h2v2h2v-2h2Z"/>
            </svg>
          </div>
          <div class="flex-1">
            <h4 class="text-2xl font-semibold text-black dark:text-white">{{ $stats['am_count'] }}</h4>
            <p class="text-sm text-gray-500">Anggota Muda (AM)</p>
          </div>
          <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-300">
            Live
          </span>
        </div>
      </div>
    </div>

    <div class="col-span-12 xl:col-span-3">
      <div class="rounded-xl border border-stroke bg-white px-5 py-5 shadow-sm dark:border-strokedark dark:bg-boxdark">
        <div class="flex items-center gap-4">
          <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">
            <svg class="fill-primary" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
              <path d="M12 2 4 5v6c0 5.55 3.84 10.74 8 11 4.16-.26 8-5.45 8-11V5Zm3.2 7.2-3.95 4a1 1 0 0 1-1.42 0L8 11.37l1.41-1.41 1.33 1.33 3.24-3.28Z"/>
            </svg>
          </div>
          <div class="flex-1">
            <h4 class="text-2xl font-semibold text-black dark:text-white">{{ $stats['at_count'] }}</h4>
            <p class="text-sm text-gray-500">Anggota Tetap (AT)</p>
          </div>
          <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-300">
            Live
          </span>
        </div>
      </div>
    </div>

    <!-- Asset Stats -->
    <div class="col-span-12 xl:col-span-3">
      <div class="rounded-xl border border-stroke bg-white px-5 py-5 shadow-sm dark:border-strokedark dark:bg-boxdark">
        <div class="flex items-center gap-4">
          <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">
            <svg class="fill-meta-3" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
              <path d="M3 7 12 3l9 4-9 4Zm0 2 9 4 9-4v8l-9 4-9-4Z"/>
            </svg>
          </div>
          <div class="flex-1">
            <h4 class="text-2xl font-semibold text-black dark:text-white">{{ $stats['total_assets'] }}</h4>
            <p class="text-sm text-gray-500">Total Aset</p>
          </div>
          <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-300">
            Live
          </span>
        </div>
      </div>
    </div>

    <div class="col-span-12 xl:col-span-3">
      <div class="rounded-xl border border-stroke bg-white px-5 py-5 shadow-sm dark:border-strokedark dark:bg-boxdark">
        <div class="flex items-center gap-4">
          <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">
            <svg class="fill-success" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
              <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2Zm-1.2 13.6-3.4-3.4L5.6 14l5.2 5.2L18.4 11l-1.8-1.8Z"/>
            </svg>
          </div>
          <div class="flex-1">
            <h4 class="text-2xl font-semibold text-black dark:text-white">{{ $stats['available_assets'] }}</h4>
            <p class="text-sm text-gray-500">Aset Tersedia</p>
          </div>
          <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-300">
            Live
          </span>
        </div>
      </div>
    </div>

    <div class="col-span-12 xl:col-span-3">
      <div class="rounded-xl border border-stroke bg-white px-5 py-5 shadow-sm dark:border-strokedark dark:bg-boxdark">
        <div class="flex items-center gap-4">
          <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">
            <svg class="fill-warning" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
              <path d="M7 13h3.59l3.2 3.2a1 1 0 0 0 1.41 0l3.8-3.8a3 3 0 0 0 0-4.24L17.9 7a3 3 0 0 0-4.24 0L12 8.66V7a4 4 0 0 0-4-4H3v4h4a2 2 0 0 1 2 2v4Zm8.7 1.7-2.41-2.4H9v-3.59l4.83-4.83a1 1 0 0 1 1.42 0l1.06 1.06a1 1 0 0 1 0 1.41L13.7 8.94l2.36 2.36 1.34-1.34a1 1 0 0 1 1.42 0l.35.35a1 1 0 0 1 0 1.41Z"/>
            </svg>
          </div>
          <div class="flex-1">
            <h4 class="text-2xl font-semibold text-black dark:text-white">{{ $stats['borrowed_assets'] }}</h4>
            <p class="text-sm text-gray-500">Aset Dipinjam</p>
          </div>
          <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-300">
            Live
          </span>
        </div>
      </div>
    </div>

    <div class="col-span-12 xl:col-span-3">
      <div class="rounded-xl border border-stroke bg-white px-5 py-5 shadow-sm dark:border-strokedark dark:bg-boxdark">
        <div class="flex items-center gap-4">
          <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">
            <svg class="fill-danger" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
              <path d="M22.7 19 19 22.7a1 1 0 0 1-1.4 0l-3.4-3.4a8 8 0 0 1-9.5-9.5L1.3 6.4a1 1 0 0 1 0-1.4L5 1.3a1 1 0 0 1 1.4 0l3.4 3.4a8 8 0 0 1 9.5 9.5l3.4 3.4a1 1 0 0 1 0 1.4ZM7.1 6.4 5.6 4.9 3.9 6.6l1.5 1.5Z"/>
            </svg>
          </div>
          <div class="flex-1">
            <h4 class="text-2xl font-semibold text-black dark:text-white">{{ $stats['maintenance_assets'] }}</h4>
            <p class="text-sm text-gray-500">Aset Maintenance</p>
          </div>
          <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-300">
            Live
          </span>
        </div>
      </div>
    </div>

    <!-- Program Stats -->
    <div class="col-span-12 xl:col-span-3">
      <div class="rounded-xl border border-stroke bg-white px-5 py-5 shadow-sm dark:border-strokedark dark:bg-boxdark">
        <div class="flex items-center gap-4">
          <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">
            <svg class="fill-meta-1" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
              <path d="M4 4h6l2 2h8v14H4Z"/>
            </svg>
          </div>
          <div class="flex-1">
            <h4 class="text-2xl font-semibold text-black dark:text-white">{{ $stats['total_programs'] }}</h4>
            <p class="text-sm text-gray-500">Total Program</p>
          </div>
          <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-300">
            Live
          </span>
        </div>
      </div>
    </div>

    <div class="col-span-12 xl:col-span-3">
      <div class="rounded-xl border border-stroke bg-white px-5 py-5 shadow-sm dark:border-strokedark dark:bg-boxdark">
        <div class="flex items-center gap-4">
          <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">
            <svg class="fill-success" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
              <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2Zm-2 6 7 4-7 4Z"/>
            </svg>
          </div>
          <div class="flex-1">
            <h4 class="text-2xl font-semibold text-black dark:text-white">{{ $stats['active_programs'] }}</h4>
            <p class="text-sm text-gray-500">Program Aktif</p>
          </div>
          <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-300">
            Live
          </span>
        </div>
      </div>
    </div>

    <div class="col-span-12 xl:col-span-3">
      <div class="rounded-xl border border-stroke bg-white px-5 py-5 shadow-sm dark:border-strokedark dark:bg-boxdark">
        <div class="flex items-center gap-4">
          <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">
            <svg class="fill-primary" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
              <path d="M7 3h10a2 2 0 0 1 2 2v14H5V5a2 2 0 0 1 2-2Zm2 6h6v2H9Zm0 4h6v2H9Z"/>
              <path d="M9 2h6v3H9Z"/>
            </svg>
          </div>
          <div class="flex-1">
            <h4 class="text-2xl font-semibold text-black dark:text-white">{{ $stats['terprogram_count'] }}</h4>
            <p class="text-sm text-gray-500">Terprogram</p>
          </div>
          <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-300">
            Live
          </span>
        </div>
      </div>
    </div>

    <div class="col-span-12 xl:col-span-3">
      <div class="rounded-xl border border-stroke bg-white px-5 py-5 shadow-sm dark:border-strokedark dark:bg-boxdark">
        <div class="flex items-center gap-4">
          <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">
            <svg class="fill-meta-2" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
              <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2Zm1 6v6h-2V8Zm0 8v2h-2v-2Z"/>
            </svg>
          </div>
          <div class="flex-1">
            <h4 class="text-2xl font-semibold text-black dark:text-white">{{ $stats['insidentil_count'] }}</h4>
            <p class="text-sm text-gray-500">Insidentil</p>
          </div>
          <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-300">
            Live
          </span>
        </div>
      </div>
    </div>

    <!-- Aktivitas Stats -->
    <div class="col-span-12 xl:col-span-4">
      <div class="rounded-xl border border-stroke bg-white px-5 py-5 shadow-sm dark:border-strokedark dark:bg-boxdark">
        <div class="flex items-center gap-4">
          <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">
            <svg class="fill-meta-1" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
              <path d="M4 17h3V7H4Zm6 0h3V4h-3Zm6 0h3V10h-3Z"/>
            </svg>
          </div>
          <div class="flex-1">
            <h4 class="text-2xl font-semibold text-black dark:text-white">{{ $stats['total_activities'] }}</h4>
            <p class="text-sm text-gray-500">Total Aktivitas</p>
          </div>
          <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-300">
            Live
          </span>
        </div>
      </div>
    </div>

    <div class="col-span-12 xl:col-span-4">
      <div class="rounded-xl border border-stroke bg-white px-5 py-5 shadow-sm dark:border-strokedark dark:bg-boxdark">
        <div class="flex items-center gap-4">
          <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">
            <svg class="fill-success" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
              <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2Zm1 5h-2v6l5 3 1-1.73-4-2.27Z"/>
            </svg>
          </div>
          <div class="flex-1">
            <h4 class="text-2xl font-semibold text-black dark:text-white">{{ $stats['ongoing_activities'] }}</h4>
            <p class="text-sm text-gray-500">Aktif / Berlangsung</p>
          </div>
          <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-300">
            Live
          </span>
        </div>
      </div>
    </div>

    <div class="col-span-12 xl:col-span-4">
      <div class="rounded-xl border border-stroke bg-white px-5 py-5 shadow-sm dark:border-strokedark dark:bg-boxdark">
        <div class="flex items-center gap-4">
          <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">
            <svg class="fill-primary" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
              <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2Zm-1.2 13.6-3.4-3.4L5.6 14l5.2 5.2L18.4 11l-1.8-1.8Z"/>
            </svg>
          </div>
          <div class="flex-1">
            <h4 class="text-2xl font-semibold text-black dark:text-white">{{ $stats['completed_activities'] }}</h4>
            <p class="text-sm text-gray-500">Selesai</p>
          </div>
          <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-300">
            Live
          </span>
        </div>
      </div>
    </div>

    <!-- Aktivitas Mendatang -->
    <div class="col-span-12 xl:col-span-6">
      <div class="rounded-sm border border-stroke bg-white px-5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
        <div class="mb-4 flex items-center gap-2">
          <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
              <path d="M4 12H7.5L9.5 6L12.5 18L15 12H20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </span>
          <h3 class="text-lg font-semibold text-black dark:text-white">Aktivitas Mendatang (7 Hari)</h3>
        </div>
        
        @if($upcomingActivities->count() > 0)
        <div class="space-y-3">
          @foreach($upcomingActivities as $activity)
          <div class="flex items-center justify-between border-b border-gray-200 pb-3 dark:border-gray-700">
            <div>
              <p class="font-medium text-black dark:text-white">{{ $activity->name }}</p>
              <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($activity->start_date)->format('d M Y') }}</p>
            </div>
            <span class="inline-flex items-center rounded-full bg-primary px-2.5 py-0.5 text-xs font-medium text-white">
              {{ $activity->status }}
            </span>
          </div>
          @endforeach
        </div>
        @else
        <p class="text-gray-500 text-center py-4">Tidak ada aktivitas mendatang</p>
        @endif
      </div>
    </div>

    <!-- Anggota Terbaru -->
    <div class="col-span-12 xl:col-span-6">
      <div class="rounded-sm border border-stroke bg-white px-5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
        <div class="mb-4 flex items-center gap-2">
          <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
              <path d="M16 7C16 9.20914 14.2091 11 12 11C9.79086 11 8 9.20914 8 7C8 4.79086 9.79086 3 12 3C14.2091 3 16 4.79086 16 7Z" stroke="currentColor" stroke-width="1.5"/>
              <path d="M4 20C4 16.6863 7.13401 14 12 14C16.866 14 20 16.6863 20 20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
            </svg>
          </span>
          <h3 class="text-lg font-semibold text-black dark:text-white">Anggota Terbaru</h3>
        </div>
        
        @if($recentMembers->count() > 0)
        <div class="space-y-3">
          @foreach($recentMembers as $member)
          <div class="flex items-center justify-between border-b border-gray-200 pb-3 dark:border-gray-700">
            <div class="flex items-center gap-3">
              <div class="h-10 w-10 rounded-full bg-primary bg-opacity-10 flex items-center justify-center">
                <span class="text-sm font-medium text-primary">{{ substr($member->name, 0, 2) }}</span>
              </div>
              <div>
                <p class="font-medium text-black dark:text-white">{{ $member->name }}</p>
                <p class="text-sm text-gray-500">{{ $member->member_type ?? '-' }}</p>
              </div>
            </div>
            <span class="text-sm text-gray-500">
              {{ \Carbon\Carbon::parse($member->join_date)->format('d M Y') }}
            </span>
          </div>
          @endforeach
        </div>
        @else
        <p class="text-gray-500 text-center py-4">Belum ada anggota</p>
        @endif
      </div>
    </div>
  </div>
@endsection

