@extends('layouts.app')

@section('title', 'Edit Jabatan')

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-6">
  <div class="col-span-12">
    <div class="rounded-sm border border-stroke bg-white px-5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-semibold text-black dark:text-white">Edit Jabatan</h2>
        <a href="{{ route('hr.positions.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
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

      <form action="{{ route('hr.positions.update', $position->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
          <!-- Nama Jabatan -->
          <div>
            <label for="name" class="mb-2 block text-sm font-medium text-black dark:text-white">
              Nama Jabatan <span class="text-red-500">*</span>
            </label>
            <input 
              type="text" 
              id="name" 
              name="name" 
              value="{{ old('name', $position->name) }}"
              class="w-full rounded border border-gray-300 bg-white px-4 py-2 text-black focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary dark:border-gray-600 dark:bg-boxdark dark:text-white"
              required
            >
          </div>

          <!-- Departemen -->
          <div>
            <label for="department" class="mb-2 block text-sm font-medium text-black dark:text-white">
              Departemen <span class="text-red-500">*</span>
            </label>
            <input 
              type="text" 
              id="department" 
              name="department" 
              value="{{ old('department', $position->department) }}"
              class="w-full rounded border border-gray-300 bg-white px-4 py-2 text-black focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary dark:border-gray-600 dark:bg-boxdark dark:text-white"
              required
            >
          </div>

          <!-- Level -->
          <div>
            <label for="level" class="mb-2 block text-sm font-medium text-black dark:text-white">
              Level <span class="text-red-500">*</span>
            </label>
              id="level" 
              name            <select 
="level" 
              class="w-full rounded border border-gray-300 bg-white px-4 py-2 text-black focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary dark:border-gray-600 dark:bg-boxdark dark:text-white"
              required
            >
              @for($i = 1; $i <= 5; $i++)
                <option value="{{ $i }}" {{ old('level', $position->level) == $i ? 'selected' : '' }}>Level {{ $i }}</option>
              @endfor
            </select>
          </div>

          <!-- Status -->
          <div>
            <label for="is_active" class="mb-2 block text-sm font-medium text-black dark:text-white">
              Status <span class="text-red-500">*</span>
            </label>
            <select 
              id="is_active" 
              name="is_active" 
              class="w-full rounded border border-gray-300 bg-white px-4 py-2 text-black focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary dark:border-gray-600 dark:bg-boxdark dark:text-white"
              required
            >
              <option value="1" {{ old('is_active', $position->is_active) == '1' ? 'selected' : '' }}>Aktif</option>
              <option value="0" {{ old('is_active', $position->is_active) == '0' ? 'selected' : '' }}>Tidak Aktif</option>
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
            >{{ old('description', $position->description) }}</textarea>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="mt-6 flex justify-end gap-4">
          <a href="{{ route('hr.positions.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
            Batal
          </a>
          <button type="submit" class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
            <svg class="mr-2" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M17 21v-8H7v8M7 3v5h8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Update
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection


