@extends('layouts.app')

@section('title', 'Edit Penempatan')

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-6">
  <div class="col-span-12">
    <div class="rounded-sm border border-stroke bg-white px-5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-semibold text-black dark:text-white">Edit Penempatan</h2>
        <a href="{{ route('hr.assignments.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
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

      <form action="{{ route('hr.assignments.update', $assignment->id) }}" method="POST">
        @csrf
        @method('PUT')

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
                <option value="{{ $member->id }}" {{ old('member_id', $assignment->member_id) == $member->id ? 'selected' : '' }}>{{ $member->name }}</option>
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
              @foreach($divisions ?? [] as $division)
                <option value="{{ $division->id }}" {{ old('division_id', $assignment->division_id) == $division->id ? 'selected' : '' }}>{{ $division->name }}</option>
              @endforeach
            </select>
          </div>

          <!-- Jabatan -->
          <div>
            <label for="position_id" class="mb-2 block text-sm font-medium text-black dark:text-white">
              Jabatan <span class="text-red-500">*</span>
            </label>
            <select 
              id="position_id" 
              name="position_id" 
              class="w-full rounded border border-gray-300 bg-white px-4 py-2 text-black focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary dark:border-gray-600 dark:bg-boxdark dark:text-white"
              required
            >
              <option value="">Pilih Jabatan</option>
              @foreach($positions ?? [] as $position)
                <option value="{{ $position->id }}" {{ old('position_id', $assignment->position_id) == $position->id ? 'selected' : '' }}>{{ $position->name }}</option>
              @endforeach
            </select>
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
              <option value="active" {{ old('status', $assignment->status) == 'active' ? 'selected' : '' }}>Aktif</option>
              <option value="completed" {{ old('status', $assignment->status) == 'completed' ? 'selected' : '' }}>Selesai</option>
              <option value="transferred" {{ old('status', $assignment->status) == 'transferred' ? 'selected' : '' }}>Dipindahkan</option>
            </select>
          </div>

          <!-- Tanggal Penempatan -->
          <div>
            <label for="assigned_date" class="mb-2 block text-sm font-medium text-black dark:text-white">
              Tanggal Penempatan <span class="text-red-500">*</span>
            </label>
            <input 
              type="date" 
              id="assigned_date" 
              name="assigned_date" 
              value="{{ old('assigned_date', $assignment->assigned_date) }}"
              class="w-full rounded border border-gray-300 bg-white px-4 py-2 text-black focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary dark:border-gray-600 dark:bg-boxdark dark:text-white"
              required
            >
          </div>

          <!-- Tanggal Selesai -->
          <div>
            <label for="end_date" class="mb-2 block text-sm font-medium text-black dark:text-white">
              Tanggal Selesai
            </label>
            <input 
              type="date" 
              id="end_date" 
              name="end_date" 
              value="{{ old('end_date', $assignment->end_date) }}"
              class="w-full rounded border border-gray-300 bg-white px-4 py-2 text-black focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary dark:border-gray-600 dark:bg-boxdark dark:text-white"
            >
          </div>

          <!-- Catatan -->
          <div class="md:col-span-2">
            <label for="notes" class="mb-2 block text-sm font-medium text-black dark:text-white">
              Catatan
            </label>
            <textarea 
              id="notes" 
              name="notes" 
              rows="3"
              class="w-full rounded border border-gray-300 bg-white px-4 py-2 text-black focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary dark:border-gray-600 dark:bg-boxdark dark:text-white"
            >{{ old('notes', $assignment->notes) }}</textarea>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="mt-6 flex justify-end gap-4">
          <a href="{{ route('hr.assignments.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
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


