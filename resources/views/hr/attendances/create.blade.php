@extends('layouts.app')

@section('title', 'Tambah Absensi')

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-6">
  <div class="col-span-12">
    <div class="rounded-sm border border-stroke bg-white px-5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-semibold text-black dark:text-white">Tambah Absensi</h2>
        <a href="{{ route('hr.attendances.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
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

      <form action="{{ route('hr.attendances.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
          <!-- Anggota -->
          <div>
            <label for="member_id" class="mb-2 block text-sm font-medium text-black dark:text-white">
              Anggota <span class="text-red-500">*</span>
            </label>
            <select 
              id="member_id" 
              name="member_id" 
              class="w-full rounded border border-gray-300 bg-white px-4 py-2 text-black focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary dark:border-gray-600 dark:bg-boxdark dark:text-white"
              required
            >
              <option value="">Pilih Anggota</option>
              @foreach($members ?? [] as $member)
                <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>{{ $member->name }}</option>
              @endforeach
            </select>
          </div>

          <!-- Tanggal -->
          <div>
            <label for="date" class="mb-2 block text-sm font-medium text-black dark:text-white">
              Tanggal <span class="text-red-500">*</span>
            </label>
            <input 
              type="date" 
              id="date" 
              name="date" 
              value="{{ old('date', date('Y-m-d')) }}"
              class="w-full rounded border border-gray-300 bg-white px-4 py-2 text-black focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary dark:border-gray-600 dark:bg-boxdark dark:text-white"
              required
            >
          </div>

          <!-- Check In -->
          <div>
            <label for="check_in" class="mb-2 block text-sm font-medium text-black dark:text-white">
              Jam Masuk
            </label>
            <input 
              type="time" 
              id="check_in" 
              name="check_in" 
              value="{{ old('check_in') }}"
              class="w-full rounded border border-gray-300 bg-white px-4 py-2 text-black focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary dark:border-gray-600 dark:bg-boxdark dark:text-white"
            >
          </div>

          <!-- Check Out -->
          <div>
            <label for="check_out" class="mb-2 block text-sm font-medium text-black dark:text-white">
              Jam Keluar
            </label>
            <input 
              type="time" 
              id="check_out" 
              name="check_out" 
              value="{{ old('check_out') }}"
              class="w-full rounded border border-gray-300 bg-white px-4 py-2 text-black focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary dark:border-gray-600 dark:bg-boxdark dark:text-white"
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
              <option value="present" {{ old('status') == 'present' ? 'selected' : '' }}>Hadir</option>
              <option value="absent" {{ old('status') == 'absent' ? 'selected' : '' }}>Tidak Hadir</option>
              <option value="late" {{ old('status') == 'late' ? 'selected' : '' }}>Terlambat</option>
              <option value="on_leave" {{ old('status') == 'on_leave' ? 'selected' : '' }}>Cuti</option>
            </select>
          </div>

          <!-- Catatan -->
          <div>
            <label for="notes" class="mb-2 block text-sm font-medium text-black dark:text-white">
              Catatan
            </label>
            <textarea 
              id="notes" 
              name="notes" 
              rows="3"
              class="w-full rounded border border-gray-300 bg-white px-4 py-2 text-black focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary dark:border-gray-600 dark:bg-boxdark dark:text-white"
            >{{ old('notes') }}</textarea>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="mt-6 flex justify-end gap-4">
          <a href="{{ route('hr.attendances.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
            Batal
          </a>
          <button type="submit" class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
            <svg class="mr-2" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M17 21v-8H7v8M7 3v5h8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Simpan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection


