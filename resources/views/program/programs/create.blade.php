@extends('layouts.app')

@section('title', 'Tambah Program Kerja')

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-6">
  <div class="col-span-12">
    <div class="rounded-sm border border-stroke bg-white px-5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
      <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <h2 class="text-xl font-semibold text-black dark:text-white">Tambah Program Kerja Baru</h2>
        <a href="{{ route('program.programs.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
          <svg class="mr-2" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M19 12H5M12 19l-7-7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          Kembali
        </a>
      </div>

      @if($errors->any())
      <div class="mb-4 rounded border border-red-200 bg-red-50 px-4 py-3 dark:border-red-800 dark:bg-red-900/20">
        <div class="flex items-center gap-2 text-red-600 dark:text-red-400">
          <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
          </svg>
          <span class="font-medium">Terjadi kesalahan!</span>
        </div>
        <ul class="mt-2 list-inside list-disc text-sm text-red-600 dark:text-red-400">
          @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
      @endif

      <form action="{{ route('program.programs.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
          <!-- Nama Program -->
          <div class="md:col-span-2">
            <label for="name" class="mb-2 block text-sm font-medium text-black dark:text-white">
              Nama Program <span class="text-red-500">*</span>
            </label>
            <input 
              type="text" 
              id="name" 
              name="name" 
              value="{{ old('name') }}"
              class="w-full rounded border border-gray-300 bg-white px-4 py-2 text-black focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary dark:border-gray-600 dark:bg-boxdark dark:text-white"
              required
            >
          </div>

          <!-- Periode -->
          <div>
            <label for="period_id" class="mb-2 block text-sm font-medium text-black dark:text-white">
              Periode <span class="text-red-500">*</span>
            </label>
            <select 
              id="period_id" 
              name="period_id" 
              class="w-full rounded border border-gray-300 bg-white px-4 py-2 text-black focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary dark:border-gray-600 dark:bg-boxdark dark:text-white"
              required
            >
              <option value="">Pilih Periode</option>
              @foreach($periods as $period)
              <option value="{{ $period->id }}" {{ old('period_id') == $period->id ? 'selected' : '' }}>
                {{ $period->name }}
              </option>
              @endforeach
            </select>
          </div>

          <!-- Bidang -->
          <div>
            <label for="field_id" class="mb-2 block text-sm font-medium text-black dark:text-white">
              Bidang <span class="text-red-500">*</span>
            </label>
            <select 
              id="field_id" 
              name="field_id" 
              class="w-full rounded border border-gray-300 bg-white px-4 py-2 text-black focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary dark:border-gray-600 dark:bg-boxdark dark:text-white"
              required
            >
              <option value="">Pilih Bidang</option>
              @foreach($fields as $field)
              <option value="{{ $field->id }}" {{ old('field_id') == $field->id ? 'selected' : '' }}>
                {{ $field->name }}
              </option>
              @endforeach
            </select>
          </div>

          <!-- Divisi -->
          <div>
            <label for="division_id" class="mb-2 block text-sm font-medium text-black dark:text-white">
              Divisi <span class="text-red-500">*</span>
            </label>
            <select 
              id="division_id" 
              name="division_id" 
              class="w-full rounded border border-gray-300 bg-white px-4 py-2 text-black focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary dark:border-gray-600 dark:bg-boxdark dark:text-white"
              required
            >
              <option value="">Pilih Divisi</option>
              @foreach($divisions as $division)
              <option value="{{ $division->id }}" {{ old('division_id') == $division->id ? 'selected' : '' }}>
                {{ $division->name }}
              </option>
              @endforeach
            </select>
          </div>

          <!-- Tipe Program -->
          <div>
            <label for="type" class="mb-2 block text-sm font-medium text-black dark:text-white">
              Tipe Program <span class="text-red-500">*</span>
            </label>
            <select 
              id="type" 
              name="type" 
              class="w-full rounded border border-gray-300 bg-white px-4 py-2 text-black focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary dark:border-gray-600 dark:bg-boxdark dark:text-white"
              required
            >
              <option value="">Pilih Tipe</option>
              <option value="terprogram" {{ old('type') == 'terprogram' ? 'selected' : '' }}>Terprogram</option>
              <option value="insidentil" {{ old('type') == 'insidentil' ? 'selected' : '' }}>Insidentil</option>
            </select>
          </div>

          <!-- Deskripsi -->
          <div class="md:col-span-2">
            <label for="description" class="mb-2 block text-sm font-medium text-black dark:text-white">
              Deskripsi
            </label>
            <textarea 
              id="description" 
              name="description" 
              rows="4"
              class="w-full rounded border border-gray-300 bg-white px-4 py-2 text-black focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary dark:border-gray-600 dark:bg-boxdark dark:text-white"
            >{{ old('description') }}</textarea>
          </div>

          <!-- Target -->
          <div>
            <label for="target" class="mb-2 block text-sm font-medium text-black dark:text-white">
              Target Peserta
            </label>
            <input 
              type="number" 
              id="target" 
              name="target" 
              value="{{ old('target') }}"
              min="0"
              class="w-full rounded border border-gray-300 bg-white px-4 py-2 text-black focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary dark:border-gray-600 dark:bg-boxdark dark:text-white"
            >
          </div>

          <!-- Anggaran -->
          <div>
            <label for="budget" class="mb-2 block text-sm font-medium text-black dark:text-white">
              Anggaran (Rp)
            </label>
            <input 
              type="number" 
              id="budget" 
              name="budget" 
              value="{{ old('budget') }}"
              min="0"
              step="0.01"
              class="w-full rounded border border-gray-300 bg-white px-4 py-2 text-black focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary dark:border-gray-600 dark:bg-boxdark dark:text-white"
            >
          </div>

          <!-- Tanggal Mulai -->
          <div>
            <label for="start_date" class="mb-2 block text-sm font-medium text-black dark:text-white">
              Tanggal Mulai <span class="text-red-500">*</span>
            </label>
            <input 
              type="date" 
              id="start_date" 
              name="start_date" 
              value="{{ old('start_date') }}"
              class="w-full rounded border border-gray-300 bg-white px-4 py-2 text-black focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary dark:border-gray-600 dark:bg-boxdark dark:text-white"
              required
            >
          </div>

          <!-- Tanggal Selesai -->
          <div>
            <label for="end_date" class="mb-2 block text-sm font-medium text-black dark:text-white">
              Tanggal Selesai <span class="text-red-500">*</span>
            </label>
            <input 
              type="date" 
              id="end_date" 
              name="end_date" 
              value="{{ old('end_date') }}"
              class="w-full rounded border border-gray-300 bg-white px-4 py-2 text-black focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary dark:border-gray-600 dark:bg-boxdark dark:text-white"
              required
            >
          </div>

          <!-- Status -->
          <div>
            <label for="status" class="mb-2 block text-sm font-medium text-black dark:text-white">
              Status <span class="text-red-500">*</span>
            </label>
            <select 
              id="status" 
              name="status" 
              class="w-full rounded border border-gray-300 bg-white px-4 py-2 text-black focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary dark:border-gray-600 dark:bg-boxdark dark:text-white"
              required
            >
              <option value="">Pilih Status</option>
              @foreach($statuses as $status)
                <option value="{{ $status }}" {{ old('status') == $status ? 'selected' : '' }}>
                  {{ strtoupper(str_replace('_', ' ', $status)) }}
                </option>
              @endforeach
            </select>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end gap-4">
          <a href="{{ route('program.programs.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
            Batal
          </a>
          <button type="submit" class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
            Simpan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
