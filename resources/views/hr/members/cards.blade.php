@extends('layouts.app')

@section('title', 'Kartu Anggota')

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-6">
  <div class="col-span-12">
    <div class="rounded-sm border border-stroke bg-white px-5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
      <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div>
          <h2 class="text-xl font-semibold text-black dark:text-white">Kartu Anggota</h2>
          <p class="text-sm text-gray-500 mt-1">Cetak kartu untuk AM/AT/Alumni.</p>
        </div>
        <a href="{{ route('hr.members.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
          Kembali
        </a>
      </div>

      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @forelse($members ?? [] as $member)
        <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/[0.03]">
          <div class="flex items-center gap-3">
            <div class="h-12 w-12 rounded-full bg-gray-100"></div>
            <div>
              <p class="text-sm font-semibold text-gray-800 dark:text-white/90">{{ $member->name }}</p>
              <p class="text-xs text-gray-500">{{ $member->email }}</p>
            </div>
          </div>
          <div class="mt-4 grid grid-cols-2 gap-3 text-xs text-gray-500">
            <div>
              <span class="block font-medium text-gray-700 dark:text-gray-300">NIA</span>
              <span>{{ $member->profile->nia ?? '-' }}</span>
            </div>
            <div>
              <span class="block font-medium text-gray-700 dark:text-gray-300">Status</span>
              <span>{{ $member->status ?? '-' }}</span>
            </div>
          </div>
          <div class="mt-4">
            <a href="{{ route('hr.members.show', $member->id) }}" class="inline-flex items-center rounded-lg bg-brand-500 px-3 py-2 text-xs font-medium text-white shadow-theme-xs hover:bg-brand-600">
              Lihat Detail
            </a>
          </div>
        </div>
        @empty
        <div class="col-span-full rounded-lg border border-dashed border-gray-200 p-6 text-center text-sm text-gray-500 dark:border-gray-800">
          Data anggota belum tersedia.
        </div>
        @endforelse
      </div>
    </div>
  </div>
</div>
@endsection
