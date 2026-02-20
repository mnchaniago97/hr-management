@extends('layouts.app')

@section('title', 'Ubah Maintenance')

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-6">
  <div class="col-span-12">
    <div class="rounded-sm border border-stroke bg-white px-5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
      <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div>
          <h2 class="text-xl font-semibold text-black dark:text-white">Ubah Maintenance</h2>
          <p class="text-sm text-gray-500 mt-1">Perbaiki status, catatan teknis, atau biaya.</p>
        </div>
        <a href="{{ route('asset.maintenance.show', $record->id) }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
          Lihat Detail
        </a>
      </div>

      <form action="{{ route('asset.maintenance.update', $record->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
          <div>
            <label for="item_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Aset <span class="text-error-500">*</span>
            </label>
            <select id="item_id" name="item_id" required>
              <option value="">Pilih Aset</option>
              @foreach($items as $item)
                <option value="{{ $item->id }}" @selected(old('item_id', $record->item_id) == $item->id)>{{ $item->name }}</option>
              @endforeach
            </select>
          </div>

          <div>
            <label for="type" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Tipe <span class="text-error-500">*</span>
            </label>
            <select id="type" name="type" required>
              @foreach($types as $type)
                <option value="{{ $type }}" @selected(old('type', $record->type) == $type)>{{ ucfirst($type) }}</option>
              @endforeach
            </select>
          </div>

          <div>
            <label for="maintenance_date" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Tanggal Maintenance <span class="text-error-500">*</span>
            </label>
            <input type="date" id="maintenance_date" name="maintenance_date" value="{{ old('maintenance_date', optional($record->maintenance_date)->format('Y-m-d')) }}" required>
          </div>

          <div>
            <label for="scheduled_date" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Jadwal
            </label>
            <input type="date" id="scheduled_date" name="scheduled_date" value="{{ old('scheduled_date', optional($record->scheduled_date)->format('Y-m-d')) }}">
          </div>

          <div>
            <label for="status" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Status <span class="text-error-500">*</span>
            </label>
            <select id="status" name="status" required>
              @foreach($statuses as $status)
                <option value="{{ $status }}" @selected(old('status', $record->status) == $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
              @endforeach
            </select>
          </div>

          <div>
            <label for="cost" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Biaya
            </label>
            <input type="number" id="cost" name="cost" min="0" step="0.01" value="{{ old('cost', $record->cost) }}">
          </div>

          <div>
            <label for="vendor_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Vendor
            </label>
            <select id="vendor_id" name="vendor_id">
              <option value="">Pilih Vendor</option>
              @foreach($vendors as $vendor)
                <option value="{{ $vendor->id }}" @selected(old('vendor_id', $record->vendor_id) == $vendor->id)>{{ $vendor->name }}</option>
              @endforeach
            </select>
          </div>

          <div>
            <label for="performed_by" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Dikerjakan Oleh
            </label>
            <input type="text" id="performed_by" name="performed_by" value="{{ old('performed_by', $record->performed_by) }}">
          </div>

          <div class="lg:col-span-2">
            <label for="description" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Deskripsi <span class="text-error-500">*</span>
            </label>
            <textarea id="description" name="description" rows="4" required>{{ old('description', $record->description) }}</textarea>
          </div>

          <div class="lg:col-span-2">
            <label for="notes" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Catatan
            </label>
            <textarea id="notes" name="notes" rows="3">{{ old('notes', $record->notes) }}</textarea>
          </div>
        </div>

        <div class="flex flex-wrap justify-end gap-3">
          <a href="{{ route('asset.maintenance.show', $record->id) }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
            Batal
          </a>
          <button type="submit" class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
            Simpan Perubahan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
