@extends('layouts.app')

@section('title', 'Tambah Anggota')

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-6">
  <div class="col-span-12">
    <div class="rounded-sm border border-stroke bg-white px-5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
      <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <h2 class="text-xl font-semibold text-black dark:text-white">Tambah Anggota Baru</h2>
        <a href="{{ route('hr.members.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
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

      <form action="{{ route('hr.members.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
          <!-- Nama Lengkap -->
          <div>
            <label for="name" class="mb-2 block text-sm font-medium text-black dark:text-white">
              Nama Lengkap <span class="text-red-500">*</span>
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

          <!-- NIA -->
          <div>
            <label for="nia" class="mb-2 block text-sm font-medium text-black dark:text-white">
              NIA
            </label>
            <input 
              type="text" 
              id="nia" 
              name="nia" 
              value="{{ old('nia') }}"
              class="w-full rounded border border-gray-300 bg-white px-4 py-2 text-black focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary dark:border-gray-600 dark:bg-boxdark dark:text-white"
            >
          </div>

          <!-- Email -->
          <div>
            <label for="email" class="mb-2 block text-sm font-medium text-black dark:text-white">
              Email <span class="text-red-500">*</span>
            </label>
            <input 
              type="email" 
              id="email" 
              name="email" 
              value="{{ old('email') }}"
              class="w-full rounded border border-gray-300 bg-white px-4 py-2 text-black focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary dark:border-gray-600 dark:bg-boxdark dark:text-white"
              required
            >
          </div>

          <!-- Telepon -->
          <div>
            <label for="phone" class="mb-2 block text-sm font-medium text-black dark:text-white">
              Telepon
            </label>
            <input 
              type="text" 
              id="phone" 
              name="phone" 
              value="{{ old('phone') }}"
              class="w-full rounded border border-gray-300 bg-white px-4 py-2 text-black focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary dark:border-gray-600 dark:bg-boxdark dark:text-white"
            >
          </div>

          <!-- Alamat -->
          <div class="md:col-span-2">
            <label for="address" class="mb-2 block text-sm font-medium text-black dark:text-white">
              Alamat
            </label>
            <textarea 
              id="address" 
              name="address" 
              rows="2"
              class="w-full rounded border border-gray-300 bg-white px-4 py-2 text-black focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary dark:border-gray-600 dark:bg-boxdark dark:text-white"
            >{{ old('address') }}</textarea>
          </div>

          <!-- Tipe Anggota -->
          <div>
            <label for="member_type" class="mb-2 block text-sm font-medium text-black dark:text-white">
              Tipe Anggota <span class="text-red-500">*</span>
            </label>
            <select 
              id="member_type" 
              name="member_type" 
              class="w-full rounded border border-gray-300 bg-white px-4 py-2 text-black focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary dark:border-gray-600 dark:bg-boxdark dark:text-white"
              required
            >
              <option value="">Pilih Tipe Anggota</option>
              <option value="AM" {{ old('member_type') == 'AM' ? 'selected' : '' }}>Anggota Muda (AM)</option>
              <option value="AT" {{ old('member_type') == 'AT' ? 'selected' : '' }}>Anggota Tetap (AT)</option>
              <option value="Alumni" {{ old('member_type') == 'Alumni' ? 'selected' : '' }}>Alumni</option>
            </select>
          </div>

          <!-- Tanggal Lahir -->
          <div>
            <label for="birth_date" class="mb-2 block text-sm font-medium text-black dark:text-white">
              Tanggal Lahir
            </label>
            <input 
              type="date" 
              id="birth_date" 
              name="birth_date" 
              value="{{ old('birth_date') }}"
              class="w-full rounded border border-gray-300 bg-white px-4 py-2 text-black focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary dark:border-gray-600 dark:bg-boxdark dark:text-white"
            >
          </div>

          <!-- Jabatan -->
          <div>
            <label for="position" class="mb-2 block text-sm font-medium text-black dark:text-white">
              Jabatan <span class="text-red-500">*</span>
            </label>
            <input 
              type="text" 
              id="position" 
              name="position" 
              value="{{ old('position') }}"
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
              value="{{ old('department') }}"
              class="w-full rounded border border-gray-300 bg-white px-4 py-2 text-black focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary dark:border-gray-600 dark:bg-boxdark dark:text-white"
              required
            >
          </div>

          <!-- Tanggal Gabung -->
          <div>
            <label for="join_date" class="mb-2 block text-sm font-medium text-black dark:text-white">
              Tanggal Gabung <span class="text-red-500">*</span>
            </label>
            <input 
              type="date" 
              id="join_date" 
              name="join_date" 
              value="{{ old('join_date') }}"
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
              <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif</option>
              <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
              <option value="on_leave" {{ old('status') == 'on_leave' ? 'selected' : '' }}>Cuti</option>
              <option value="terminated" {{ old('status') == 'terminated' ? 'selected' : '' }}>Mengundurkan Diri</option>
            </select>
          </div>

          <!-- Foto -->
          <div class="md:col-span-2">
            <label for="photo" class="mb-2 block text-sm font-medium text-black dark:text-white">
              Foto
            </label>
            <input 
              type="file" 
              id="photo" 
              name="photo" 
              accept="image/*"
              class="w-full rounded border border-gray-300 bg-white px-4 py-2 text-black focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary dark:border-gray-600 dark:bg-boxdark dark:text-white"
            >
            <p class="mt-1 text-xs text-gray-500">Format: JPEG, PNG, JPG, GIF (Max 2MB)</p>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end gap-4">
          <a href="{{ route('hr.members.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
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
