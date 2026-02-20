@extends('layouts.app')

@section('title', 'Laporan Program')

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-6">
  <div class="col-span-12">
    <div class="rounded-sm border border-stroke bg-white px-5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
      <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div>
          <h2 class="text-xl font-semibold text-black dark:text-white">Ringkasan Laporan</h2>
          <p class="text-sm text-gray-500 mt-1">Pilih filter lalu lihat visualisasi ringkas.</p>
        </div>
        <a href="{{ route('program.reports.export') }}" class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
          Export
        </a>
      </div>

      <form class="mb-6">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-4">
          <div>
            <label for="period_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Periode</label>
            <select id="period_id" name="period_id">
              <option value="">Semua Periode</option>
              @foreach(\App\Models\Program\Period::orderBy('year_start', 'desc')->get() as $period)
                <option value="{{ $period->id }}">{{ $period->name }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label for="field_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Bidang</label>
            <select id="field_id" name="field_id">
              <option value="">Semua Bidang</option>
              @foreach(\App\Models\Program\Field::orderBy('name')->get() as $field)
                <option value="{{ $field->id }}">{{ $field->name }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label for="division_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Divisi</label>
            <select id="division_id" name="division_id">
              <option value="">Semua Divisi</option>
              @foreach(\App\Models\Program\Division::orderBy('name')->get() as $division)
                <option value="{{ $division->id }}">{{ $division->name }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label for="month" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Bulan</label>
            <input type="month" id="month" name="month">
          </div>
        </div>
        <div class="mt-4 flex flex-wrap items-center justify-end gap-3">
          <button type="submit" class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
            Tampilkan Laporan
          </button>
        </div>
      </form>

      <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800">
          <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Hasil Laporan</h3>
        </div>
        <div class="p-4 sm:p-6">
          <div class="rounded-lg border border-dashed border-gray-200 p-6 text-center text-sm text-gray-500 dark:border-gray-800 dark:text-gray-400">
            Hasil laporan akan muncul di sini setelah memilih filter.
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
