@extends('layouts.app')

@section('title', 'Tambah Kategori Aset')

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-6">
  <div class="col-span-12">
    <div class="rounded-sm border border-stroke bg-white px-5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
      <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <h2 class="text-xl font-semibold text-black dark:text-white">Tambah Kategori Aset Baru</h2>
        <a href="{{ route('asset.categories.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
          Kembali
        </a>
      </div>

      <form action="{{ route('asset.categories.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
          <div>
            <label for="code" class="mb-2 block text-sm font-medium text-black dark:text-white">
              Kode Kategori <span class="text-red-500">*</span>
            </label>
            <input
              type="text"
              id="code"
              name="code"
              value="{{ old('code') }}"
              required
            >
          </div>

          <div class="md:col-span-2">
            <label for="name" class="mb-2 block text-sm font-medium text-black dark:text-white">
              Nama Kategori <span class="text-red-500">*</span>
            </label>
            <input 
              type="text" 
              id="name" 
              name="name" 
              value="{{ old('name') }}"
              required
            >
          </div>

          <div class="md:col-span-2">
            <label for="parent_id" class="mb-2 block text-sm font-medium text-black dark:text-white">
              Kategori Induk
            </label>
            <select id="parent_id" name="parent_id">
              <option value="">Tidak Ada</option>
              @foreach($parentCategories as $parent)
                <option value="{{ $parent->id }}" @selected(old('parent_id') == $parent->id)>{{ $parent->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="md:col-span-2">
            <label for="description" class="mb-2 block text-sm font-medium text-black dark:text-white">
              Deskripsi
            </label>
            <textarea 
              id="description" 
              name="description" 
              rows="3"
            >{{ old('description') }}</textarea>
          </div>

          <div class="md:col-span-2">
            <label class="inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-400">
              <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true))>
              Aktif
            </label>
          </div>
        </div>

        <div class="flex justify-end gap-4">
          <a href="{{ route('asset.categories.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
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
