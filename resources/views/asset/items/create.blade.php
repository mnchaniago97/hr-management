@extends('layouts.app')

@section('title', 'Tambah Aset')

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-6">
  <div class="col-span-12">
    <div class="rounded-sm border border-stroke bg-white px-5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
      <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div>
          <h2 class="text-xl font-semibold text-black dark:text-white">Tambah Aset</h2>
          <p class="text-sm text-gray-500 mt-1">Input nama, kategori, kondisi, dan lokasi aset.</p>
        </div>
        <a href="{{ route('asset.items.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
          Kembali
        </a>
      </div>

      <form action="{{ route('asset.items.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
          <div class="lg:col-span-2">
            <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Nama Aset <span class="text-error-500">*</span>
            </label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required>
          </div>

          <div>
            <label for="code" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Kode Aset <span class="text-error-500">*</span>
            </label>
            <input type="text" id="code" name="code" value="{{ old('code') }}" required>
          </div>

          <div>
            <label for="category" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Kategori <span class="text-error-500">*</span>
            </label>
            <select id="category" name="category" required>
              <option value="">Pilih Kategori</option>
              @foreach($categories as $category)
                <option value="{{ $category }}" @selected(old('category') == $category)>{{ ucfirst($category) }}</option>
              @endforeach
            </select>
          </div>

          <div>
            <label for="location" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Lokasi <span class="text-error-500">*</span>
            </label>
            <input type="text" id="location" name="location" value="{{ old('location') }}" required>
          </div>

          <div>
            <label for="purchase_date" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Tanggal Pembelian
            </label>
            <input type="date" id="purchase_date" name="purchase_date" value="{{ old('purchase_date') }}">
          </div>

          <div>
            <label for="purchase_price" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Harga Pembelian
            </label>
            <input type="number" id="purchase_price" name="purchase_price" min="0" step="0.01" value="{{ old('purchase_price') }}">
          </div>

          <div>
            <label for="condition" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Kondisi <span class="text-error-500">*</span>
            </label>
            <select id="condition" name="condition" required>
              @foreach($conditions as $condition)
                <option value="{{ $condition }}" @selected(old('condition') == $condition)>{{ ucfirst($condition) }}</option>
              @endforeach
            </select>
          </div>

          <div>
            <label for="status" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Status <span class="text-error-500">*</span>
            </label>
            <select id="status" name="status" required>
              @foreach($statuses as $status)
                <option value="{{ $status }}" @selected(old('status') == $status)>{{ ucfirst($status) }}</option>
              @endforeach
            </select>
          </div>

          <div class="lg:col-span-2">
            <label for="description" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Deskripsi
            </label>
            <textarea id="description" name="description" rows="4">{{ old('description') }}</textarea>
          </div>

          <div class="lg:col-span-2">
            <label for="image" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Foto Aset
            </label>
            <input type="file" id="image" name="image">
          </div>
        </div>

        <div class="flex flex-wrap justify-end gap-3">
          <a href="{{ route('asset.items.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
            Batal
          </a>
          <button type="submit" class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
            Simpan Aset
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
