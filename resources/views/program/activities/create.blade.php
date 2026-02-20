@extends('layouts.app')

@section('title', 'Tambah Kegiatan')

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-6">
  <div class="col-span-12">
    <div class="rounded-sm border border-stroke bg-white px-5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
      <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div>
          <h2 class="text-xl font-semibold text-black dark:text-white">Tambah Kegiatan Program</h2>
          <p class="text-sm text-gray-500 mt-1">Rancang jadwal, lokasi, peserta, dan dokumen.</p>
        </div>
        <a href="{{ route('program.activities.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
          Kembali
        </a>
      </div>

      <form action="{{ route('program.activities.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
          <div>
            <label for="program_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Program <span class="text-error-500">*</span>
            </label>
            <select id="program_id" name="program_id" required>
              <option value="">Pilih Program</option>
              @foreach($programs as $program)
                <option value="{{ $program->id }}" @selected(old('program_id') == $program->id)>{{ $program->name }}</option>
              @endforeach
            </select>
          </div>

          <div>
            <label for="division_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Divisi <span class="text-error-500">*</span>
            </label>
            <select id="division_id" name="division_id" required>
              <option value="">Pilih Divisi</option>
              @foreach($divisions as $division)
                <option value="{{ $division->id }}" @selected(old('division_id') == $division->id)>{{ $division->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="lg:col-span-2">
            <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Nama Kegiatan <span class="text-error-500">*</span>
            </label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: Pelatihan P3K" required>
          </div>

          <div>
            <label for="type" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Tipe <span class="text-error-500">*</span>
            </label>
            <select id="type" name="type" required>
              <option value="">Pilih Tipe</option>
              @foreach($types as $type)
                <option value="{{ $type }}" @selected(old('type') == $type)>{{ strtoupper(str_replace('_', ' ', $type)) }}</option>
              @endforeach
            </select>
          </div>

          <div>
            <label for="status" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Status
            </label>
            <select id="status" name="status">
              <option value="">Draft (Default)</option>
              @foreach($statuses as $status)
                <option value="{{ $status }}" @selected(old('status') == $status)>{{ strtoupper(str_replace('_', ' ', $status)) }}</option>
              @endforeach
            </select>
          </div>

          <div>
            <label for="start_date" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Tanggal Mulai <span class="text-error-500">*</span>
            </label>
            <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}" required>
          </div>

          <div>
            <label for="end_date" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Tanggal Selesai <span class="text-error-500">*</span>
            </label>
            <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}" required>
          </div>

          <div>
            <label for="location" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Lokasi
            </label>
            <input type="text" id="location" name="location" value="{{ old('location') }}" placeholder="Lokasi kegiatan">
          </div>

          <div>
            <label for="capacity" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Kapasitas Peserta
            </label>
            <input type="number" id="capacity" name="capacity" min="0" value="{{ old('capacity') }}" placeholder="0">
          </div>

          <div>
            <label for="registered_count" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Peserta Terdaftar
            </label>
            <input type="number" id="registered_count" name="registered_count" min="0" value="{{ old('registered_count') }}" placeholder="0">
          </div>

          <div class="lg:col-span-2">
            <label for="description" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Deskripsi
            </label>
            <textarea id="description" name="description" rows="4" placeholder="Ringkasan kegiatan...">{{ old('description') }}</textarea>
          </div>
        </div>

        <div class="flex flex-wrap justify-end gap-3">
          <a href="{{ route('program.activities.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
            Batal
          </a>
          <button type="submit" class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
            Simpan Kegiatan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
