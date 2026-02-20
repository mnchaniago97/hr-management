@extends('layouts.app')

@section('title', 'Edit Calon Anggota')

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-6">
  <div class="col-span-12">
    <div class="rounded-sm border border-stroke bg-white px-5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-semibold text-black dark:text-white">Edit Calon Anggota</h2>
        <a href="{{ route('hr.recruitment.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
          <svg class="mr-2" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M19 12H5M12 19l-7-7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          Kembali
        </a>
      </div>

      <form action="{{ route('hr.recruitment.update', $member->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
          <div>
            <label for="name" class="mb-2 block text-sm font-medium text-black dark:text-white">Nama Lengkap <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name', $member->name) }}" class="w-full rounded border border-gray-300 bg-white px-4 py-2 dark:border-gray-600 dark:bg-boxdark dark:text-white" required>
          </div>

          <div>
          </div>

          <div>
            <label for="phone" class="mb-2 block text-sm font-medium text-black dark:text-white">Telepon</label>
            <input type="text" name="phone" value="{{ old('phone', $member->phone) }}" class="w-full rounded border border-gray-300 bg-white px-4 py-2 dark:border-gray-600 dark:bg-boxdark dark:text-white">
          </div>

          <div>
            <label for="position" class="mb-2 block text-sm font-medium text-black dark:text-white">Jabatan <span class="text-red-500">*</span></label>
            <input type="text" name="position" value="{{ old('position', $member->position) }}" class="w-full rounded border border-gray-300 bg-white px-4 py-2 dark:border-gray-600 dark:bg-boxdark dark:text-white" required>
          </div>

          <div>
            <label for="department" class="mb-2 block text-sm font-medium text-black dark:text-white">Departemen <span class="text-red-500">*</span></label>
            <input type="text" name="department" value="{{ old('department', $member->department) }}" class="w-full rounded border border-gray-300 bg-white px-4 py-2 dark:border-gray-600 dark:bg-boxdark dark:text-white" required>
          </div>


          <div>
            <label for="status" class="mb-2 block text-sm font-medium text-black dark:text-white">Status <span class="text-red-500">*</span></label>
            <select name="status" class="w-full rounded border border-gray-300 bg-white px-4 py-2 dark:border-gray-600 dark:bg-boxdark dark:text-white" required>
              <option value="active" {{ old('status', $member->status) == 'active' ? 'selected' : '' }}>Aktif</option>
              <option value="inactive" {{ old('status', $member->status) == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
              <option value="on_leave" {{ old('status', $member->status) == 'on_leave' ? 'selected' : '' }}>Cuti</option>
            </select>
          </div>
        </div>

        <div class="mt-6 flex justify-end gap-4">
          <a href="{{ route('hr.recruitment.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">Batal</a>
          <button type="submit" class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection


