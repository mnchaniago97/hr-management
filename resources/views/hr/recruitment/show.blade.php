@extends('layouts.app')

@section('title', 'Detail Calon Anggota')

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-6">
  <div class="col-span-12">
    <div class="rounded-sm border border-stroke bg-white px-5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
      <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <h2 class="text-xl font-semibold text-black dark:text-white">Detail Calon Anggota</h2>
        <div class="flex flex-wrap gap-2">
          <a href="{{ route('hr.recruitment.edit', $member->id) }}" class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">Edit</a>
          <a href="{{ route('hr.recruitment.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">Kembali</a>
        </div>
      </div>

      <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
          <h3 class="mb-4 text-lg font-semibold text-black dark:text-white">Informasi Pribadi</h3>
          <div class="space-y-3">
            <div class="flex justify-between"><span class="text-sm text-gray-500">Nama</span><span class="font-medium">{{ $member->name }}</span></div>
            <div class="flex justify-between"><span class="text-sm text-gray-500">Email</span><span class="font-medium">{{ $member->email }}</span></div>
            <div class="flex justify-between"><span class="text-sm text-gray-500">Telepon</span><span class="font-medium">{{ $member->phone ?? '-' }}</span></div>
            <div class="flex justify-between"><span class="text-sm text-gray-500">Tanggal Gabung</span><span class="font-medium">{{ $member->join_date }}</span></div>
          </div>
        </div>

        <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
          <h3 class="mb-4 text-lg font-semibold text-black dark:text-white">Informasi Kepegawaian</h3>
          <div class="space-y-3">
            <div class="flex justify-between"><span class="text-sm text-gray-500">Jabatan</span><span class="font-medium">{{ $member->position ?? '-' }}</span></div>
            <div class="flex justify-between"><span class="text-sm text-gray-500">Departemen</span><span class="font-medium">{{ $member->department ?? '-' }}</span></div>
            <div class="flex justify-between"><span class="text-sm text-gray-500">Status</span>
              @switch($member->status)
                @case('active')<span class="text-theme-xs inline-block rounded-full px-2 py-0.5 font-medium bg-green-50 text-green-700">Aktif</span>@break
                @case('inactive')<span class="text-theme-xs inline-block rounded-full px-2 py-0.5 font-medium bg-yellow-50 text-yellow-700">Tidak Aktif</span>@break
                @default<span class="text-theme-xs inline-block rounded-full px-2 py-0.5 font-medium bg-gray-100 text-gray-700">{{ $member->status }}</span>
              @endswitch
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection


