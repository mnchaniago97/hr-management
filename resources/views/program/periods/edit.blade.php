@extends('layouts.app')

@section('title', 'Ubah Periode')

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-6">
  <div class="col-span-12">
    <div class="rounded-sm border border-stroke bg-white px-5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
      <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div>
          <h2 class="text-xl font-semibold text-black dark:text-white">Ubah Periode Program</h2>
          <p class="text-sm text-gray-500 mt-1">Perbarui tanggal dan status periode.</p>
        </div>
        <a href="{{ route('program.periods.show', $period->id) }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
          Lihat Detail
        </a>
      </div>

      <form action="{{ route('program.periods.update', $period->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
          <div>
            <label for="year_start" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Tahun Mulai <span class="text-error-500">*</span>
            </label>
            <input type="number" id="year_start" name="year_start" value="{{ old('year_start', $period->year_start) }}" required>
          </div>

          <div>
            <label for="year_end" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Tahun Selesai <span class="text-error-500">*</span>
            </label>
            <input type="number" id="year_end" name="year_end" value="{{ old('year_end', $period->year_end) }}" required>
          </div>

          <div>
            <label for="start_date" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Tanggal Mulai
            </label>
            <input type="date" id="start_date" name="start_date" value="{{ old('start_date', optional($period->start_date)->format('Y-m-d')) }}">
          </div>

          <div>
            <label for="end_date" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Tanggal Selesai
            </label>
            <input type="date" id="end_date" name="end_date" value="{{ old('end_date', optional($period->end_date)->format('Y-m-d')) }}">
          </div>

          <div class="lg:col-span-2">
            <label class="inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-400">
              <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $period->is_active))>
              Set sebagai periode aktif
            </label>
          </div>
        </div>

        <div class="flex flex-wrap justify-end gap-3">
          <a href="{{ route('program.periods.show', $period->id) }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
            Batal
          </a>
          <button type="submit" class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
            Simpan Perubahan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
