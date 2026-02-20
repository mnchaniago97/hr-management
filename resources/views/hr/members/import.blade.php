@extends('layouts.app')

@section('title', 'Impor Anggota')

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-6">
  <div class="col-span-12">
    <div class="rounded-sm border border-stroke bg-white px-5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
      <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div>
          <h2 class="text-xl font-semibold text-black dark:text-white">Impor Anggota</h2>
          <p class="text-sm text-gray-500 mt-1">Unggah Excel untuk memuat banyak anggota.</p>
        </div>
        <a href="{{ route('hr.members.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
          Kembali
        </a>
      </div>

      @if (session('success'))
      <div class="mb-4 rounded-lg border border-green-100 bg-green-50 px-4 py-3 text-sm text-green-700 dark:border-green-500/20 dark:bg-green-500/10 dark:text-green-400">
        {{ session('success') }}
      </div>
      @endif

      @if (session('error'))
      <div class="mb-4 rounded-lg border border-red-100 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-500/20 dark:bg-red-500/10 dark:text-red-400">
        {{ session('error') }}
      </div>
      @endif

      @if ($errors->any())
      <div class="mb-4 rounded-lg border border-red-100 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-500/20 dark:bg-red-500/10 dark:text-red-400">
        <ul class="list-disc space-y-1 pl-5">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
      @endif

      @if (session('importFailures'))
      <div class="mb-4 rounded-lg border border-red-100 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-500/20 dark:bg-red-500/10 dark:text-red-400">
        <p class="font-medium mb-1">Detail gagal import:</p>
        <ul class="list-disc space-y-1 pl-5">
          @foreach (session('importFailures') as $failure)
            <li>{{ $failure }}</li>
          @endforeach
        </ul>
      </div>
      @endif

      <form action="{{ route('hr.members.import.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <div>
          <label class="mb-2 block text-sm font-medium text-black dark:text-white">File Excel <span class="text-red-500">*</span></label>
          <input type="file" name="file" accept=".xls,.xlsx,.csv" required>
          <p class="mt-2 text-xs text-gray-500">Kolom wajib: name, email, position, department, join_date. Opsional: phone, status, member_type, nia.</p>
        </div>
        <div class="flex justify-end gap-3">
          <a href="{{ route('hr.members.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
            Batal
          </a>
          <button type="submit" class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
            Unggah
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
