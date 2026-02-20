@extends('layouts.app')

@section('title', 'Tambah Bidang')

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-6">
  <div class="col-span-12">
    <div class="rounded-sm border border-stroke bg-white px-5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
      <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div>
          <h2 class="text-xl font-semibold text-black dark:text-white">Tambah Bidang Program</h2>
          <p class="text-sm text-gray-500 mt-1">Tambahkan nama dan deskripsi bidang baru.</p>
        </div>
        <a href="{{ route('program.fields.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
          Kembali
        </a>
      </div>

      <form action="{{ route('program.fields.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
          <div>
            <label for="period_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Periode <span class="text-error-500">*</span>
            </label>
            <select id="period_id" name="period_id" required>
              <option value="">Pilih Periode</option>
              @foreach($periods as $period)
                <option value="{{ $period->id }}" @selected(old('period_id') == $period->id)>{{ $period->name }}</option>
              @endforeach
            </select>
          </div>

          <div>
            <label for="code" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Kode Bidang <span class="text-error-500">*</span>
            </label>
            <input type="text" id="code" name="code" value="{{ old('code') }}" placeholder="BIDANG_1" required>
          </div>

          <div class="lg:col-span-2">
            <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Nama Bidang <span class="text-error-500">*</span>
            </label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required>
          </div>

          <div>
            <label for="head_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Ketua Bidang
            </label>
            <select id="head_id" name="head_id">
              <option value="">Pilih Ketua</option>
              @foreach(\App\Models\Hr\Member::orderBy('name')->get() as $member)
                <option value="{{ $member->id }}" @selected(old('head_id') == $member->id)>{{ $member->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="lg:col-span-2">
            <label for="description" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Deskripsi
            </label>
            <textarea id="description" name="description" rows="4" placeholder="Deskripsi bidang...">{{ old('description') }}</textarea>
          </div>
        </div>

        <div class="flex flex-wrap justify-end gap-3">
          <a href="{{ route('program.fields.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
            Batal
          </a>
          <button type="submit" class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
            Simpan Bidang
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
