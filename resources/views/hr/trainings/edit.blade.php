@extends('layouts.app')

@section('title', 'Edit Pelatihan')

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-6">
  <div class="col-span-12">
    <div class="rounded-sm border border-stroke bg-white px-5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-semibold text-black dark:text-white">Edit Pelatihan</h2>
        <a href="{{ route('hr.trainings.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">Kembali</a>
      </div>

      <form action="{{ route('hr.trainings.update', $training->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
          <div>
            <label class="mb-2 block text-sm font-medium text-black dark:text-white">Nama Pelatihan <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name', $training->name) }}" class="w-full rounded border border-gray-300 bg-white px-4 py-2 dark:border-gray-600 dark:bg-boxdark dark:text-white" required>
          </div>
          <div>
            <label class="mb-2 block text-sm font-medium text-black dark:text-white">Lokasi</label>
            <input type="text" name="location" value="{{ old('location', $training->location) }}" class="w-full rounded border border-gray-300 bg-white px-4 py-2 dark:border-gray-600 dark:bg-boxdark dark:text-white">
          </div>
          <div>
            <label class="mb-2 block text-sm font-medium text-black dark:text-white">Tanggal Mulai <span class="text-red-500">*</span></label>
            <input type="date" name="start_date" value="{{ old('start_date', $training->start_date) }}" class="w-full rounded border border-gray-300 bg-white px-4 py-2 dark:border-gray-600 dark:bg-boxdark dark:text-white" required>
          </div>
          <div>
            <label class="mb-2 block text-sm font-medium text-black dark:text-white">Tanggal Selesai <span class="text-red-500">*</span></label>
            <input type="date" name="end_date" value="{{ old('end_date', $training->end_date) }}" class="w-full rounded border border-gray-300 bg-white px-4 py-2 dark:border-gray-600 dark:bg-boxdark dark:text-white" required>
          </div>
          <div>
            <label class="mb-2 block text-sm font-medium text-black dark:text-white">Kapasitas</label>
            <input type="number" name="capacity" value="{{ old('capacity', $training->capacity) }}" class="w-full rounded border border-gray-300 bg-white px-4 py-2 dark:border-gray-600 dark:bg-boxdark dark:text-white">
          </div>
          <div>
            <label class="mb-2 block text-sm font-medium text-black dark:text-white">Status <span class="text-red-500">*</span></label>
            <select name="status" class="w-full rounded border border-gray-300 bg-white px-4 py-2 dark:border-gray-600 dark:bg-boxdark dark:text-white" required>
              <option value="planned" {{ old('status', $training->status) == 'planned' ? 'selected' : '' }}>Direncanakan</option>
              <option value="ongoing" {{ old('status', $training->status) == 'ongoing' ? 'selected' : '' }}>Sedang Berlangsung</option>
              <option value="completed" {{ old('status', $training->status) == 'completed' ? 'selected' : '' }}>Selesai</option>
              <option value="cancelled" {{ old('status', $training->status) == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
          </div>
          <div class="md:col-span-2">
            <label class="mb-2 block text-sm font-medium text-black dark:text-white">Deskripsi</label>
            <textarea name="description" rows="3" class="w-full rounded border border-gray-300 bg-white px-4 py-2 dark:border-gray-600 dark:bg-boxdark dark:text-white">{{ old('description', $training->description) }}</textarea>
          </div>
        </div>
        <div class="mt-6 flex justify-end gap-4">
          <a href="{{ route('hr.trainings.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50">Batal</a>
          <button type="submit" class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection


