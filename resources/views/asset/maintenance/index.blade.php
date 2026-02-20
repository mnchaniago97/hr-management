@extends('layouts.app')

@section('title', 'Maintenance Aset')

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-6">
  <div class="col-span-12">
    <div class="rounded-sm border border-stroke bg-white px-5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
      <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div>
          <h2 class="text-xl font-semibold text-black dark:text-white">Daftar Maintenance</h2>
          <p class="text-sm text-gray-500 mt-1">Catat jadwal perawatan rutin atau insidentil.</p>
        </div>
        <a href="{{ route('asset.maintenance.create') }}" class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
          Tambah Maintenance
        </a>
      </div>

      <form method="GET" class="mb-6">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-4">
          <div>
            <label for="search" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Pencarian</label>
            <input type="text" id="search" name="search" value="{{ $search }}" placeholder="Nama aset / kode">
          </div>
          <div>
            <label for="status" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Status</label>
            <select id="status" name="status">
              <option value="">Semua Status</option>
              @foreach($statuses as $statusOption)
                <option value="{{ $statusOption }}" @selected($status === $statusOption)>{{ ucfirst(str_replace('_', ' ', $statusOption)) }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label for="type" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Tipe</label>
            <select id="type" name="type">
              <option value="">Semua Tipe</option>
              @foreach($types as $typeOption)
                <option value="{{ $typeOption }}" @selected($type === $typeOption)>{{ ucfirst($typeOption) }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label for="date_from" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Dari Tanggal</label>
            <input type="date" id="date_from" name="date_from" value="{{ $dateFrom }}">
          </div>
        </div>
        <div class="mt-4 flex flex-wrap items-center justify-end gap-3">
          <a href="{{ route('asset.maintenance.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
            Reset
          </a>
          <button type="submit" class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
            Terapkan Filter
          </button>
        </div>
      </form>

      <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="max-w-full overflow-x-auto custom-scrollbar">
          <table class="w-full min-w-[1100px]">
            <thead>
              <tr class="border-b border-gray-100 dark:border-gray-800">
                <th class="px-5 py-3 text-left sm:px-6">
                  <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">No</p>
                </th>
                <th class="px-5 py-3 text-left sm:px-6">
                  <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Aset</p>
                </th>
                <th class="px-5 py-3 text-left sm:px-6">
                  <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Tipe</p>
                </th>
                <th class="px-5 py-3 text-left sm:px-6">
                  <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Tanggal</p>
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
              @forelse($records as $record)
                <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-white/[0.03]">
                  <td class="px-5 py-4 text-theme-sm text-gray-500 dark:text-gray-400 sm:px-6">{{ $loop->iteration }}</td>
                  <td class="px-5 py-4 text-theme-sm font-medium text-gray-800 dark:text-white/90 sm:px-6">{{ $record->item?->name ?? '-' }}</td>
                  <td class="px-5 py-4 text-theme-sm text-gray-500 dark:text-gray-400 sm:px-6">{{ ucfirst($record->type) }}</td>
                  <td class="px-5 py-4 text-theme-sm text-gray-500 dark:text-gray-400 sm:px-6">{{ optional($record->maintenance_date)->format('d M Y') ?? '-' }}</td>
                  <td class="px-5 py-4 text-theme-sm text-gray-500 dark:text-gray-400 sm:px-6">{{ ucfirst(str_replace('_', ' ', $record->status)) }}</td>
                  <td class="px-5 py-4 text-center sm:px-6">
                    <div class="flex items-center justify-center gap-2">
                      <a href="{{ route('asset.maintenance.show', $record->id) }}" class="text-blue-600 hover:text-blue-800">Detail</a>
                      <a href="{{ route('asset.maintenance.edit', $record->id) }}" class="text-blue-600 hover:text-blue-800">Ubah</a>
                      <form action="{{ route('asset.maintenance.destroy', $record->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Hapus data maintenance ini?')">Hapus</button>
                      </form>
                    </div>
                  </td>
                </tr>
              @empty
                <tr class="border-b border-gray-100 dark:border-gray-800">
                  <td colspan="6" class="px-5 py-8 text-center text-theme-sm text-gray-500 dark:text-gray-400 sm:px-6">
                    Data maintenance belum tersedia.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
