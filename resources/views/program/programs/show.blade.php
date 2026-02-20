@extends('layouts.app')

@section('title', 'Detail Program')

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-6">
  <div class="col-span-12">
    <div class="rounded-sm border border-stroke bg-white px-5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
      <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div>
          <h2 class="text-xl font-semibold text-black dark:text-white">Detail Program Kerja</h2>
          <p class="text-sm text-gray-500 mt-1">Lihat ringkasan, kegiatan, dan dokumen terkait.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
          <a href="{{ route('program.programs.edit', $program->id) }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
            Ubah
          </a>
          <form action="{{ route('program.programs.destroy', $program->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center rounded-lg bg-red-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-red-600" onclick="return confirm('Hapus program ini?')">
              Hapus
            </button>
          </form>
          <a href="{{ route('program.programs.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
            Kembali
          </a>
        </div>
      </div>

      <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
          <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800">
              <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Informasi Program</h3>
              <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Detail utama program kerja.</p>
            </div>
            <div class="p-4 sm:p-6">
              <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                  <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Nama Program</p>
                  <p class="mt-1 text-sm font-medium text-gray-800 dark:text-white/90">{{ $program->name }}</p>
                </div>
                <div>
                  <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Periode</p>
                  <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $program->period?->name ?? '-' }}</p>
                </div>
                <div>
                  <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Bidang</p>
                  <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $program->field?->name ?? '-' }}</p>
                </div>
                <div>
                  <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Divisi</p>
                  <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $program->division?->name ?? '-' }}</p>
                </div>
                <div>
                  <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Tipe</p>
                  <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ strtoupper(str_replace('_', ' ', $program->type)) }}</p>
                </div>
                <div>
                  <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Status</p>
                  <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ strtoupper(str_replace('_', ' ', $program->status)) }}</p>
                </div>
                <div>
                  <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Tanggal</p>
                  <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">
                    {{ optional($program->start_date)->format('d M Y') }} - {{ optional($program->end_date)->format('d M Y') }}
                  </p>
                </div>
                <div>
                  <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Anggaran</p>
                  <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $program->budget ?? '-' }}</p>
                </div>
                <div class="sm:col-span-2">
                  <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Deskripsi</p>
                  <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $program->description ?: '-' }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="space-y-4">
          <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800">
              <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Ringkasan Kegiatan</h3>
            </div>
            <div class="p-4 sm:p-6 space-y-2">
              <p class="text-sm text-gray-500 dark:text-gray-400">Jumlah kegiatan: {{ $program->activities?->count() ?? 0 }}</p>
              <a href="{{ route('program.activities.index', ['program_id' => $program->id]) }}" class="text-sm font-medium text-brand-500 hover:text-brand-600">
                Lihat kegiatan terkait
              </a>
            </div>
          </div>
          <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800">
              <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Dokumen</h3>
            </div>
            <div class="p-4 sm:p-6 space-y-2">
              <p class="text-sm text-gray-500 dark:text-gray-400">Dokumen terkait: {{ $program->documents?->count() ?? 0 }}</p>
              <a href="{{ route('program.documents.index', ['program_id' => $program->id]) }}" class="text-sm font-medium text-brand-500 hover:text-brand-600">
                Lihat dokumen
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
