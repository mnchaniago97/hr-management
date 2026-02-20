@extends('layouts.app')

@section('title', 'Detail Dokumen')

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-6">
  <div class="col-span-12">
    <div class="rounded-sm border border-stroke bg-white px-5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
      <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div>
          <h2 class="text-xl font-semibold text-black dark:text-white">Detail Dokumen</h2>
          <p class="text-sm text-gray-500 mt-1">Periksa metadata dan tautan unduhan.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
          <a href="{{ route('program.documents.edit', $document->id) }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
            Ubah
          </a>
          <a href="{{ route('program.documents.download', $document->id) }}" class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
            Download
          </a>
          <form action="{{ route('program.documents.destroy', $document->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center rounded-lg bg-red-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-red-600" onclick="return confirm('Hapus dokumen ini?')">
              Hapus
            </button>
          </form>
          <a href="{{ route('program.documents.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
            Kembali
          </a>
        </div>
      </div>

      <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800">
          <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Informasi Dokumen</h3>
          <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Detail dokumen yang diunggah.</p>
        </div>
        <div class="p-4 sm:p-6">
          <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
              <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Nama File</p>
              <p class="mt-1 text-sm font-medium text-gray-800 dark:text-white/90">{{ $document->file_name }}</p>
            </div>
            <div>
              <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Jenis</p>
              <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ \App\Models\Program\Document::getTypeLabel($document->type) }}</p>
            </div>
            <div>
              <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Program</p>
              <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $document->program?->name ?? '-' }}</p>
            </div>
            <div>
              <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Kegiatan</p>
              <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $document->activity?->name ?? '-' }}</p>
            </div>
            <div>
              <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Diunggah Oleh</p>
              <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $document->uploader?->name ?? '-' }}</p>
            </div>
            <div>
              <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Tanggal Upload</p>
              <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ optional($document->uploaded_at)->format('d M Y H:i') ?? '-' }}</p>
            </div>
            <div class="sm:col-span-2">
              <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Lokasi File</p>
              <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $document->file_path }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
