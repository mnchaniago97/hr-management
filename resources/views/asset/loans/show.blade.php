@extends('layouts.app')

@section('title', 'Detail Peminjaman')

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-6">
  <div class="col-span-12">
    <div class="rounded-sm border border-stroke bg-white px-5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
      <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div>
          <h2 class="text-xl font-semibold text-black dark:text-white">Detail Peminjaman</h2>
          <p class="text-sm text-gray-500 mt-1">Tampilkan timeline dan dokumen pengembalian.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
          <a href="{{ route('asset.loans.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
            Kembali
          </a>
          <form action="{{ route('asset.loans.destroy', $assignment->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center rounded-lg bg-red-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-red-600" onclick="return confirm('Hapus data peminjaman ini?')">
              Hapus
            </button>
          </form>
        </div>
      </div>

      <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
          <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800">
              <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Informasi Peminjaman</h3>
            </div>
            <div class="p-4 sm:p-6">
              <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                  <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Aset</p>
                  <p class="mt-1 text-sm font-medium text-gray-800 dark:text-white/90">{{ $assignment->item?->name ?? '-' }}</p>
                </div>
                <div>
                  <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Anggota</p>
                  <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $assignment->member?->name ?? '-' }}</p>
                </div>
                <div>
                  <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Tanggal Pinjam</p>
                  <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ optional($assignment->assignment_date)->format('d M Y') ?? '-' }}</p>
                </div>
                <div>
                  <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Tanggal Kembali</p>
                  <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ optional($assignment->return_date)->format('d M Y') ?? '-' }}</p>
                </div>
                <div>
                  <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Pengembalian Aktual</p>
                  <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ optional($assignment->actual_return_date)->format('d M Y') ?? '-' }}</p>
                </div>
                <div>
                  <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Status</p>
                  <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ ucfirst($assignment->status) }}</p>
                </div>
                <div class="sm:col-span-2">
                  <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Catatan</p>
                  <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $assignment->notes ?: '-' }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="space-y-4">
          @if($assignment->status === 'borrowed')
          <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800">
              <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Pengembalian</h3>
            </div>
            <div class="p-4 sm:p-6">
              <form action="{{ route('asset.loans.return', $assignment->id) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                  <label for="condition_notes" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Catatan Kondisi</label>
                  <textarea id="condition_notes" name="condition_notes" rows="3"></textarea>
                </div>
                <button type="submit" class="inline-flex w-full items-center justify-center rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                  Tandai Kembali
                </button>
              </form>
            </div>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
