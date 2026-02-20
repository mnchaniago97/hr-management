@extends('layouts.app')

@section('title', 'Bidang Program')

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-6">
  <div class="col-span-12">
    <div class="rounded-sm border border-stroke bg-white px-5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
      <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div>
          <h2 class="text-xl font-semibold text-black dark:text-white">Daftar Bidang Program</h2>
          <p class="text-sm text-gray-500 mt-1">Lihat dan filter bidang yang tersedia.</p>
        </div>
        <a href="{{ route('program.fields.create') }}" class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
          Tambah Bidang
        </a>
      </div>

      <form method="GET" class="mb-6">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
          <div>
            <label for="filter_period" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Periode</label>
            <select id="filter_period" name="period_id">
              <option value="">Semua Periode</option>
              @foreach(\App\Models\Program\Period::orderBy('year_start', 'desc')->get() as $period)
                <option value="{{ $period->id }}" @selected((string)request('period_id') === (string)$period->id)>{{ $period->name }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="mt-4 flex flex-wrap items-center justify-end gap-3">
          <a href="{{ route('program.fields.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
            Reset
          </a>
          <button type="submit" class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
            Terapkan Filter
          </button>
        </div>
      </form>

      <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="max-w-full overflow-x-auto custom-scrollbar">
          <table class="w-full min-w-[1000px]">
            <thead>
              <tr class="border-b border-gray-100 dark:border-gray-800">
                <th class="px-5 py-3 text-left sm:px-6">
                  <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">No</p>
                </th>
                <th class="px-5 py-3 text-left sm:px-6">
                  <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Kode</p>
                </th>
                <th class="px-5 py-3 text-left sm:px-6">
                  <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Nama Bidang</p>
                </th>
                <th class="px-5 py-3 text-left sm:px-6">
                  <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Periode</p>
                </th>
                <th class="px-5 py-3 text-left sm:px-6">
                  <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Ketua</p>
                </th>
                <th class="px-5 py-3 text-left sm:px-6">
                  <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Divisi</p>
                </th>
                <th class="px-5 py-3 text-center sm:px-6">
                  <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Aksi</p>
                </th>
              </tr>
            </thead>
            <tbody>
              @forelse($fields as $field)
                <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-white/[0.03]">
                  <td class="px-5 py-4 text-theme-sm text-gray-500 dark:text-gray-400 sm:px-6">{{ $loop->iteration }}</td>
                  <td class="px-5 py-4 text-theme-sm text-gray-500 dark:text-gray-400 sm:px-6">{{ $field->code }}</td>
                  <td class="px-5 py-4 text-theme-sm font-medium text-gray-800 dark:text-white/90 sm:px-6">{{ $field->name }}</td>
                  <td class="px-5 py-4 text-theme-sm text-gray-500 dark:text-gray-400 sm:px-6">{{ $field->period?->name ?? '-' }}</td>
                  <td class="px-5 py-4 text-theme-sm text-gray-500 dark:text-gray-400 sm:px-6">{{ $field->head?->name ?? '-' }}</td>
                  <td class="px-5 py-4 text-theme-sm text-gray-500 dark:text-gray-400 sm:px-6">{{ $field->divisions?->count() ?? 0 }}</td>
                  <td class="px-5 py-4 text-center sm:px-6">
                    <div class="flex items-center justify-center gap-2">
                      <a href="{{ route('program.fields.show', $field) }}" class="text-blue-600 hover:text-blue-800">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                      </a>
                      <a href="{{ route('program.fields.edit', $field) }}" class="text-blue-600 hover:text-blue-800">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                      </a>
                      <form action="{{ route('program.fields.destroy', $field) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Hapus bidang ini?')">
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
                  <td colspan="7" class="px-5 py-8 text-center text-theme-sm text-gray-500 dark:text-gray-400 sm:px-6">
                    Data tidak tersedia. Silakan tambahkan data bidang terlebih dahulu.
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
