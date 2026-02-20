@extends('layouts.app')

@section('title', 'Detail Kegiatan')

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-6">
  <div class="col-span-12">
    <div class="rounded-sm border border-stroke bg-white px-5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
      @php
        $activityId = $activity->id ?? null;
      @endphp
      <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div>
          <h2 class="text-xl font-semibold text-black dark:text-white">Detail Kegiatan</h2>
          <p class="text-sm text-gray-500 mt-1">Lihat dokumen, peserta, dan log kegiatan.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
          @if($activityId)
            <a href="{{ route('program.activities.edit', $activityId) }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
              Ubah
            </a>
            <form action="{{ route('program.activities.destroy', $activityId) }}" method="POST">
              @csrf
              @method('DELETE')
              <button type="submit" class="inline-flex items-center rounded-lg bg-red-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-red-600" onclick="return confirm('Hapus kegiatan ini?')">
                Hapus
              </button>
            </form>
          @endif
          <a href="{{ route('program.activities.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
            Kembali
          </a>
        </div>
      </div>

      <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
          <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800">
              <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Informasi Kegiatan</h3>
              <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Ringkasan detail kegiatan utama.</p>
            </div>
            <div class="p-4 sm:p-6">
              <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                  <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Nama Kegiatan</p>
                  <p class="mt-1 text-sm font-medium text-gray-800 dark:text-white/90">{{ $activity->name }}</p>
                </div>
                <div>
                  <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Program</p>
                  <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $activity->program?->name ?? '-' }}</p>
                </div>
                <div>
                  <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Divisi</p>
                  <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $activity->division?->name ?? '-' }}</p>
                </div>
                <div>
                  <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Tipe</p>
                  <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ strtoupper(str_replace('_', ' ', $activity->type)) }}</p>
                </div>
                <div>
                  <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Tanggal</p>
                  <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">
                    {{ optional($activity->start_date)->format('d M Y') }} - {{ optional($activity->end_date)->format('d M Y') }}
                  </p>
                </div>
                <div>
                  <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Lokasi</p>
                  <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $activity->location ?? '-' }}</p>
                </div>
                <div>
                  <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Kapasitas</p>
                  <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $activity->capacity ?? '-' }}</p>
                </div>
                <div>
                  <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Peserta Terdaftar</p>
                  <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $activity->registered_count ?? 0 }}</p>
                </div>
                <div class="sm:col-span-2">
                  <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Deskripsi</p>
                  <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $activity->description ?: '-' }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="space-y-4">
          <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800">
              <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Status</h3>
            </div>
            <div class="p-4 sm:p-6 space-y-3">
              <p class="text-sm text-gray-500 dark:text-gray-400">Status saat ini</p>
              <span class="text-theme-xs inline-block rounded-full px-2 py-0.5 font-medium bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                {{ strtoupper(str_replace('_', ' ', $activity->status)) }}
              </span>
              @if($activityId)
              <form action="{{ route('program.activities.update-status', $activityId) }}" method="POST" class="space-y-3">
                @csrf
                <label for="status" class="text-sm font-medium text-gray-700 dark:text-gray-400">Ubah Status</label>
                <select id="status" name="status" required>
                  @foreach(\App\Models\Program\Activity::getAvailableStatuses() as $status)
                    <option value="{{ $status }}" @selected($activity->status === $status)>{{ strtoupper(str_replace('_', ' ', $status)) }}</option>
                  @endforeach
                </select>
                <button type="submit" class="inline-flex w-full items-center justify-center rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                  Simpan Status
                </button>
              </form>
              @endif
            </div>
          </div>

          <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800">
              <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Dokumen & Peserta</h3>
            </div>
            <div class="p-4 sm:p-6 space-y-2">
              <p class="text-sm text-gray-500 dark:text-gray-400">Dokumen: {{ $activity->documents->count() }}</p>
              <p class="text-sm text-gray-500 dark:text-gray-400">Laporan: {{ $activity->reports->count() }}</p>
              <p class="text-sm text-gray-500 dark:text-gray-400">Group Peserta: {{ $activity->participantGroups->count() }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
