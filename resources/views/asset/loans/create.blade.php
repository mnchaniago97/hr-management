@extends('layouts.app')

@section('title', 'Pinjamkan Aset')

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-6">
  <div class="col-span-12">
    <div class="rounded-sm border border-stroke bg-white px-5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
      <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div>
          <h2 class="text-xl font-semibold text-black dark:text-white">Tambah Peminjaman</h2>
          <p class="text-sm text-gray-500 mt-1">Masukkan anggota, aset, tanggal pinjam.</p>
        </div>
        <a href="{{ route('asset.loans.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
          Kembali
        </a>
      </div>

      <form action="{{ route('asset.loans.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
          <div>
            <label for="member_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Anggota <span class="text-error-500">*</span>
            </label>
            <select id="member_id" name="member_id" required>
              <option value="">Pilih Anggota</option>
              @foreach($members as $member)
                <option value="{{ $member->id }}" @selected(old('member_id') == $member->id)>{{ $member->name }}</option>
              @endforeach
            </select>
          </div>

          <div>
            <label for="item_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Aset <span class="text-error-500">*</span>
            </label>
            <select id="item_id" name="item_id" required>
              <option value="">Pilih Aset</option>
              @foreach($availableItems as $item)
                <option value="{{ $item->id }}" @selected(old('item_id') == $item->id)>{{ $item->name }}</option>
              @endforeach
            </select>
          </div>

          <div>
            <label for="assignment_date" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Tanggal Pinjam <span class="text-error-500">*</span>
            </label>
            <input type="date" id="assignment_date" name="assignment_date" value="{{ old('assignment_date') }}" required>
          </div>

          <div>
            <label for="return_date" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Tanggal Kembali <span class="text-error-500">*</span>
            </label>
            <input type="date" id="return_date" name="return_date" value="{{ old('return_date') }}" required>
          </div>

          <div class="lg:col-span-2">
            <label for="notes" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Catatan
            </label>
            <textarea id="notes" name="notes" rows="4">{{ old('notes') }}</textarea>
          </div>
        </div>

        <div class="flex flex-wrap justify-end gap-3">
          <a href="{{ route('asset.loans.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
            Batal
          </a>
          <button type="submit" class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
            Simpan Peminjaman
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
