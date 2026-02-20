@extends('layouts.app')

@section('title', 'Detail Kategori Aset')

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-6">
  <div class="col-span-12">
    <div class="rounded-sm border border-stroke bg-white px-5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
      <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div>
          <h2 class="text-xl font-semibold text-black dark:text-white">Detail Kategori Aset</h2>
          <p class="text-sm text-gray-500 mt-1">Lihat detail kategori aset.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
          <a href="{{ route('asset.categories.edit', $category->id) }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
            Ubah
          </a>
          <form action="{{ route('asset.categories.destroy', $category->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center rounded-lg bg-red-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-red-600" onclick="return confirm('Hapus kategori ini?')">
              Hapus
            </button>
          </form>
          <a href="{{ route('asset.categories.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
            Kembali
          </a>
        </div>
      </div>

      <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800">
          <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Informasi Kategori</h3>
        </div>
        <div class="p-4 sm:p-6">
          <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
              <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Nama</p>
              <p class="mt-1 text-sm font-medium text-gray-800 dark:text-white/90">{{ $category->name }}</p>
            </div>
            <div>
              <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Kode</p>
              <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $category->code }}</p>
            </div>
            <div>
              <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Kategori Induk</p>
              <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $category->parent?->name ?? '-' }}</p>
            </div>
            <div>
              <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Status</p>
              <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $category->is_active ? 'Aktif' : 'Nonaktif' }}</p>
            </div>
            <div class="sm:col-span-2">
              <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Deskripsi</p>
              <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $category->description ?: '-' }}</p>
            </div>
            <div>
              <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Jumlah Aset</p>
              <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $category->assetItems?->count() ?? 0 }}</p>
            </div>
            <div>
              <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Subkategori</p>
              <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $category->subcategories?->count() ?? 0 }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
