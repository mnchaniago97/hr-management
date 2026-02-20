@extends('layouts.app')

@section('title', 'Inventaris Aset')

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-6">
  <div class="col-span-12">
    <div class="rounded-sm border border-stroke bg-white px-5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-semibold text-black dark:text-white">Data Inventaris Aset</h2>
        <div class="flex gap-2">
          <a href="{{ route('asset.items.export', request()->query()) }}" class="inline-flex items-center rounded-lg border border-brand-500 px-4 py-2 text-sm font-medium text-brand-500 shadow-theme-xs hover:bg-brand-500/10">
            Export Excel
          </a>
          <button type="button" id="toggleImportCard" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
            Import
          </button>
          <a href="{{ route('asset.items.create') }}" class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
            <svg class="mr-2" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M12 4V20M4 12H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            Tambah Aset
          </a>
        </div>
      </div>

      @if (session('success'))
      <div class="mb-4 rounded-lg border border-green-100 bg-green-50 px-4 py-3 text-sm text-green-700 dark:border-green-500/20 dark:bg-green-500/10 dark:text-green-400">
        {{ session('success') }}
      </div>
      @endif

      @if (session('error'))
      <div class="mb-4 rounded-lg border border-red-100 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-500/20 dark:bg-red-500/10 dark:text-red-400">
        {{ session('error') }}
      </div>
      @endif

      @if ($errors->any())
      <div class="mb-4 rounded-lg border border-red-100 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-500/20 dark:bg-red-500/10 dark:text-red-400">
        <ul class="list-disc space-y-1 pl-5">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
      @endif

      @if (session('importFailures'))
      <div class="mb-4 rounded-lg border border-red-100 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-500/20 dark:bg-red-500/10 dark:text-red-400">
        <p class="font-medium mb-1">Detail gagal import:</p>
        <ul class="list-disc space-y-1 pl-5">
          @foreach (session('importFailures') as $failure)
            <li>{{ $failure }}</li>
          @endforeach
        </ul>
      </div>
      @endif

      <div id="importCard" class="mb-6 hidden rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/[0.03]">
        <form method="POST" action="{{ route('asset.items.import') }}" enctype="multipart/form-data" class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
          @csrf
          <div class="flex-1">
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Import Excel</label>
            <input type="file" name="file" accept=".xlsx,.xls,.csv" required>
            <p class="mt-1 text-xs text-gray-500">Kolom wajib: name, code, category, location, condition, status. Opsional: purchase_date, purchase_price, description.</p>
          </div>
          <div class="flex gap-2">
            <button type="submit" class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
              Import Data
            </button>
          </div>
        </form>
      </div>

      <!-- Stats Summary -->
      <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="rounded border border-gray-200 p-4 dark:border-gray-700">
          <p class="text-sm text-gray-500">Total Aset</p>
          <p class="text-2xl font-bold text-black dark:text-white">{{ $stats['total'] ?? 0 }}</p>
        </div>
        <div class="rounded border border-gray-200 p-4 dark:border-gray-700">
          <p class="text-sm text-gray-500">Tersedia</p>
          <p class="text-2xl font-bold text-success">{{ $stats['available'] ?? 0 }}</p>
        </div>
        <div class="rounded border border-gray-200 p-4 dark:border-gray-700">
          <p class="text-sm text-gray-500">Dipinjam</p>
          <p class="text-2xl font-bold text-warning">{{ $stats['borrowed'] ?? 0 }}</p>
        </div>
        <div class="rounded border border-gray-200 p-4 dark:border-gray-700">
          <p class="text-sm text-gray-500">Maintenance</p>
          <p class="text-2xl font-bold text-danger">{{ $stats['maintenance'] ?? 0 }}</p>
        </div>
      </div>

      <!-- Filters -->
      <form method="GET" action="{{ route('asset.items.index') }}" class="mb-6">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-6">
          <div class="lg:col-span-2">
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Pencarian</label>
            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama, kode, atau deskripsi...">
          </div>
          <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Status</label>
            <select name="status">
              <option value="">Semua Status</option>
              @foreach($statuses as $statusOption)
                <option value="{{ $statusOption }}" @selected(($status ?? '') === $statusOption)>{{ ucfirst($statusOption) }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Kondisi</label>
            <select name="condition">
              <option value="">Semua Kondisi</option>
              @foreach($conditions as $conditionOption)
                <option value="{{ $conditionOption }}" @selected(($condition ?? '') === $conditionOption)>{{ ucfirst($conditionOption) }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Kategori</label>
            <select name="category">
              <option value="">Semua Kategori</option>
              @foreach($categories as $categoryOption)
                <option value="{{ $categoryOption }}" @selected(($category ?? '') === $categoryOption)>{{ ucfirst($categoryOption) }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Lokasi</label>
            <select name="location">
              <option value="">Semua Lokasi</option>
              @foreach($locations as $locationOption)
                <option value="{{ $locationOption }}" @selected(($location ?? '') === $locationOption)>{{ $locationOption }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="mt-4 flex flex-wrap items-center justify-end gap-3">
          <a href="{{ route('asset.items.create') }}" class="inline-flex items-center rounded-lg border border-brand-500 px-4 py-2 text-sm font-medium text-brand-500 shadow-theme-xs hover:bg-brand-500/10">
            Tambah Aset
          </a>
          <a href="{{ route('asset.items.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
            Reset
          </a>
          <button type="submit" class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">Cari</button>
        </div>
      </form>

      <!-- Table -->
      <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="max-w-full overflow-x-auto custom-scrollbar">
          <table class="w-full min-w-[900px]">
            <thead>
              <tr class="border-b border-gray-100 dark:border-gray-800">
                <th class="px-5 py-3 text-left sm:px-6">
                  <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">No</p>
                </th>
                <th class="px-5 py-3 text-left sm:px-6">
                  <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Kode Aset</p>
                </th>
                <th class="px-5 py-3 text-left sm:px-6">
                  <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Nama Aset</p>
                </th>
                <th class="px-5 py-3 text-left sm:px-6">
                  <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Kategori</p>
                </th>
                <th class="px-5 py-3 text-left sm:px-6">
                  <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Lokasi</p>
                </th>
                <th class="px-5 py-3 text-left sm:px-6">
                  <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Kondisi</p>
                </th>
                <th class="px-5 py-3 text-left sm:px-6">
                  <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Status</p>
                </th>
                <th class="px-5 py-3 text-center sm:px-6">
                  <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Aksi</p>
                </th>
              </tr>
            </thead>
            <tbody>
              @forelse($items ?? [] as $index => $item)
              <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-white/[0.03]">
                <td class="px-5 py-4 text-theme-sm text-gray-500 dark:text-gray-400 sm:px-6">
                  {{ ($items->firstItem() ?? 1) + $index }}
                </td>
                <td class="px-5 py-4 text-theme-sm font-mono text-gray-500 dark:text-gray-400 sm:px-6">{{ $item->code }}</td>
                <td class="px-5 py-4 text-theme-sm font-medium text-gray-800 dark:text-white/90 sm:px-6">{{ $item->name }}</td>
                <td class="px-5 py-4 text-theme-sm text-gray-500 dark:text-gray-400 sm:px-6">{{ $item->category ?? '-' }}</td>
                <td class="px-5 py-4 text-theme-sm text-gray-500 dark:text-gray-400 sm:px-6">{{ $item->location ?? '-' }}</td>
                <td class="px-5 py-4 text-theme-sm sm:px-6">
                  @php
                    $conditionLabel = $item->condition === 'good' ? 'Baik' : ($item->condition === 'fair' ? 'Cukup' : ($item->condition === 'poor' ? 'Rusak' : 'Baru'));
                    $conditionClass = $item->condition === 'good'
                      ? 'bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500'
                      : ($item->condition === 'fair'
                        ? 'bg-yellow-50 text-yellow-700 dark:bg-yellow-500/15 dark:text-yellow-400'
                        : ($item->condition === 'poor'
                          ? 'bg-red-50 text-red-700 dark:bg-red-500/15 dark:text-red-500'
                          : 'bg-blue-50 text-blue-700 dark:bg-blue-500/15 dark:text-blue-400'));
                  @endphp
                  <span class="text-theme-xs inline-block rounded-full px-2 py-0.5 font-medium {{ $conditionClass }}">
                    {{ $conditionLabel }}
                  </span>
                </td>
                <td class="px-5 py-4 text-theme-sm sm:px-6">
                  @php
                    $statusLabel = $item->status === 'available' ? 'Tersedia' : ($item->status === 'borrowed' ? 'Dipinjam' : ($item->status === 'maintenance' ? 'Maintenance' : 'Dijual/Dibuang'));
                    $statusClass = $item->status === 'available'
                      ? 'bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500'
                      : ($item->status === 'borrowed'
                        ? 'bg-yellow-50 text-yellow-700 dark:bg-yellow-500/15 dark:text-yellow-400'
                        : ($item->status === 'maintenance'
                          ? 'bg-red-50 text-red-700 dark:bg-red-500/15 dark:text-red-500'
                          : 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300'));
                  @endphp
                  <span class="text-theme-xs inline-block rounded-full px-2 py-0.5 font-medium {{ $statusClass }}">
                    {{ $statusLabel }}
                  </span>
                </td>
                <td class="px-5 py-4 text-center sm:px-6">
                  <div class="flex items-center justify-center gap-2">
                    <a href="{{ route('asset.items.show', $item->id) }}" class="text-blue-600 hover:text-blue-800">
                      <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                      </svg>
                    </a>
                    <a href="{{ route('asset.items.edit', $item->id) }}" class="text-blue-600 hover:text-blue-800">
                      <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                      </svg>
                    </a>
                    <form action="{{ route('asset.items.destroy', $item->id) }}" method="POST" class="inline">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Apakah Anda yakin ingin menghapus aset ini?')">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
              @empty
              <tr class="border-b border-gray-100 dark:border-gray-800">
                <td colspan="8" class="px-5 py-8 text-center text-theme-sm text-gray-500 dark:text-gray-400 sm:px-6">
                  Data tidak tersedia. Silakan tambahkan data aset terlebih dahulu.
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      @if(isset($items) && $items->hasPages())
      <div class="mt-4 flex items-center justify-between">
        <p class="text-sm text-gray-500">Menampilkan {{ $items->firstItem() }} - {{ $items->lastItem() }} dari {{ $items->total() }} data</p>
        {{ $items->appends(request()->query())->links('pagination::tailwind') }}
      </div>
      @endif
    </div>
  </div>
</div>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const toggleButton = document.getElementById('toggleImportCard');
    const importCard = document.getElementById('importCard');

    if (toggleButton && importCard) {
      toggleButton.addEventListener('click', function () {
        importCard.classList.toggle('hidden');
      });
    }
  });
</script>
@endpush
@endsection
