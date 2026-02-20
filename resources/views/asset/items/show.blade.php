@extends('layouts.app')

@section('title', 'Detail Aset')

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-6">
  <div class="col-span-12">
    <div class="rounded-sm border border-stroke bg-white px-5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
      <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div>
          <h2 class="text-xl font-semibold text-black dark:text-white">Detail Aset</h2>
          <p class="text-sm text-gray-500 mt-1">Lihat riwayat pemakaian dan maintenance.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
          <a href="{{ route('asset.items.edit', $item->id) }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
            Ubah
          </a>
          <form action="{{ route('asset.items.destroy', $item->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center rounded-lg bg-red-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-red-600" onclick="return confirm('Hapus aset ini?')">
              Hapus
            </button>
          </form>
          <a href="{{ route('asset.items.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
            Kembali
          </a>
        </div>
      </div>

      <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
          <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800">
              <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Informasi Aset</h3>
            </div>
            <div class="p-4 sm:p-6">
              <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                  <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Nama</p>
                  <p class="mt-1 text-sm font-medium text-gray-800 dark:text-white/90">{{ $item->name }}</p>
                </div>
                <div>
                  <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Kode</p>
                  <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $item->code }}</p>
                </div>
                <div>
                  <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Kategori</p>
                  <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $item->category }}</p>
                </div>
                <div>
                  <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Lokasi</p>
                  <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $item->location }}</p>
                </div>
                <div>
                  <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Kondisi</p>
                  <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ ucfirst($item->condition) }}</p>
                </div>
                <div>
                  <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Status</p>
                  <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ ucfirst($item->status) }}</p>
                </div>
                <div>
                  <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Tanggal Beli</p>
                  <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ optional($item->purchase_date)->format('d M Y') ?? '-' }}</p>
                </div>
                <div>
                  <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Harga Beli</p>
                  <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $item->purchase_price ?? '-' }}</p>
                </div>
                <div class="sm:col-span-2">
                  <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Deskripsi</p>
                  <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $item->description ?: '-' }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="space-y-4">
          <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800">
              <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Riwayat Peminjaman</h3>
            </div>
            <div class="p-4 sm:p-6">
              <p class="text-sm text-gray-500 dark:text-gray-400">Total peminjaman: {{ $item->assetAssignments?->count() ?? 0 }}</p>
              <a href="{{ route('asset.assignments.index', ['search' => $item->name]) }}" class="text-sm font-medium text-brand-500 hover:text-brand-600">
                Lihat peminjaman
              </a>
            </div>
          </div>
          <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800">
              <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Maintenance</h3>
            </div>
            <div class="p-4 sm:p-6">
              <a href="{{ route('asset.maintenance.index', ['search' => $item->name]) }}" class="text-sm font-medium text-brand-500 hover:text-brand-600">
                Lihat maintenance
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
