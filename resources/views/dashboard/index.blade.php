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
            <h3 class="text-lg font-semibold text-black dark:text-white">
              Periode Aktif: {{ $currentPeriod->name }}
            </h3>
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
            <svg class="fill-primary" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M12 12C14.21 12 16 10.21 16 8C16 5.79 14.21 4 12 4C9.79 4 8 5.79 8 8C8 10.21 9.79 12 12 12ZM12 14C9.33 14 4 15.34 4 18V20H20V18C20 15.34 14.67 14 12 14Z" />
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
            <svg class="fill-success" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M9 16.17L4.83 12L3.41 13.41L9 19L21 7L19.59 5.59L9 16.17Z" />
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
            <svg class="fill-warning" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM12 20C7.59 20 4 16.41 4 12C4 7.59 7.59 4 12 4C16.41 4 20 7.59 20 12C20 16.41 16.41 20 12 20Z" />
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
            <svg class="fill-primary" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M12 2L4 5V11.09C4 16.14 7.41 20.85 12 22C16.59 20.85 20 16.14 20 11.09V5L12 2ZM18 11.09C18 15.09 15.45 18.79 12 19.92C8.55 18.79 6 15.09 6 11.09V6.39L12 4.14L18 6.39V11.09Z" />
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
            <svg class="fill-meta-3" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M20 2H4C2.9 2 2 2.9 2 4V22L6 18H20C21.1 18 22 17.1 22 16V4C22 2.9 21.1 2 20 2ZM20 16H5.17L4 17.17V4H20V16Z" />
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
            <svg class="fill-success" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M9 16.17L4.83 12L3.41 13.41L9 19L21 7L19.59 5.59L9 16.17Z" />
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
            <svg class="fill-warning" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M14 2H6C4.9 2 4.01 2.9 4.01 4L4 20C4 21.1 4.89 22 5.99 22H18C19.1 22 20 21.1 20 20V8L14 2ZM16 18H8V16H16V18ZM16 14H8V12H16V14ZM13 9V3.5L18.5 9H13Z" />
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
            <svg class="fill-danger" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M22.7 19L13.6 9.9C14.5 7.6 14 4.9 12.1 3C10.1 1 7.1 0.6 4.7 1.7L9 6L6 9L1.6 4.7C0.4 7.1 0.9 10.1 2.9 12.1C4.8 14 7.5 14.5 9.8 13.6L18.9 22.7C19.3 23.1 19.9 23.1 20.3 22.7L22.6 20.4C23.1 20 23.1 19.3 22.7 19Z" />
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
            <svg class="fill-meta-1" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M19 3H5C3.89 3 3 3.9 3 5V19C3 20.1 3.89 21 5 21H19C20.1 21 21 20.1 21 19V5C21 3.9 20.1 3 19 3ZM19 19H5V5H19V19Z" />
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
            <svg class="fill-success" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM10 17L5 12L6.41 10.59L10 14.17L17.59 6.58L19 8L10 17Z" />
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
            <svg class="fill-primary" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M19 3H14.82C14.4 1.84 13.3 1 12 1C10.7 1 9.6 1.84 9.18 3H5C3.9 3 3 3.9 3 5V19C3 20.1 3.9 21 5 21H19C20.1 21 21 20.1 21 19V5C21 3.9 20.1 3 19 3ZM12 3C12.55 3 13 3.45 13 4C13 4.55 12.55 5 12 5C11.45 5 11 4.55 11 4C11 3.45 11.45 3 12 3ZM14 17H7V15H14V17ZM17 13H7V11H17V13ZM17 9H7V7H17V9Z" />
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
            <svg class="fill-meta-2" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M11 15H13V17H11V15ZM11 7H13V13H11V7ZM12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM12 20C7.59 20 4 16.41 4 12C4 7.59 7.59 4 12 4C16.41 4 20 7.59 20 12C20 16.41 16.41 20 12 20Z" />
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
            <svg class="fill-meta-1" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M17 12H19V19H17V12ZM11 12H13V19H11V12ZM5 12H7V19H5V12Z" />
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
            <svg class="fill-success" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM12 20C7.59 20 4 16.41 4 12C4 7.59 7.59 4 12 4C16.41 4 20 7.59 20 12C20 16.41 16.41 20 12 20Z" />
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
            <svg class="fill-primary" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M9 16.17L4.83 12L3.41 13.41L9 19L21 7L19.59 5.59L9 16.17Z" />
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
        <h3 class="text-lg font-semibold text-black dark:text-white mb-4">Aktivitas Mendatang (7 Hari)</h3>
        
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
        <h3 class="text-lg font-semibold text-black dark:text-white mb-4">Anggota Terbaru</h3>
        
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
